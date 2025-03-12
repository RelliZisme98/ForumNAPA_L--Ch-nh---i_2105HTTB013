<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xem trang này.";
    exit; // Dừng thực thi nếu chưa đăng nhập
}

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID từ phiên
$user_id = $_SESSION['user_id']; 

// Truy vấn để lấy thông tin người dùng
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu người dùng tồn tại
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); 
    $profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'images/resources/author.jpg'; 
} else {
    echo "Không tìm thấy người dùng.";
    exit; 
}

// Xử lý upload hình ảnh
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    // Đường dẫn đến thư mục lưu hình ảnh
    $target_dir = "uploads/profile_pictures/";

    // Kiểm tra xem thư mục đã tồn tại hay chưa
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); 
    }

    // Đường dẫn tệp tin mục tiêu
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra xem tệp tin có phải là hình ảnh không
    if (isset($_FILES['profile_picture']['tmp_name']) && $_FILES['profile_picture']['tmp_name'] != "") {
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Tệp tin không phải là hình ảnh.";
            $uploadOk = 0;
        }
    } else {
        echo "Không có tệp tin nào được tải lên.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước tệp
    if ($_FILES['profile_picture']['size'] > 2000000) {
        echo "Xin lỗi, tệp tin của bạn quá lớn.";
        $uploadOk = 0;
    }

    // Cho phép chỉ định các định dạng tệp
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Xin lỗi, chỉ cho phép tệp JPG, JPEG và PNG.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu không có lỗi nào
    if ($uploadOk == 0) {
        echo "Xin lỗi, tệp của bạn không được tải lên.";
    } else {
        // Cố gắng upload tệp
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            echo "Tệp " . htmlspecialchars(basename($_FILES['profile_picture']['name'])) . " đã được tải lên.";

            // Cập nhật hình ảnh của người dùng trong cơ sở dữ liệu
            $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $user_id);
            if ($stmt->execute()) {
                echo "Cập nhật hình ảnh thành công.";
                // Cập nhật biến profile_picture để hiển thị ảnh mới ngay lập tức
                $profile_picture = $target_file;
            } else {
                echo "Có lỗi xảy ra khi cập nhật hình ảnh.";
            }
            $stmt->close(); // Đóng đối tượng statement
        } else {
            echo "Xin lỗi, có lỗi xảy ra khi tải tệp lên.";
        }
    }
}

// Truy vấn để lấy thông tin từ bảng user_profiles
$sql_profile = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $user_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();

$user_profile_data = $result_profile->fetch_assoc(); // Lấy thông tin hồ sơ người dùng
$stmt_profile->close();

$sql_hobbies = "SELECT * FROM hobbies WHERE user_id = ?";
$stmt_hobbies = $conn->prepare($sql_hobbies);
$stmt_hobbies->bind_param("i", $user_id);
$stmt_hobbies->execute();
$result_hobbies = $stmt_hobbies->get_result();
$user_hobbies_data = [];
$main_interests = [];
$other_interests = [];
while ($row = $result_hobbies->fetch_assoc()) {
    $user_hobbies_data[] = $row;
}
$stmt_hobbies->close();

foreach ($user_hobbies_data as $hobby) {
    if ($hobby['is_main'] == 1) {
        $main_interests[] = $hobby['hobby_name']; 
    } else {
        $other_interests[] = $hobby['hobby_name']; 
    }
}

$sql_education = "SELECT degree, institution, graduation_year FROM education WHERE user_id = ?";
$stmt_education = $conn->prepare($sql_education);
$stmt_education->bind_param("i", $user_id);
$stmt_education->execute();
$result_education = $stmt_education->get_result();
$education = $result_education->fetch_assoc();
$stmt_education->close();

// Get Work Experience data for the user
$sql_work = "SELECT position, company_name, years_of_experience FROM work_experience WHERE user_id = ?";
$stmt_work = $conn->prepare($sql_work);
$stmt_work->bind_param("i", $user_id);
$stmt_work->execute();
$result_work = $stmt_work->get_result();
$work_experience = $result_work->fetch_assoc();
$stmt_work->close();

// SQL query to get social networks of the user
$sql_social_networks = "SELECT platform_name, profile_url FROM social_networks WHERE user_id = ?";
$stmt_social_networks = $conn->prepare($sql_social_networks);
$stmt_social_networks->bind_param("i", $user_id);
$stmt_social_networks->execute();
$result_social_networks = $stmt_social_networks->get_result();

// Array to store social network data
$social_networks = [];

// Fetch and store all social network links
while ($row = $result_social_networks->fetch_assoc()) {
    $social_networks[] = $row;
}

$stmt_social_networks->close();

