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
						<div class="user-profile">
								<figure>
									<div class="edit-pp">
										<label class="fileContainer">
											<i class="fa fa-camera"></i>
											<input type="file">
										</label>
									</div>
									<img src="images/resources/profile-image.jpg" alt="">
									<ul class="profile-controls">
										<li><a href="#" title="Add friend" data-toggle="tooltip"><i class="fa fa-user-plus"></i></a></li>
										<li><a href="#" title="Follow" data-toggle="tooltip"><i class="fa fa-star"></i></a></li>
										<li><a class="send-mesg" href="#" title="Send Message" data-toggle="tooltip"><i class="fa fa-comment"></i></a></li>
										<li>
											<div class="edit-seting" title="Edit Profile image"><i class="fa fa-sliders"></i>
												<ul class="more-dropdown">
													<li><a href="setting.html" title="">Update Profile Photo</a></li>
													<li><a href="setting.html" title="">Update Header Photo</a></li>
													<li><a href="setting.html" title="">Account Settings</a></li>
													<li><a href="support-and-help.html" title="">Find Support</a></li>
													<li><a class="bad-report" href="#" title="">Report Profile</a></li>
													<li><a href="#" title="">Block Profile</a></li>
												</ul>
											</div>
										</li>
									</ul>
									<ol class="pit-rate">
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class=""><i class="fa fa-star"></i></li>
										<li><span>4.7/5</span></li>
									</ol>
								</figure>
								
								<div class="profile-section">
									<div class="row">
										<div class="col-lg-2 col-md-3">
											<div class="profile-author">
												<div class="profile-author-thumb">
													<img alt="author" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
													<div class="edit-dp">
														<label class="fileContainer">
															<i class="fa fa-camera"></i>
															<input type="file">
														</label>
													</div>
												</div>
													
												<div class="author-content">
                                                        <a class="h4 author-name" href="about.php"><?php echo htmlspecialchars($user['username']); ?></a>
                                                        <div class="country"><?php echo htmlspecialchars($user_profile_data['country']); ?></div>
                                                    </div>
											</div>
											</div>
										</div>
										<div class="col-lg-10 col-md-9">
											<ul class="profile-menu">
												<li>
													<a class="active" href="timeline.php">Timeline</a>
												</li>
												<li>
													<a class="" href="about.php">About</a>
												</li>
												<li>
													<a class="" href="timeline_friends.php">Friends</a>
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
											<ol class="folw-detail">
												<li><span>Posts</span><ins>101</ins></li>
												<li><span>Followers</span><ins>1.3K</ins></li>
												<li><span>Following</span><ins>22</ins></li>
											</ol>
										</div>
									</div>
								</div>	
							</div><!-- user profile banner  -->
							<div class="col-lg-9">
								<div class="central-meta">
									<div class="about">
										<div class="d-flex flex-row mt-2">
											<ul class="nav nav-tabs nav-tabs--vertical nav-tabs--left" >
												<li class="nav-item">
													<a href="#gen-setting" class="nav-link active" data-toggle="tab" ><i class="fa fa-gear"></i> General Setting</a>
												</li>
												<li class="nav-item">
													<a href="#edit-profile" class="nav-link" data-toggle="tab" ><i class="fa fa-pencil"></i> Edit Profile</a>
												</li>
												<li class="nav-item">
													<a href="#notifi" class="nav-link" data-toggle="tab" ><i class="fa fa-bell"></i> Notification</a>
												</li>
												<li class="nav-item">
													<a href="#messages" class="nav-link" data-toggle="tab" ><i class="fa fa-comment"></i> Messages</a>
												</li>
												<li class="nav-item">
													<a href="#weather" class="nav-link" data-toggle="tab" ><i class="fa fa-cloud"></i> Weather Setting</a>
												</li>
												<li class="nav-item">
													<a href="#page-manage" class="nav-link" data-toggle="tab" ><i class="fa fa-pencil-square-o"></i>Widgets Setting</a>
												</li>
												<li class="nav-item">
													<a href="#privacy" class="nav-link" data-toggle="tab"  ><i class="fa fa-shield"></i> Privacy & Data</a>
												</li>
												<li class="nav-item">
													<a href="#security" class="nav-link" data-toggle="tab" ><i class="fa fa-lock"></i> Security</a>
												</li>
												<li class="nav-item">
													<a href="#apps" class="nav-link" data-toggle="tab" ><i class="fa fa-th"></i> Apps</a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade show active" id="gen-setting" >
													<div class="set-title">
														<h5>General Setting</h5>
														<span>Set your login preference, help us personalize your experience and make big account change here.</span>
													</div>
													<div class="onoff-options ">
														<form method="post">
															<div class="setting-row">
																<span>Sub users</span>
																<p>Enable this if you want people to follow you</p>
																<input type="checkbox" id="switch00" /> 
																<label for="switch00" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Enable follow me</span>
																<p>Enable this if you want people to follow you</p>
																<input type="checkbox" id="switch01" /> 
																<label for="switch01" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Send me notifications</span>
																<p>Send me notification emails my friends like, share or message me</p>
																<input type="checkbox" id="switch02" /> 
																<label for="switch02" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Text messages</span>
																<p>Send me messages to my cell phone</p>
																<input type="checkbox" id="switch03" /> 
																<label for="switch03" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Enable tagging</span>
																<p>Enable my friends to tag me on their posts</p>
																<input type="checkbox" id="switch04" /> 
																<label for="switch04" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Enable sound Notification</span>
																<p>You'll hear notification sound when someone sends you a private message</p>
																<input type="checkbox" id="switch05" checked=""/> 
																<label for="switch05" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															
															<div class="submit-btns">
																<button type="button" class="main-btn" data-ripple=""><span>Save</span></button>
																<button type="button" class="main-btn3" data-ripple=""><span>Cancel</span></button>
															</div>
														</form>
													</div>
													<div class="account-delete">
														<h5>Account Changes</h5>
														<div>
															<span>Hide Your Posts and profile </span>
															<button type="button" class=""><span>Deactivate account</span></button>
														</div>	
														<div>
															<span>Delete your account and data </span>
															<button type="button" class=""><span>close account</span></button>
														</div>
													</div>
												</div><!-- general setting -->
												<div class="tab-pane fade" id="edit-profile" >
													<div class="set-title">
														<h5>Chỉnh sửa thông tin</h5>
														<span>Thông tin của bạn sẽ được hiển thị tại đây</span>
													</div>
													<div class="setting-meta">
														<div class="change-photo">
															<figure><img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; /* Tạo hình tròn cho ảnh đại diện */"></figure>
															<div class="edit-img">
															<form action="about.php" method="post" enctype="multipart/form-data">
																<label class="fileContainer">
																	<i class="fa fa-camera"></i> Chọn ảnh
																	<input type="file" name="profile_picture" accept="image/*" required>
																</label>
																<input type="submit" value="Upload Image" name="submit">
															</form>
															</div>
														</div>
													</div>
													<div class="stg-form-area">
														<form method="post" class="c-form">
															<div>
																<label>Display Name</label>
																<input type="text" placeholder="Jack Carter">
															</div>
															
															<div class="uzer-nam">
																<label>User Name</label>
																<span>http://localhost/forumNAPA</span><input type="text" placeholder="jackcarter4023">
															</div>
															<div>
																<label>Email Address</label>
																<input type="text" placeholder="abc@pitnikmail.com">
															</div>
															<div>
																<label>Gender</label>
																<div class="form-radio">
																  <div class="radio">
																	<label>
																	  <input type="radio" checked="checked" name="radio"><i class="check-box"></i>Male
																	</label>
																  </div>
																  <div class="radio">
																	<label>
																	  <input type="radio" name="radio"><i class="check-box"></i>Female
																	</label>
																  </div>
																	<div class="radio">
																	<label>
																	  <input type="radio" name="radio"><i class="check-box"></i>Custom
																	</label>
																  </div>
																</div>
															</div>
															<div>
																<label>about your profile</label>
																<textarea rows="3" placeholder="write someting about yourself"></textarea>
															</div>	
																	
															<div>
																<label>Location</label>
																<input type="text" placeholder="Ex.San Francisco, CA">
															</div>
															<div>
																<label>Country</label>
																<select>
																  <option value="country">Country</option>
																  <option value="AFG">Afghanistan</option>
																  <option value="ALA">Ƭand Islands</option>
																  <option value="ALB">Albania</option>
																  <option value="DZA">Algeria</option>
																  <option value="ASM">American Samoa</option>
																  <option value="AND">Andorra</option>
																  <option value="AGO">Angola</option>
																  <option value="AIA">Anguilla</option>
																  <option value="ATA">Antarctica</option>
																  <option value="ATG">Antigua and Barbuda</option>
																  <option value="ARG">Argentina</option>
																  <option value="ARM">Armenia</option>
																  <option value="ABW">Aruba</option>
																  <option value="AUS">Australia</option>
																  <option value="AUT">Austria</option>
																  <option value="AZE">Azerbaijan</option>
																  <option value="BHS">Bahamas</option>
																  <option value="BHR">Bahrain</option>
																  <option value="BGD">Bangladesh</option>
																  <option value="BRB">Barbados</option>
																  <option value="BLR">Belarus</option>
																  <option value="BEL">Belgium</option>
																  <option value="BLZ">Belize</option>
																  <option value="BEN">Benin</option>
																  <option value="BMU">Bermuda</option>
																  <option value="BTN">Bhutan</option>
																  <option value="BOL">Bolivia, Plurinational State of</option>
																  <option value="BES">Bonaire, Sint Eustatius and Saba</option>
																  <option value="BIH">Bosnia and Herzegovina</option>
																  <option value="BWA">Botswana</option>
																  <option value="BVT">Bouvet Island</option>
																  <option value="BRA">Brazil</option>
																  <option value="IOT">British Indian Ocean Territory</option>
																  <option value="BRN">Brunei Darussalam</option>
																  <option value="BGR">Bulgaria</option>
																  <option value="BFA">Burkina Faso</option>
																  <option value="BDI">Burundi</option>
																  <option value="KHM">Cambodia</option>
																  <option value="CMR">Cameroon</option>
																  <option value="CAN">Canada</option>
																  <option value="CPV">Cape Verde</option>
																  <option value="CYM">Cayman Islands</option>
																  <option value="CAF">Central African Republic</option>
																  <option value="TCD">Chad</option>
																  <option value="CHL">Chile</option>
																  <option value="CHN">China</option>
																  <option value="CXR">Christmas Island</option>
																  <option value="CCK">Cocos (Keeling) Islands</option>
																  <option value="COL">Colombia</option>
																  <option value="COM">Comoros</option>
																  <option value="COG">Congo</option>
																  <option value="COD">Congo, the Democratic Republic of the</option>
																  <option value="COK">Cook Islands</option>
																  <option value="CRI">Costa Rica</option>
																  <option value="CIV">C𴥠d'Ivoire</option>
																  <option value="HRV">Croatia</option>
																  <option value="CUB">Cuba</option>
																  <option value="CUW">Cura袯</option>
																  <option value="CYP">Cyprus</option>
																  <option value="CZE">Czech Republic</option>
																  <option value="DNK">Denmark</option>
																  <option value="DJI">Djibouti</option>
																  <option value="DMA">Dominica</option>
																  <option value="DOM">Dominican Republic</option>
																  <option value="ECU">Ecuador</option>
																  <option value="EGY">Egypt</option>
																  <option value="SLV">El Salvador</option>
																  <option value="GNQ">Equatorial Guinea</option>
																  <option value="ERI">Eritrea</option>
																  <option value="EST">Estonia</option>
																  <option value="ETH">Ethiopia</option>
																  <option value="FLK">Falkland Islands (Malvinas)</option>
																  <option value="FRO">Faroe Islands</option>
																  <option value="FJI">Fiji</option>
																  <option value="FIN">Finland</option>
																  <option value="FRA">France</option>
																  <option value="GUF">French Guiana</option>
																  <option value="PYF">French Polynesia</option>
																  <option value="ATF">French Southern Territories</option>
																  <option value="GAB">Gabon</option>
																  <option value="GMB">Gambia</option>
																  <option value="GEO">Georgia</option>
																  <option value="DEU">Germany</option>
																  <option value="GHA">Ghana</option>
																  <option value="GIB">Gibraltar</option>
																  <option value="GRC">Greece</option>
																  <option value="GRL">Greenland</option>
																  <option value="GRD">Grenada</option>
																  <option value="GLP">Guadeloupe</option>
																  <option value="GUM">Guam</option>
																  <option value="GTM">Guatemala</option>
																  <option value="GGY">Guernsey</option>
																  <option value="GIN">Guinea</option>
																  <option value="GNB">Guinea-Bissau</option>
																  <option value="GUY">Guyana</option>
																  <option value="HTI">Haiti</option>
																  <option value="HMD">Heard Island and McDonald Islands</option>
																  <option value="VAT">Holy See (Vatican City State)</option>
																  <option value="HND">Honduras</option>
																  <option value="HKG">Hong Kong</option>
																  <option value="HUN">Hungary</option>
																  <option value="ISL">Iceland</option>
																  <option value="IND">India</option>
																  <option value="IDN">Indonesia</option>
																  <option value="IRN">Iran, Islamic Republic of</option>
																  <option value="IRQ">Iraq</option>
																  <option value="IRL">Ireland</option>
																  <option value="IMN">Isle of Man</option>
																  <option value="ISR">Israel</option>
																  <option value="ITA">Italy</option>
																  <option value="JAM">Jamaica</option>
																  <option value="JPN">Japan</option>
																  <option value="JEY">Jersey</option>
																  <option value="JOR">Jordan</option>
																  <option value="KAZ">Kazakhstan</option>
																  <option value="KEN">Kenya</option>
																  <option value="KIR">Kiribati</option>
																  <option value="PRK">Korea, Democratic People's Republic of</option>
																  <option value="KOR">Korea, Republic of</option>
																  <option value="KWT">Kuwait</option>
																  <option value="KGZ">Kyrgyzstan</option>
																  <option value="LAO">Lao People's Democratic Republic</option>
																  <option value="LVA">Latvia</option>
																  <option value="LBN">Lebanon</option>
																  <option value="LSO">Lesotho</option>
																  <option value="LBR">Liberia</option>
																  <option value="LBY">Libya</option>
																  <option value="LIE">Liechtenstein</option>
																  <option value="LTU">Lithuania</option>
																  <option value="LUX">Luxembourg</option>
																  <option value="MAC">Macao</option>
																  <option value="MKD">Macedonia, the former Yugoslav Republic of</option>
																  <option value="MDG">Madagascar</option>
																  <option value="MWI">Malawi</option>
																  <option value="MYS">Malaysia</option>
																  <option value="MDV">Maldives</option>
																  <option value="MLI">Mali</option>
																  <option value="MLT">Malta</option>
																  <option value="MHL">Marshall Islands</option>
																  <option value="MTQ">Martinique</option>
																  <option value="MRT">Mauritania</option>
																  <option value="MUS">Mauritius</option>
																  <option value="MYT">Mayotte</option>
																  <option value="MEX">Mexico</option>
																  <option value="FSM">Micronesia, Federated States of</option>
																  <option value="MDA">Moldova, Republic of</option>
																  <option value="MCO">Monaco</option>
																  <option value="MNG">Mongolia</option>
																  <option value="MNE">Montenegro</option>
																  <option value="MSR">Montserrat</option>
																  <option value="MAR">Morocco</option>
																  <option value="MOZ">Mozambique</option>
																  <option value="MMR">Myanmar</option>
																  <option value="NAM">Namibia</option>
																  <option value="NRU">Nauru</option>
																  <option value="NPL">Nepal</option>
																  <option value="NLD">Netherlands</option>
																  <option value="NCL">New Caledonia</option>
																  <option value="NZL">New Zealand</option>
																  <option value="NIC">Nicaragua</option>
																  <option value="NER">Niger</option>
																  <option value="NGA">Nigeria</option>
																  <option value="NIU">Niue</option>
																  <option value="NFK">Norfolk Island</option>
																  <option value="MNP">Northern Mariana Islands</option>
																  <option value="NOR">Norway</option>
																  <option value="OMN">Oman</option>
																  <option value="PAK">Pakistan</option>
																  <option value="PLW">Palau</option>
																  <option value="PSE">Palestinian Territory, Occupied</option>
																  <option value="PAN">Panama</option>
																  <option value="PNG">Papua New Guinea</option>
																  <option value="PRY">Paraguay</option>
																  <option value="PER">Peru</option>
																  <option value="PHL">Philippines</option>
																  <option value="PCN">Pitcairn</option>
																  <option value="POL">Poland</option>
																  <option value="PRT">Portugal</option>
																  <option value="PRI">Puerto Rico</option>
																  <option value="QAT">Qatar</option>
																  <option value="REU">R궮ion</option>
																  <option value="ROU">Romania</option>
																  <option value="RUS">Russian Federation</option>
																  <option value="RWA">Rwanda</option>
																  <option value="BLM">Saint Barthꭥmy</option>
																  <option value="SHN">Saint Helena, Ascension and Tristan da Cunha</option>
																  <option value="KNA">Saint Kitts and Nevis</option>
																  <option value="LCA">Saint Lucia</option>
																  <option value="MAF">Saint Martin (French part)</option>
																  <option value="SPM">Saint Pierre and Miquelon</option>
																  <option value="VCT">Saint Vincent and the Grenadines</option>
																  <option value="WSM">Samoa</option>
																  <option value="SMR">San Marino</option>
																  <option value="STP">Sao Tome and Principe</option>
																  <option value="SAU">Saudi Arabia</option>
																  <option value="SEN">Senegal</option>
																  <option value="SRB">Serbia</option>
																  <option value="SYC">Seychelles</option>
																  <option value="SLE">Sierra Leone</option>
																  <option value="SGP">Singapore</option>
																  <option value="SXM">Sint Maarten (Dutch part)</option>
																  <option value="SVK">Slovakia</option>
																  <option value="SVN">Slovenia</option>
																  <option value="SLB">Solomon Islands</option>
																  <option value="SOM">Somalia</option>
																  <option value="ZAF">South Africa</option>
																  <option value="SGS">South Georgia and the South Sandwich Islands</option>
																  <option value="SSD">South Sudan</option>
																  <option value="ESP">Spain</option>
																  <option value="LKA">Sri Lanka</option>
																  <option value="SDN">Sudan</option>
																  <option value="SUR">Suriname</option>
																  <option value="SJM">Svalbard and Jan Mayen</option>
																  <option value="SWZ">Swaziland</option>
																  <option value="SWE">Sweden</option>
																  <option value="CHE">Switzerland</option>
																  <option value="SYR">Syrian Arab Republic</option>
																  <option value="TWN">Taiwan, Province of China</option>
																  <option value="TJK">Tajikistan</option>
																  <option value="TZA">Tanzania, United Republic of</option>
																  <option value="THA">Thailand</option>
																  <option value="TLS">Timor-Leste</option>
																  <option value="TGO">Togo</option>
																  <option value="TKL">Tokelau</option>
																  <option value="TON">Tonga</option>
																  <option value="TTO">Trinidad and Tobago</option>
																  <option value="TUN">Tunisia</option>
																  <option value="TUR">Turkey</option>
																  <option value="TKM">Turkmenistan</option>
																  <option value="TCA">Turks and Caicos Islands</option>
																  <option value="TUV">Tuvalu</option>
																  <option value="UGA">Uganda</option>
																  <option value="UKR">Ukraine</option>
																  <option value="ARE">United Arab Emirates</option>
																  <option value="GBR">United Kingdom</option>
																  <option value="USA" selected>United States</option>
																  <option value="UMI">United States Minor Outlying Islands</option>
																  <option value="URY">Uruguay</option>
																  <option value="UZB">Uzbekistan</option>
																  <option value="VUT">Vanuatu</option>
																  <option value="VEN">Venezuela, Bolivarian Republic of</option>
																  <option value="VNM">Viet Nam</option>
																  <option value="VGB">Virgin Islands, British</option>
																  <option value="VIR">Virgin Islands, U.S.</option>
																  <option value="WLF">Wallis and Futuna</option>
																  <option value="ESH">Western Sahara</option>
																  <option value="YEM">Yemen</option>
																  <option value="ZMB">Zambia</option>
																  <option value="ZWE">Zimbabwe</option>
															  </select>
															</div>
															<div>
																<button type="submit" data-ripple="">Cancel</button>
																<button type="submit" data-ripple="">Save</button>
															</div>
														</form>
													</div>
												</div><!-- edit profile -->
												<div class="tab-pane fade" id="notifi" role="tabpanel">
													<div class="set-title">
														<h5>Notification Setting</h5>
														<span>Select push and email notifications you'd like to receive.</span>
													</div>
													<div class="notifi-seting">
														<div class="form-radio">
														  <div class="radio">
															<label>
															  <input type="radio" checked="checked" name="radio"><i class="check-box"></i>
																Send Me emails about my activity except emails i have unsubscribe from
															</label>
														  </div>
														  <div class="radio">
															<label>
															  <input type="radio" name="radio"><i class="check-box"></i>
																Only send me required services announcements.
															</label>
														  </div>
														</div>
														<div class="set-title">
															<h6>i'd like to receive emails and updates from Pitnik about</h6>
														</div>	
														<div class="checkbox">
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Always General announcement, updates, posts, and videos. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Personalise tips for my page. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Announcements and recommendations. 
														  </label>
															<p><a href="#" title="">learn more</a> about emails from pitnik</p>
														</div>
														<div class="set-title">
															<h6>Other Notifications</h6>
														</div>	
														<div class="checkbox">
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Recommended videos. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  activity on my page or channel. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Activity on my comments. 
														  </label>
															<label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Reply to comments. 
														  </label>
															<label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Mentions. 
														  </label>
															
														</div>
														<div class="set-title">
															<h6>Language Preference</h6>
															<span>Select your email language</span>
														</div>
														<select class="select">
															<option value="">Eglish US</option>
															<option value="">Eglish UK</option>
															<option value="">Russia</option>
														</select>
														<p>
															you will always get notifications you have turned on for individual <a href="#" title="">Manage All Subscriptions</a>
														</p>
													</div>
												</div><!-- notification -->
												<div class="tab-pane fade" id="messages" role="tabpanel">
													<div class="set-title">
														<h5>Messages Setting</h5>
														<span>Set your login preference, help us personalize your experience and make big account change here.</span>
														<div class="mesg-seting">
														
														<div class="set-title">
															<h6>i'd like to receive emails and updates from Pitnik about</h6>
														</div>	
														<div class="checkbox">
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Always General announcement, updates, posts, and videos. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Personalise tips for my page. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Announcements and recommendations. 
														  </label>
															<p><a href="#" title="">learn more</a> about emails from pitnik</p>
														</div>
														<div class="set-title">
															<h6>Other Messages</h6>
														</div>	
														<div class="checkbox">
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  From Recommended videos. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Messages from activity on my page or channel. 
														  </label>
														  <label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Message me the replyer Activity on my comments. 
														  </label>
															<label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Reply to comments. 
														  </label>
															<label>
															<input type="checkbox" checked="checked"><i class="check-box"></i>
															  Mentions. 
														  </label>
															
														</div>
														<div class="set-title">
															<h6>Language Preference</h6>
															<span>Select your Messages language</span>
														</div>
														<select class="select">
															<option value="">Eglish US</option>
															<option value="">Eglish UK</option>
															<option value="">Russia</option>
														</select>
														<p>
															you will always get notifications you have turned on for individual <a href="#" title="">Manage All Subscriptions</a>
														</p>
													</div>
													</div>
												</div><!-- messages -->
												<div class="tab-pane fade" id="weather" role="tabpanel">
													<div class="set-title">
														<h5>Weather Widget Setting</h5>
														<span>Set your weather widget or page setting.</span>
														<div class="mesg-seting">
															<div class="set-title">
																<h6>Country & Timezone</h6>
																<span>Select your Country Time Zone</span>
															</div>
															<select class="select">
																<option value="">US (UTC-8)</option>
																<option value="">Ontario(UTC-7)</option>
																<option value="">Nova Scotia(UTC-5)</option>
															</select>
															<div class="set-title">
																<h6>Temperature Unit</h6>
															</div>
															<select class="select">
																<option value="">F° (Farenheit)</option>
																<option value="">C° (Celsius)</option>
															</select>
															<div class="set-title">
																<h6>Show Extended forecast</h6>
															</div>
															<div class="checkbox">
															  <label>
																<input type="checkbox" checked="checked"><i class="check-box"></i>
																  Show Extended Forecast on Widget. 
															  </label>
																<p><a href="#" title="">learn more</a></p>
															</div>
															<div class="set-title">
																<h6>Forecast Days</h6>
															</div>
															<select class="select">
																<option value="">Next Day</option>
																<option value="">Next week</option>
																<option value="">Next Month</option>
																<option value="">Next Year</option>
															</select>
															<p>
																you will always get Daily notifications you have turned on for individual.
															</p>
															<div>
															<form>
																<button class="main-btn" data-ripple="" type="submit">Save</button>
																<button class="main-btn3" data-ripple="" type="submit">Cancel</button>
																
															</form>	
															</div>
														</div>
													</div>
												</div><!-- weather widget setting -->
												<div class="tab-pane fade" id="page-manage" role="tabpanel">
													<div class="set-title">
														<h5>Page & sidebar</h5>
														<span>Deceide whether your profile will be hidden from search engine and what kind of data you want to use to imporve the recommendation and ads you see <a href="#" title="">Learn more</a></span>
													</div>
													<p class="p-info"><a href="manage-page.html">Click here</a> to go widget and page setting area</p>
												</div><!-- privacy -->
												<div class="tab-pane fade" id="privacy" role="tabpanel">
													<div class="set-title">
														<h5>Privacy & data</h5>
														<span>Deceide whether your profile will be hidden from search engine and what kind of data you want to use to imporve the recommendation and ads you see <a href="#" title="">Learn more</a></span>
													</div>
													<div class="onoff-options ">
														<form method="post">
															<div class="setting-row">
																<span>Search Privacy</span>
																<p>Hide your profile from search engine (Ex.google) <a href="#" title="">Learn more</a>
																</p>
																<input type="checkbox" id="switch0001" /> 
																<label for="switch0001" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="set-title">
																<h5>Personalization</h5>
															</div>	
															<div class="setting-row">
																<span>Search Privacy</span>
																<p>use sites you visit to improve which recommendation and ads you see. <a href="#" title="">Learn more</a>
																</p>
																<input type="checkbox" id="switch0002" /> 
																<label for="switch0002" data-on-label="ON" data-off-label="OFF"></label>
															</div>
															<div class="setting-row">
																<span>Search Privacy</span>
																<p>use information from our partners to improve which ads you see<a href="#" title="">Learn more</a>
																</p>
																<input type="checkbox" id="switch0003" /> 
																<label for="switch0003" data-on-label="ON" data-off-label="OFF"></label>
															</div>
														</form>
													</div>
												</div><!-- privacy -->
												<div class="tab-pane fade" id="security" role="tabpanel">
													<div class="set-title">
														<h5>Security Setting</h5>
														<span>trun on two factor authentication and check your list of connected device to keep your account posts safe <a href="#" title="">Learn More</a>.</span>
													</div>
													<div class="seting-box">
														<p>to turn on two-factor authentication, you must <a href="#" title=""> confirm Your Email </a> and <a href="#" title="">Set Password</a></p>
														<div class="set-title">
															<h5>Connected Devicese</h5>
														</div>
														<p>This is a list of devices that have logged into your account, Revok any session that you do not recognize. <a href="#" title="">Hide Sessions</a></p>
														<span>Last Accessed</span>
														<p>August 30, 2020 12:25AM</p>
														<span>Location</span>
														<p>Berlin, Germany (based on IP = 103.233.24.5)</p>
														<span>Device Type</span>
														<p>Chrome on windows 10</p>
													</div>
												</div><!-- security -->
												<div class="tab-pane fade" id="apps" role="tabpanel">
													<div class="set-title">
														<h5>Apps</h5>
														<span>Keep track of everywhere you have login with your pintik profile and remove access from apps you are no longer using with pitnik <a href="#" title="">Learn more</a></span>
													</div>
													<p class="p-info">You have not approved any app</p>
												</div><!-- apps -->
											</div>
										</div>
									</div>
								</div>	
							</div><!-- centerl meta -->
							<div class="col-lg-3">
								<aside class="sidebar static">
									<div class="widget">
											<h4 class="widget-title">Your page</h4>	
											<div class="your-page">
												<figure>
													<a title="" href="#"><img alt="" src="images/resources/friend-avatar9.jpg"></a>
												</figure>
												<div class="page-meta">
													<a class="underline" title="" href="#">My page</a>
													<span><i class="ti-comment"></i>Messages <em class="bg-blue">9</em></span>
													<span><i class="ti-bell"></i>Notifications <em class="bg-purple">2</em></span>
												</div>
												<div class="page-likes">
													<ul class="nav nav-tabs likes-btn">
														<li class="nav-item"><a data-toggle="tab" href="#link1" class="active">likes</a></li>
														 <li class="nav-item"><a data-toggle="tab" href="#link2" class="">views</a></li>
													</ul>
													<!-- Tab panes -->
													<div class="tab-content">
													  <div id="link1" class="tab-pane active fade show">
														<span><i class="ti-heart"></i>884</span>
														  <a title="weekly-likes" href="#">35 new likes this week</a>
														  <div class="users-thumb-list">
														  	<a data-toggle="tooltip" title="" href="#" data-original-title="Anderw">
																<img alt="" src="images/resources/userlist-1.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="frank">
																<img alt="" src="images/resources/userlist-2.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Sara">
																<img alt="" src="images/resources/userlist-3.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Amy">
																<img alt="" src="images/resources/userlist-4.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Ema">
																<img alt="" src="images/resources/userlist-5.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Sophie">
																<img alt="" src="images/resources/userlist-6.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Maria">
																<img alt="" src="images/resources/userlist-7.jpg">  
															</a>  
														  </div>
													  </div>
													  <div id="link2" class="tab-pane fade">
														  <span><i class="ti-eye"></i>445</span>
														  <a title="weekly-likes" href="#">440 new views this week</a>
														  <div class="users-thumb-list">
														  	<a data-toggle="tooltip" title="" href="#" data-original-title="Anderw">
																<img alt="" src="images/resources/userlist-1.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="frank">
																<img alt="" src="images/resources/userlist-2.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Sara">
																<img alt="" src="images/resources/userlist-3.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Amy">
																<img alt="" src="images/resources/userlist-4.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Ema">
																<img alt="" src="images/resources/userlist-5.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Sophie">
																<img alt="" src="images/resources/userlist-6.jpg">  
															</a>
															<a data-toggle="tooltip" title="" href="#" data-original-title="Maria">
																<img alt="" src="images/resources/userlist-7.jpg">  
															</a>  
														  </div>
													  </div>
													</div>
												</div>
											</div>
										</div>
								</aside>
							</div><!-- sidebar -->
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</section>


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
	
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>

</body>	


</html>