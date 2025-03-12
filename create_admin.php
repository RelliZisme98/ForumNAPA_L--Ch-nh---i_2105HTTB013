<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập (ví dụ: kiểm tra session của admin)
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "ledai_forum"; 

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra các trường có được nhập đầy đủ không
    if (empty($new_username) || empty($new_password) || empty($confirm_password)) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Mật khẩu không khớp!";
    } else {
        // Mã hóa mật khẩu trước khi lưu
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Kiểm tra xem username đã tồn tại chưa
        $sql_check = "SELECT * FROM admin_users WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $new_username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Username đã tồn tại!";
        } else {
            // Lưu tài khoản admin mới vào cơ sở dữ liệu
            $sql = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Tạo tài khoản admin thành công!";
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo tài khoản Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .create-admin-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .create-admin-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .create-admin-container input[type="text"], .create-admin-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .create-admin-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .create-admin-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="create-admin-container">
        <h2>Tạo tài khoản Admin</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="create_admin.php">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            <input type="submit" value="Tạo tài khoản">
        </form>
    </div>
</body>
</html>
