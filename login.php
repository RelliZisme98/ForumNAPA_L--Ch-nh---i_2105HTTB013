<?php
ob_start();
session_start(); // Bắt đầu session

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ledai_forum";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$error = ""; // Biến chứa lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Truy vấn để tìm tài khoản admin theo username
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Lỗi khi chuẩn bị truy vấn: " . $conn->error); // Kiểm tra lỗi trong quá trình chuẩn bị truy vấn
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra xem username có tồn tại không
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kiểm tra mật khẩu (giả định mật khẩu đã được hash)
        if (password_verify($password, $row['password'])) {
            // Thiết lập session sau khi đăng nhập thành công
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Chuyển hướng đến trang index
            header("Location: index.php");
            exit();
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}
?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập Admin</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .login-container {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            .login-container h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #007bff;
            }
            .login-container input[type="text"],
            .login-container input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .login-container input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .login-container input[type="submit"]:hover {
                background-color: #0056b3;
            }
            .error {
                color: red;
                text-align: center;
            }
        </style>
    </head>
    <body>
    <div class="login-container">
        <h2>Đăng nhập Admin</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="submit" value="Đăng nhập">
        </form>
    </div>
    </body>
    </html>
