<?php
session_start(); // Bắt đầu phiên

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = '';
$error_message = '';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Tải PHPMailer


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Kiểm tra nếu email không được để trống
    if (empty($email)) {
        $error_message = "Vui lòng nhập email.";
    } else {
        // Chuẩn bị truy vấn để kiểm tra email
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Lấy thông tin người dùng
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            $username = $user['username'];

            // Tạo mã token
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800; // 30 phút

            // Lưu token vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expires=?");
            $stmt->bind_param("issss", $user_id, $token, $expires, $token, $expires);
            $stmt->execute();

            // Gửi email chứa liên kết đặt lại mật khẩu bằng PHPMailer
            $reset_link = "http://localhost/forumNAPA/reset_password.php?token=$token";

            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = 2;  // Bật chế độ debug
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lechinhdai98@gmail.com';
                $mail->Password = 'fumj xgwj bder izfo';  // Dùng mật khẩu ứng dụng nếu đã bật 2FA
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
            
                // Người gửi và người nhận
                $mail->setFrom('lechinhdai98@gmail.com', 'Forum');
                $mail->addAddress($email);
            
                // Nội dung email
                $mail->isHTML(true);
                $mail->Subject = 'Đặt lại mật khẩu của bạn';
                $mail->Body = "Nhấp vào liên kết để đặt lại mật khẩu của bạn: <a href=\"$reset_link\">$reset_link</a>";
            
                $mail->send();
                echo 'Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn.';
            } catch (Exception $e) {
                echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "Email không tồn tại.";
        }
    }
}
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];

//     // Kiểm tra nếu email không được để trống
//     if (empty($email)) {
//         $error_message = "Vui lòng nhập email.";
//     } else {
//         // Chuẩn bị truy vấn để kiểm tra email
//         $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
//         $stmt->bind_param("s", $email);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         if ($result->num_rows > 0) {
//             // Lấy thông tin người dùng
//             $user = $result->fetch_assoc();
//             $user_id = $user['id'];
//             $username = $user['username'];

//             // Tạo mã token
//             $token = bin2hex(random_bytes(50));
//             $expires = date("U") + 1800; // 30 phút

//             // Lưu token vào cơ sở dữ liệu
//             $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expires=?");
//             $stmt->bind_param("issss", $user_id, $token, $expires, $token, $expires);
//             $stmt->execute();

//             // Gửi email chứa liên kết đặt lại mật khẩu bằng hàm mail()
//             $reset_link = "http://localhost/forumPHP/reset_password.php?token=$token";
//             $subject = 'Đặt lại mật khẩu của bạn';
//             $message = "Nhấp vào liên kết để đặt lại mật khẩu của bạn: <a href=\"$reset_link\">$reset_link</a>";
//             $headers = "From: lechinhdai98@gmail.com\r\n";
//             $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

//             if (mail($email, $subject, $message, $headers)) {
//                 echo 'Một liên kết đặt lại mật khẩu đã được gửi đến email của bạn.';
//             } else {
//                 echo 'Có lỗi khi gửi email.';
//             }
//         } else {
//             $error_message = "Email không tồn tại.";
//         }
//     }
// }

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="style.css">
</head>
<style> 
/* CSS cho trang Quên Mật Khẩu */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f4f7f8;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: #fff;
    padding: 20px 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    max-width: 100%;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    text-align: left;
    margin-bottom: 10px;
    color: #555;
}

input[type="email"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    color: #333;
}

input[type="submit"] {
    padding: 12px;
    background-color: #28a745;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #218838;
}

p {
    margin-top: 20px;
    font-size: 14px;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.success-message {
    color: green;
    font-size: 14px;
    margin-bottom: 10px;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

</style>
<body>

<div class="login-container">
    <h2>Quên mật khẩu</h2>

    <?php if (!empty($message)): ?>
        <p class="success-message"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">
        <label for="email">Nhập email của bạn:</label>
        <input type="email" id="email" name="email" placeholder="Nhập email" required>

        <input type="submit" value="Gửi liên kết đặt lại mật khẩu">
    </form>

    <p><a href="login.php">Quay lại trang đăng nhập</a></p>
</div>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