// SQL query to get favorite movies with image and link
$sql_favorite_movies = "SELECT movie_name, year, image_url, movie_link FROM favorite_movies WHERE user_id = ?";
$stmt_favorite_movies = $conn->prepare($sql_favorite_movies);
$stmt_favorite_movies->bind_param("i", $user_id);
$stmt_favorite_movies->execute();
$result_favorite_movies = $stmt_favorite_movies->get_result();

// Array to store favorite movies
$favorite_movies = [];

// Fetch and store all favorite movies
while ($row = $result_favorite_movies->fetch_assoc()) {
    $favorite_movies[] = $row;
}

$stmt_favorite_movies->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>NAPA Social Network</title>
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">

</head>
<body>
<div class="wavy-wraper">
		<div class="wavy">
		  <span style="--i:1;">f</span>
		  <span style="--i:2;">o</span>
		  <span style="--i:3;">r</span>
		  <span style="--i:4;">u</span>
		  <span style="--i:5;">m</span>
		  <span style="--i:6;">N</span>
		  <span style="--i:7;">A</span>
		  <span style="--i:8;">P</span>
		  <span style="--i:9;">A</span>
		</div>
	</div>
<div class="theme-layout">
	
	
<?php include 'component/header.php'; ?>
<?php include 'component/sidebarright.php'; ?>
<?php include 'component/sidebarleft.php'; ?>

		
<section>
		<div class="gap2 gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="row merged20" id="page-contents">
										<div class="col-lg-10 col-md-9">
											<ul class="profile-menu">
												<li>
													<a class="" href="timeline.php">Timeline</a>
												</li>
												<li>
													<a class="" href="about.php">About</a>
												</li>
												<li>
													<a class="active" href="timeline_friends.php">Friends</a>
												</li>
												<li>
													<a class="" href="timeline_photos.php">Photos</a>
												</li>
												<li>
													<a class="" href="timeline_videos.php">Videos</a>
												</li>
												<li>
													<div class="more">
														<i class="fa fa-ellipsis-h"></i>
														<ul class="more-dropdown">
															<li>
																<a href="timeline_groups.php">Profile Groups</a>
															</li>
															<li>
																<a href="statistics.html">Profile Analytics</a>
															</li>
														</ul>
													</div>
												</li>
											</ul>
											
										</div>
									</div>
								</div>	
							</div><!-- user profile banner  -->
							<div class="col-lg-3">
								<aside class="sidebar static">
									<div class="widget">
										<h4 class="widget-title">Shortcuts</h4>
										<ul class="naves">
											<li>
												<i class="ti-clipboard"></i>
												<a href="newsfeed.html" title="">News feed</a>
											</li>
											<li>
												<i class="ti-mouse-alt"></i>
												<a href="inbox.html" title="">Inbox</a>
											</li>
											<li>
												<i class="ti-files"></i>
												<a href="fav-page.html" title="">My pages</a>
											</li>
											<li>
												<i class="ti-user"></i>
												<a href="timeline_friends.php" title="">friends</a>
											</li>
											<li>
												<i class="ti-image"></i>
												<a href="timeline_photos.php" title="">images</a>
											</li>
											<li>
												<i class="ti-video-camera"></i>
												<a href="timeline_videos.php" title="">videos</a>
											</li>
											<li>
												<i class="ti-comments-smiley"></i>
												<a href="messages.html" title="">Messages</a>
											</li>
											<li>
												<i class="ti-bell"></i>
												<a href="notifications.html" title="">Notifications</a>
											</li>
											<li>
												<i class="ti-share"></i>
												<a href="people-nearby.html" title="">People Nearby</a>
											</li>
											<li>
												<i class="fa fa-bar-chart-o"></i>
												<a href="insights.html" title="">insights</a>
											</li>
											<li>
												<i class="ti-power-off"></i>
												<a href="landing.html" title="">Logout</a>
											</li>
										</ul>
									</div><!-- Shortcuts -->
									<div class="widget">
										<h4 class="widget-title">Profile intro</h4>
										<ul class="short-profile">
											<li>
												<span>about</span>
												<p>Hi, i am jhon kates, i am 32 years old and worked as a web developer in microsoft company. </p>
											</li>
											<li>
												<span>fav tv show</span>
												<p>Sacred Games, Spartcus Blood, Games of theron</p>
											</li>
											<li>
												<span>favourit music</span>
												<p>Justin Biber, Nati Natsha, Shakira</p>
											</li>
										</ul>
									</div><!-- profile intro widget -->

								</aside>
							</div><!-- sidebar -->
							<div class="col-lg-9">
								<div class="central-meta">
									<div class="title-block">
										<div class="row">
											<div class="col-lg-6">
												<div class="align-left">
													<h5>Photos <span>62</span></h5>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="row merged20">
													<div class="col-lg-7 col-md-7 col-sm-7">
														<form method="post">
															<input type="text" placeholder="Search Photo">
															<button type="submit"><i class="fa fa-search"></i></button>
														</form>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-4">
														<div class="select-options">
															<select class="select">
																<option>Sort by</option>
																<option>A to Z</option>
																<option>See All</option>
																<option>Newest</option>
																<option>oldest</option>
															</select>
														</div>
													</div>
													<div class="col-lg-1 col-md-1 col-sm-1">
														<div class="option-list">
															<i class="fa fa-ellipsis-v"></i>
															<ul>
																<li class="active"><i class="fa fa-check"></i><a title="" href="#">Show Public</a></li>
																<li><a title="" href="#">Show only Friends</a></li>
																<li><a title="" href="#">Hide all Posts</a></li>
																<li><a title="" href="#">Mute Notifications</a></li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div><!-- title block -->
								<div class="central-meta">
									<div class="row merged5">
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<div class="item-upload album">
													<i class="fa fa-plus-circle"></i>
													<div class="upload-meta">
														<h5>Upload photo or album</h5>
														<span>its only take a few seconds!</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-101.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo1.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 15</a>
													<span>20 hours ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-22.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo2.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 20</a>
													<span>20 days ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-33.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo3.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 155</a>
													<span>Yesterday</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-44.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo4.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 201</a>
													<span>3 weeks ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-55.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo5.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 81</a>
													<span>2 months ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-66.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo6.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 98</a>
													<span>1 day</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-77.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo7.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 87</a>
													<span>23 hours ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-88.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo8.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 60</a>
													<span>3 days ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-99.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo9.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 897</a>
													<span>1 year ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1001.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo10.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 983</a>
													<span>2 years ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1011.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo11.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 40</a>
													<span>4 days ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1012.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo12.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 15</a>
													<span>20 hours ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1013.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo13.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 59</a>
													<span>20 days ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1014.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo14.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 66</a>
													<span>1 year ago</span>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
											<div class="item-box">
												<a class="strip" href="images/resources/photo-1015.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
												<img src="images/resources/photo15.jpg" alt=""></a>
												<div class="over-photo">
													<a href="#" title=""><i class="fa fa-heart"></i> 98</a>
													<span>9 months ago</span>
												</div>
											</div>
										</div>
									</div>
									
									<div class="lodmore">
										<span>Viewing 1-15 of 62 Pictures</span>
										<button class="btn-view btn-load-more"></button>
									</div>
								</div><!-- photos -->
							</div><!-- centerl meta -->
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</section>

	<a title="Your Cart Items" href="shop-cart.html" class="shopping-cart" data-toggle="tooltip">Cart <i class="fa fa-shopping-bag"></i><span>02</span></a>

	<?php include 'component/footer.php'; ?>
