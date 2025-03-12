<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'ledai_forum');

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Chuẩn bị truy vấn để kiểm tra xem username hoặc email đã tồn tại hay chưa
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Nếu kết quả trả về có ít nhất một hàng, tức là username hoặc email đã tồn tại
    if ($result->num_rows > 0) {
        echo "Tên đăng nhập hoặc email đã tồn tại, vui lòng chọn tên đăng nhập hoặc email khác.";
    } else {
        // Chuẩn bị câu truy vấn để thêm người dùng mới vào bảng users
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);

        // Thực hiện truy vấn
        if ($stmt->execute()) {
            echo "Đăng ký thành công!";

            // Chuyển hướng về trang đăng nhập sau 2 giây
            header("Refresh: 2; url=login.php");
            exit();  // Dừng script sau khi chuyển hướng
        } else {
            echo "Đã có lỗi xảy ra, vui lòng thử lại.";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
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

</head>

<body>
    <div class="www-layout">
        <section>
            <div class="gap no-gap signin whitish medium-opacity register">
                <div class="bg-image" style="background-image:url(images/resources/theme-bg.jpg)"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="big-ad">
                                <figure><img src="images/logo2.png" alt=""></figure>
                                <h1>Welcome to the NAPA Social Network</h1>
                                <p>
                                    NAPA is a social network template that can be used to connect people. use this template for multipurpose social activities like job, dating, posting, bloging and much more. Now join & Make Cool Friends around the world !!!
                                </p>
                                <div class="barcode">
                                    <figure><img src="images/resources/Barcode.jpg" alt=""></figure>
                                    <div class="app-download">
                                        <span>Download Mobile App and Scan QR Code to login</span>
                                        <ul class="colla-apps">
                                            <li><a title="" href="https://play.google.com/store?hl=en"><img src="images/android.png" alt="">android</a></li>
                                            <li><a title="" href="https://www.apple.com/lae/ios/app-store/"><img src="images/apple.png" alt="">iPhone</a></li>
                                            <li><a title="" href="https://www.microsoft.com/store/apps"><img src="images/windows.png" alt="">Windows</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="we-login-register">
                                <div class="form-title">
                                    <i class="fa fa-key"></i>Sign Up
                                    <span>Sign Up now and meet the awesome friends around the world.</span>
                                </div>
                                <form class="we-form" method="post">
                                    <input type="text" name="username" placeholder="Username"> <!-- Added name attribute for username -->
                                    <input type="email" name="email" placeholder="Email"> <!-- Added name attribute for email -->
                                    <input type="password" name="password" placeholder="Password"> <!-- Added name attribute for password -->
                                    <input type="checkbox"><label>Send code to Mobile</label>
                                    <button type="submit" data-ripple="">Register</button>
                                    <a class="forgot underline" href="#" title="">forgot password?</a>
                                </form>

                                <a data-ripple="" title="" href="#" class="with-smedia facebook"><i class="fa fa-facebook"></i></a>
                                <a data-ripple="" title="" href="#" class="with-smedia twitter"><i class="fa fa-twitter"></i></a>
                                <a data-ripple="" title="" href="#" class="with-smedia instagram"><i class="fa fa-instagram"></i></a>
                                <a data-ripple="" title="" href="#" class="with-smedia google"><i class="fa fa-google-plus"></i></a>
                                <span>already have an account? <a class="we-account underline" href="#" title="">Sign in</a></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>

    <script src="js/main.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>