<?php
session_start(); // Bắt đầu phiên

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi người dùng gửi form đăng nhập
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra nếu các trường không được để trống
    if (empty($username) || empty($password)) {
        $error_message = "Vui lòng điền đầy đủ thông tin.";
    } else {
        // Chuẩn bị truy vấn để tránh SQL Injection
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Lấy thông tin người dùng
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu (so sánh với mật khẩu đã hash)
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công: Lưu thông tin người dùng vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Chuyển hướng tới trang chủ hoặc trang mà người dùng yêu cầu trước đó
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        } else {
            $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    }
}

// Lấy số lượng người dùng đã đăng ký
$registered_query = $conn->query("SELECT COUNT(id) AS total_registered FROM users");
$registered_data = $registered_query->fetch_assoc();
$registered_people = $registered_data['total_registered'];

// Lấy số lượng bài viết đã xuất bản
$posts_query = $conn->query("SELECT COUNT(id) AS total_posts FROM threads"); // Hoặc "posts" nếu bạn có bảng riêng cho bài viết
$posts_data = $posts_query->fetch_assoc();
$posts_published = $posts_data['total_posts'];

// Lấy số lượng người dùng hiện đang online (giả sử bạn có cột 'last_activity')
$time_limit = date('Y-m-d H:i:s', strtotime('-15 minutes')); // Ví dụ: Người dùng hoạt động trong 15 phút gần đây
$online_users_query = $conn->query("SELECT COUNT(id) AS total_online FROM active_users WHERE last_activity > '$time_limit'");
$online_users_data = $online_users_query->fetch_assoc();
$online_users = $online_users_data['total_online'];
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
	<link rel="stylesheet" href="css/weather-icon.css">
	<link rel="stylesheet" href="css/weather-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '2831919040299540',  
            cookie     : true,
            xfbml      : true,
            version    : 'v20.0'
        });
        
        FB.AppEvents.logPageView();   
    };
    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "https://connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
</head>

<body>
	<div class="www-layout">
        <section>
        	<div class="gap no-gap signin whitish medium-opacity">
                <div class="bg-image" style="background-image:url(images/resources/theme-bg.jpg)"></div>
                <div class="container">
                	<div class="row">
                        <div class="col-lg-8">
                            <div class="big-ad">
                                <figure><img src="images/LogaNapacut.png" alt=""></figure>
                                <h1>Welcome to the NAPA Social Network</h1>
                                <p>
                                NAPA là một mạng xã hội có thể được sử dụng để kết nối mọi người. Bạn có thể sử dụng mẫu này cho nhiều hoạt động xã hội khác nhau như tìm việc, hẹn hò, đăng bài, viết blog và nhiều hoạt động khác nữa. Hãy tham gia ngay và kết bạn thú vị khắp thế giới!
                                </p>
                                
                                <div class="fun-fact">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-4">
                                            <div class="fun-box">
                                                <i class="ti-check-box"></i>
                                                <h6>Tổng số người đã đăng kí</h6>
                                                <span><?php echo number_format($registered_people); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-4">
                                            <div class="fun-box">
                                                <i class="ti-layout-media-overlay-alt-2"></i>
                                                <h6>Tổng số bài đã đăng</h6>    
                                                <span><?php echo number_format($posts_published); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-4">
                                            <div class="fun-box">
                                                <i class="ti-user"></i>
                                                <h6>Số thành viên đang trực tuyến</h6>
                                                <span><?php echo number_format($online_users); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="barcode">
                                    <figure><img src="images/resources/Barcode.jpg" alt=""></figure>
                                    <div class="app-download">
                                        <span>Download Mobile App and Scan QR Code to login</span>
                                        <ul class="colla-apps">
                                            <li><a title="" href="https://play.google.com/store?hl=en"><img src="images/android.png" alt="">android</a></li>
                                            <li><a title="" href="https://www.apple.com/lae/ios/app-store/"><img src="images/apple.png" alt="">iPhone</a></li>
                                            <li><a title="" href="https://www.microsoft.com/store/apps"><img src="images/windows.png" alt="">Windows</a></li>
                                        </ul>
                                    </div>
                                </div> -->  
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="we-login-register">
                                <div class="form-title">
                                    <i class="fa fa-key"></i>Đăng nhập
                                    <span>Đăng nhập ngay để kết nối với bạn bè trên toàn thế giới</span>
                                </div>
                                <?php if (!empty($error_message)): ?>
                                <p class="error-message"><?php echo $error_message; ?></p>
                                <?php endif; ?>
                                <form class="we-form" method="post" action="login.php">
                                <input type="text" name="username" placeholder="Tài khoản"> <!-- Added name attribute -->
                                <input type="password" name="password" placeholder="Mật khẩu"> <!-- Added name attribute -->
                                <input type="checkbox"><label>Nhớ mật khẩu</label>
                                <button type="submit" data-ripple="">Đăng nhập</button>
                                <a class="forgot underline" href="forgot_password.php" title="">Quên mật khẩu</a>
                                </form>
                                <p>Hoặc đăng nhập bằng:</p>
                                <a class="with-smedia facebook" onclick="loginWithFacebook()" title="" data-ripple=""><i class="fa fa-facebook"></i></a>
								<a class="with-smedia google" href="google_login.php" title="" data-ripple=""><i class="fa fa-google-plus"></i></a>
                                <span>Chưa có tài khoản <a class="we-account underline" href="register.php" title="">Đăng kí ngay</a></span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
         
    </div>
    
<script>
function loginWithFacebook() {
    FB.login(function(response) {
        if (response.authResponse) {
            FB.api('/me', {fields: 'name,email'}, function(response) {
                // Gửi thông tin người dùng về server
                var username = response.name;
                var email = response.email;        
                // AJAX request để gửi dữ liệu về PHP xử lý
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "facebook_login.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === 'success') {
                            window.location.href = 'index.php'; // Chuyển hướng sau khi đăng nhập thành công
                        } else {
                            alert("Đăng nhập thất bại. Vui lòng thử lại.");
                        }
                    }
                };
                xhr.send("username=" + username + "&email=" + email);
            });
        } else {
            console.log('Người dùng hủy đăng nhập.');
        }
    }, {scope: 'public_profile,email'});
}
</script>
    	<script src="js/main.min.js"></script>
		<script src="js/script.js"></script>
</body>	

</html>