</div>
	<div class="side-panel">
			<h4 class="panel-title">General Setting</h4>
			<form method="post">
				<div class="setting-row">
					<span>use night mode</span>
					<input type="checkbox" id="nightmode1"/> 
					<label for="nightmode1" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Notifications</span>
					<input type="checkbox" id="switch22" /> 
					<label for="switch22" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Notification sound</span>
					<input type="checkbox" id="switch33" /> 
					<label for="switch33" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>My profile</span>
					<input type="checkbox" id="switch44" /> 
					<label for="switch44" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Show profile</span>
					<input type="checkbox" id="switch55" /> 
					<label for="switch55" data-on-label="ON" data-off-label="OFF"></label>
				</div>
			</form>
			<h4 class="panel-title">Account Setting</h4>
			<form method="post">
				<div class="setting-row">
					<span>Sub users</span>
					<input type="checkbox" id="switch66" /> 
					<label for="switch66" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>personal account</span>
					<input type="checkbox" id="switch77" /> 
					<label for="switch77" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Business account</span>
					<input type="checkbox" id="switch88" /> 
					<label for="switch88" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Show me online</span>
					<input type="checkbox" id="switch99" /> 
					<label for="switch99" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Delete history</span>
					<input type="checkbox" id="switch101" /> 
					<label for="switch101" data-on-label="ON" data-off-label="OFF"></label>
				</div>
				<div class="setting-row">
					<span>Expose author name</span>
					<input type="checkbox" id="switch111" /> 
					<label for="switch111" data-on-label="ON" data-off-label="OFF"></label>
				</div>
			</form>
		</div><!-- side panel -->
	
	<div class="popup-wraper5">
		<div class="popup">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<h5>Upload Pictures & Videos</h5>
				</div>
				<div class="upload-boxes">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="smal-box">
								<label class="fileContainer">
									<i class=" ti-layout-media-center-alt"></i>
									<input type="file">
									<em>Upload New</em>
									<span>Choose form Computer</span>
								</label>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="smal-box">
								<div class="from-gallery">
									<i class="ti-layout-grid2"></i>
									<em>From Gallery Or Album</em>
									<span>Choose form already uploaded</span>
								</div>
							</div>
						</div>
					</div>
					<div class="sugested-photos">
						<h5>Suggested Photos & Videos <a href="#" title="">See All</a></h5>
						<ul class="sugestd-photo-caro">
							<li><img src="images/resources/sug1.jpg" alt=""></li>
							<li><img src="images/resources/sug2.jpg" alt=""></li>
							<li><img src="images/resources/sug3.jpg" alt=""></li>
							<li><img src="images/resources/sug4.jpg" alt=""></li>
							<li><img src="images/resources/sug5.jpg" alt=""></li>
						</ul>
					</div>
					<a class="main-btn" href="#" title="" data-ripple="">Proceed</a>
				</div>
			</div>
		</div>
		<div class="already-gallery">
			<div class="popup-head">
				<h5>Choose from gallery</h5>
			</div>
			<ul class="sugestd-photo-caro">
				<li><img src="images/resources/sug1.jpg" alt=""></li>
				<li><img src="images/resources/sug2.jpg" alt=""></li>
				<li><img src="images/resources/sug3.jpg" alt=""></li>
				<li><img src="images/resources/sug4.jpg" alt=""></li>
				<li><img src="images/resources/sug5.jpg" alt=""></li>
				<li><img src="images/resources/sug6.jpg" alt=""></li>
				<li><img src="images/resources/sug7.jpg" alt=""></li>
				<li><img src="images/resources/sug4.jpg" alt=""></li>
				<li><img src="images/resources/sug1.jpg" alt=""></li>
				<li><img src="images/resources/sug2.jpg" alt=""></li>
				<li><img src="images/resources/sug4.jpg" alt=""></li>
				<li><img src="images/resources/sug5.jpg" alt=""></li>
				<li><img src="images/resources/sug6.jpg" alt=""></li>
				<li><img src="images/resources/sug7.jpg" alt=""></li>
				<li><img src="images/resources/sug1.jpg" alt=""></li>
				<li><img src="images/resources/sug4.jpg" alt=""></li>
				<li><img src="images/resources/sug5.jpg" alt=""></li>
			</ul>
			<a class="main-btn" href="#" title="" data-ripple="">Confirm</a>
			<a class="main-btn canceld" href="#" title="" data-ripple="">Cancel</a>
		</div>
	</div><!-- upload popup -->
	
	<div class="popup-wraper1">
		<div class="popup direct-mesg">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<h5>Send Message</h5>
				</div>
				<div class="send-message">
					<form method="post" class="c-form">
						<input type="text" placeholder="Sophia">
						<textarea placeholder="Write Message"></textarea>
						<button type="submit" class="main-btn">Send</button>
					</form>
					<div class="add-smiles">
						<div class="uploadimage">
							<i class="fa fa-image"></i>
							<label class="fileContainer">
								<input type="file">
							</label>
						</div>
						<span title="add icon" class="em em-expressionless"></span>
						<div class="smiles-bunch">
							<i class="em em---1"></i>
							<i class="em em-smiley"></i>
							<i class="em em-anguished"></i>
							<i class="em em-laughing"></i>
							<i class="em em-angry"></i>
							<i class="em em-astonished"></i>
							<i class="em em-blush"></i>
							<i class="em em-disappointed"></i>
							<i class="em em-worried"></i>
							<i class="em em-kissing_heart"></i>
							<i class="em em-rage"></i>
							<i class="em em-stuck_out_tongue"></i>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div><!-- send message popup -->	
	
	<div class="popup-wraper3">
		<div class="popup">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<h5>Report Post</h5>
				</div>
				<div class="Rpt-meta">
					<span>We're sorry something's wrong. How can we help?</span>
					<form method="post" class="c-form">
						<div class="form-radio">
						  <div class="radio">
							<label>
							  <input type="radio" name="radio" checked="checked"><i class="check-box"></i>It's spam or abuse
							</label>
						  </div>
						  <div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>It breaks r/technology's rules
							</label>
						  </div>
							<div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>Not Related
							</label>
						  </div>
							<div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>Other issues
							</label>
						  </div>
						</div>
					<div>
						<label>Write about Report</label>
						<textarea placeholder="write someting about Post" rows="2"></textarea>
					</div>
					<div>
						<button data-ripple="" type="submit" class="main-btn">Submit</button>
						<a href="#" data-ripple="" class="main-btn3 cancel">Close</a>
					</div>
					</form>	
				</div>
			</div>	
		</div>
	</div><!-- report popup -->

		
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>

</body>	

</html>