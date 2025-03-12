<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Bắt đầu phiên

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = '';
$error_message = '';

// Lấy mã token từ URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Kiểm tra token
    $current_time = date("U"); // Lưu thời gian hiện tại vào biến
    $stmt = $conn->prepare("SELECT user_id, expires FROM password_resets WHERE token = ? AND expires > ?");
    $stmt->bind_param("si", $token, $current_time); // Sử dụng biến
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reset = $result->fetch_assoc();
        $user_id = $reset['user_id'];

        // Xử lý khi người dùng gửi form đặt lại mật khẩu
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Kiểm tra nếu mật khẩu không khớp
            if ($new_password !== $confirm_password) {
                $error_message = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
            } else {
                // Kiểm tra độ dài mật khẩu
                if (strlen($new_password) < 8) {
                    $error_message = "Mật khẩu mới phải có ít nhất 8 ký tự.";
                } else {
                    // Hash mật khẩu mới
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Cập nhật mật khẩu mới vào cơ sở dữ liệu
                    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->bind_param("si", $hashed_password, $user_id);

                    if ($stmt->execute()) {
                        // Xóa token sau khi sử dụng
                        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                        $stmt->bind_param("s", $token);
                        $stmt->execute();

                        $message = "Mật khẩu đã được thay đổi thành công!";
                    } else {
                        $error_message = "Đã có lỗi xảy ra khi cập nhật mật khẩu.";
                    }
                }
            }
        }
    } else {
        $error_message = "Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.";
    }
} else {
    $error_message = "Liên kết đặt lại mật khẩu không hợp lệ.";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="style.css">
</head>
<style> 
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

label {
    display: block;
    text-align: left;
    margin: 10px 0 5px;
    color: #555;
}

input[type="password"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

p {
    margin-top: 15px;
}

a {
    color: #4CAF50;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.success-message {
    color: green;
    font-weight: bold;
    margin-bottom: 20px;
}

.error-message {
    color: red;
    font-weight: bold;
    margin-bottom: 20px;
}

</style>
<body>

<div class="login-container">
    <h2>Đặt lại mật khẩu</h2>

    <?php if (!empty($message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">Mật khẩu mới:</label>
        <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>

        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>

        <input type="submit" value="Đặt lại mật khẩu">
    </form>

    <p><a href="login.php">Quay lại trang đăng nhập</a></p>
</div>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
