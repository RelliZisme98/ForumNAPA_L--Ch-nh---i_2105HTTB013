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

                                        <ul class="profile-controls">
                                            <li><a href="#" title="Add friend" data-toggle="tooltip"><i class="fa fa-user-plus"></i></a></li>
                                            <li><a href="#" title="Follow" data-toggle="tooltip"><i class="fa fa-star"></i></a></li>
                                            <li><a class="send-mesg" href="#" title="Send Message" data-toggle="tooltip"><i class="fa fa-comment"></i></a></li>
                                            <li>
                                                <div class="edit-seting" title="Edit Profile image"><i class="fa fa-sliders"></i>
                                                    <ul class="more-dropdown">
                                                        <li><a href="settingaccount.php" title="">Update Profile Photo</a></li>
                                                        <li><a href="settingaccount.php" title="">Update Header Photo</a></li>
                                                        <li><a href="settingaccount.php" title="">Account Settings</a></li>
                                                        <li><a href="support-and-help.html" title="">Find Support</a></li>
                                                        <li><a class="bad-report" href="#" title="">Report Profile</a></li>
                                                        <li><a href="#" title="">Block Profile</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>

                                    </figure>

                                    <div class="profile-section">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3">
                                                <div class="profile-author">
                                                    <div class="profile-author-thumb">
                                                        <img alt="author" src="<?php echo htmlspecialchars($profile_picture); ?>">
                                                        <div class="edit-dp">
                                                            <form action="about.php" method="post" enctype="multipart/form-data">
                                                                <label class="fileContainer">
                                                                    <i class="fa fa-camera"></i> Chọn ảnh
                                                                    <input type="file" name="profile_picture" accept="image/*" required>
                                                                </label>
                                                                <input type="submit" value="Upload Image" name="submit">
                                                            </form>
                                                        </div>
                                                    </div>




                                                    <div class="author-content">
                                                        <a class="h4 author-name" href="about.php"><?php echo htmlspecialchars($user['username']); ?></a>
                                                        <div class="country"><?php echo htmlspecialchars($user_profile_data['country']); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-10 col-md-9">
                                                <ul class="profile-menu">
                                                    <li>
                                                        <a class="" href="timeline.php">Timeline</a>
                                                    </li>
                                                    <li>
                                                        <a class="active" href="about.php">About</a>
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

                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <ol class="folw-detail">
                                                    <li><span>Posts</span><ins><?php echo htmlspecialchars($user_profile_data['posts']); ?></ins></li>
                                                    <li><span>Followers</span><ins><?php echo htmlspecialchars($user_profile_data['followers']); ?></ins></li>
                                                    <li><span>Following</span><ins><?php echo htmlspecialchars($user_profile_data['following']); ?></ins></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- user profile banner  -->
                                <div class="col-lg-4 col-md-4">
                                    <aside class="sidebar">
                                        <div class="central-meta stick-widget">
                                            <span class="create-post">Thông tin cá nhân</span>
                                            <div class="personal-head">
                                                <span class="f-title"><i class="fa fa-user"></i> Giới thiệu</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['about']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-birthday-cake"></i> Sinh nhật</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['birthday']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-phone"></i> Số điện thoại:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['phone_number']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-medkit"></i> Nhóm máu:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['blood_group']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-male"></i> Giới tính:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['gender']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-globe"></i> Thành phố:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['country']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-briefcase"></i> Học tập:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['occupation']); ?>
                                                </p>
                                                <span class="f-title"><i class="fa fa-handshake-o"></i> Ngày tham gia:</span>
                                                <p>
                                                    <?php echo htmlspecialchars($user_profile_data['joined']); ?>
                                                </p>

                                                <span class="f-title"><i class="fa fa-envelope"></i> Email & Website:</span>
                                                <p>
                                                    <a href="<?php echo htmlspecialchars($user_profile_data['website']); ?>" title=""><?php echo htmlspecialchars($user_profile_data['website']); ?></a>
                                                    <a href="mailto:<?php echo htmlspecialchars($user_profile_data['email']); ?>" class="__cf_email__"><?php echo htmlspecialchars($user_profile_data['email']); ?></a>
                                                </p>

                                            </div>
                                        </div>
                                    </aside>
                                </div>
                                <div class="col-lg-8 col-md-8">
                                    <div class="central-meta">
                                        <span class="create-post">Thông tin chung<a href="#" title="">See All</a></span>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?php if (!empty($main_interests)): ?>
                                                    <div class="gen-metabox">
                                                        <span><i class="fa fa-puzzle-piece"></i> Sở thích</span>
                                                        <ul>
                                                            <!-- Loop through main hobbies and display -->
                                                            <?php foreach ($main_interests as $main): ?>
                                                                <li><?php echo htmlspecialchars($main); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php else: ?>
                                                    <p>Người dùng không có sở thích chính nào.</p>
                                                <?php endif; ?>

                                                <?php if (!empty($other_interests)): ?>
                                                    <div class="gen-metabox">
                                                        <span><i class="fa fa-plus"></i> Sở thích khác</span>
                                                        <ul>
                                                            <!-- Loop through other hobbies and display -->
                                                            <?php foreach ($other_interests as $other): ?>
                                                                <li><?php echo htmlspecialchars($other); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="gen-metabox">
                                                    <span><i class="fa fa-mortar-board"></i> Học tập</span>
                                                    <?php if (!empty($education)): ?>
                                                        <p>
                                                            <?php echo htmlspecialchars($education['degree']); ?>, Khóa <?php echo htmlspecialchars($education['graduation_year']); ?>
                                                            tại <a href="#" title=""><?php echo htmlspecialchars($education['institution']); ?></a>
                                                        </p>
                                                    <?php else: ?>
                                                        <p>No education data available.</p>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="gen-metabox">
                                                    <span><i class="fa fa-certificate"></i> Kinh nghiệm làm việc</span>
                                                    <?php if (!empty($work_experience)): ?>
                                                        <p>
                                                            Đang làm việc tại "<?php echo htmlspecialchars($work_experience['company_name']); ?>" được
                                                            <?php echo htmlspecialchars($work_experience['years_of_experience']); ?> năm với vị trí
                                                            <a href="#" title=""><?php echo htmlspecialchars($work_experience['position']); ?></a>
                                                        </p>
                                                    <?php else: ?>
                                                        <p>No work experience data available.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="gen-metabox no-margin">
                                                    <span><i class="fa fa-sitemap"></i> Social Networks</span>
                                                    <ul class="sociaz-media">
                                                        <?php foreach ($social_networks as $network): ?>
                                                            <?php
                                                            // Map platform names to their corresponding Font Awesome icons
                                                            $icon_class = '';
                                                            switch (strtolower($network['platform_name'])) {
                                                                case 'facebook':
                                                                    $icon_class = 'fa-facebook';
                                                                    break;
                                                                case 'twitter':
                                                                    $icon_class = 'fa-twitter';
                                                                    break;
                                                                case 'google-plus':
                                                                    $icon_class = 'fa-google-plus';
                                                                    break;
                                                                case 'vk':
                                                                    $icon_class = 'fa-vk';
                                                                    break;
                                                                case 'instagram':
                                                                    $icon_class = 'fa-instagram';
                                                                    break;
                                                                default:
                                                                    $icon_class = 'fa-globe'; // Fallback icon
                                                                    break;
                                                            }
                                                            ?>
                                                            <li><a class="<?php echo htmlspecialchars($network['platform_name']); ?>" href="<?php echo htmlspecialchars($network['profile_url']); ?>" title="<?php echo htmlspecialchars($network['platform_name']); ?>"><i class="fa <?php echo $icon_class; ?>"></i></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="gen-metabox no-margin">
                                                    <span><i class="fa fa-trophy"></i> Badges</span>
                                                    <ul class="badged">
                                                        <li><img src="images/badges/badge2.png" alt=""></li>
                                                        <li><img src="images/badges/badge19.png" alt=""></li>
                                                        <li><img src="images/badges/badge21.png" alt=""></li>
                                                        <li><img src="images/badges/badge3.png" alt=""></li>
                                                        <li><img src="images/badges/badge4.png" alt=""></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="central-meta">
                                        <span class="create-post">Favorite Movies & TV Shows (<?php echo count($favorite_movies); ?>)
                                            <a href="#" title="">See All</a>
                                        </span>
                                        <div class="row">
                                            <?php foreach ($favorite_movies as $movie): ?>
                                                <div class="col-lg-4 col-md-6 col-sm-6">
                                                    <div class="fav-play">
                                                        <figure>
                                                            <a href="<?php echo htmlspecialchars($movie['movie_link']); ?>" target="_blank">
                                                                <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['movie_name']); ?>">
                                                            </a>
                                                        </figure>
                                                        <span class="tv-play-title"><?php echo htmlspecialchars($movie['movie_name']); ?> (<?php echo htmlspecialchars($movie['year']); ?>)</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="central-meta">
                                        <span class="create-post">Friend's (320) <a href="timeline_friends2.php" title="">See All</a></span>
                                        <ul class="frndz-list">
                                            <li>
                                                <img src="images/resources/recent1.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Olivia</a>
                                                    <span>1 mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="images/resources/recent2.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Emma watson</a>
                                                    <span>2 mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="images/resources/recent3.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Isabella</a>
                                                    <span><a href="#" title="">Emmy</a> is mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="images/resources/recent4.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Amelia</a>
                                                    <span>5 mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="images/resources/recent5.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Sophia</a>
                                                    <span>1 mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="images/resources/recent6.jpg" alt="">
                                                <div class="sugtd-frnd-meta">
                                                    <a href="#" title="">Amelia</a>
                                                    <span>3 mutual friend</span>
                                                    <ul class="add-remove-frnd">
                                                        <li class="add-tofrndlist"><a class="send-mesg" href="#" title="Send Message"><i class="fa fa-commenting"></i></a></li>
                                                        <li class="remove-frnd"><a href="#" title="remove friend"><i class="fa fa-user-times"></i></a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div><!-- friends list -->
                                    <div class="central-meta">
                                        <span class="create-post">Photos (580) <a href="timeline_photos.php" title="">See All</a></span>
                                        <ul class="photos-list">
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-22.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo2.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>15</span></div>
                                                        <span>20 hours ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-33.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo3.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>20</span></div>
                                                        <span>20 days ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-44.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo4.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>155</span></div>
                                                        <span>Yesterday</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-55.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo5.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>201</span></div>
                                                        <span>3 weeks ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-66.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo6.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>81</span></div>
                                                        <span>2 months ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-77.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo7.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>98</span></div>
                                                        <span>1 day</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a class="strip" href="images/resources/photo-88.jpg" title="" data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                        <img src="images/resources/photo8.jpg" alt=""></a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>87</span></div>
                                                        <span>23 hours ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="central-meta">
                                        <span class="create-post">Videos (33) <a href="timeline_videos.php" title="">See All</a></span>
                                        <ul class="videos-list">
                                            <li>
                                                <div class="item-box">
                                                    <a href="https://www.youtube.com/watch?v=fF382gwEnG8&amp;t=1s" title="" data-strip-group="mygroup" class="strip" data-strip-options="width: 700,height: 450,youtube: { autoplay: 1 }"><img src="images/resources/vid-11.jpg" alt="">
                                                        <i>
                                                            <svg version="1.1" class="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px" width="50px"
                                                                viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                <path class="stroke-solid" fill="none" stroke="" d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
                                                        C97.3,23.7,75.7,2.3,49.9,2.5" />
                                                                <path class="icon" fill="" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z" />
                                                            </svg>
                                                        </i>
                                                    </a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>15</span></div>
                                                        <span>20 hours ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a href="https://www.youtube.com/watch?v=fF382gwEnG8&amp;t=1s" title="" data-strip-group="mygroup" class="strip" data-strip-options="width: 700,height: 450,youtube: { autoplay: 1 }"><img src="images/resources/vid-12.jpg" alt="">
                                                        <i>
                                                            <svg version="1.1" class="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px" width="50px"
                                                                viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                <path class="stroke-solid" fill="none" stroke="" d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
                                                            C97.3,23.7,75.7,2.3,49.9,2.5" />
                                                                <path class="icon" fill="" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z" />
                                                            </svg>
                                                        </i>
                                                    </a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>20</span></div>
                                                        <span>20 hours ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a href="https://www.youtube.com/watch?v=fF382gwEnG8&amp;t=1s" title="" data-strip-group="mygroup" class="strip" data-strip-options="width: 700,height: 450,youtube: { autoplay: 1 }"><img src="images/resources/vid-10.jpg" alt="">
                                                        <i>
                                                            <svg version="1.1" class="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px" width="50px"
                                                                viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                <path class="stroke-solid" fill="none" stroke="" d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
                                                            C97.3,23.7,75.7,2.3,49.9,2.5" />
                                                                <path class="icon" fill="" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z" />
                                                            </svg>
                                                        </i>
                                                    </a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>49</span></div>
                                                        <span>20 days ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a href="https://www.youtube.com/watch?v=fF382gwEnG8&amp;t=1s" title="" data-strip-group="mygroup" class="strip" data-strip-options="width: 700,height: 450,youtube: { autoplay: 1 }"><img src="images/resources/vid-9.jpg" alt="">
                                                        <i>
                                                            <svg version="1.1" class="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px" width="50px"
                                                                viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                <path class="stroke-solid" fill="none" stroke="" d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
                                                            C97.3,23.7,75.7,2.3,49.9,2.5" />
                                                                <path class="icon" fill="" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z" />
                                                            </svg>
                                                        </i>
                                                    </a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>156</span></div>
                                                        <span>Yesterday</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-box">
                                                    <a href="https://www.youtube.com/watch?v=fF382gwEnG8&amp;t=1s" title="" data-strip-group="mygroup" class="strip" data-strip-options="width: 700,height: 450,youtube: { autoplay: 1 }"><img src="images/resources/vid-6.jpg" alt="">
                                                        <i>
                                                            <svg version="1.1" class="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="50px" width="50px"
                                                                viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                <path class="stroke-solid" fill="none" stroke="" d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
                                                            C97.3,23.7,75.7,2.3,49.9,2.5" />
                                                                <path class="icon" fill="" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z" />
                                                            </svg>
                                                        </i>
                                                    </a>
                                                    <div class="over-photo">
                                                        <div class="likes heart" title="Like/Dislike">❤ <span>202</span></div>
                                                        <span>3 weeks ago</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- content -->



        <?php include 'component/footer.php'; ?>
    </div>
    <div class="side-panel">
        <h4 class="panel-title">General Setting</h4>
        <form method="post">
            <div class="setting-row">
                <span>use night mode</span>
                <input type="checkbox" id="nightmode1" />
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

    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>