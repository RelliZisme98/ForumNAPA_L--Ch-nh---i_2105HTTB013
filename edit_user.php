<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Lấy dữ liệu người dùng từ cơ sở dữ liệu
    $query = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cập nhật thông tin người dùng
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $updateQuery = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssi', $newUsername, $newEmail, $userId);

    if ($stmt->execute()) {
        echo "Thông tin người dùng đã được cập nhật.";
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }

    $stmt->close();
    // Điều hướng trở lại trang quản lý tài khoản sau khi cập nhật
    header("Location: manage_accounts.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tài Khoản</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
        
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

.main-content {
    width: 50%;
    margin: 50px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 18px;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-bottom: 20px;
}

.btn-back:hover {
    background-color: #0056b3;
}
    </style>
<body>
<div class="main-content">
    <h1>Sửa Tài Khoản</h1>
    <a href="manage_accounts.php" class="btn-back">Quay lại</a> <!-- Nút quay lại trang quản lý tài khoản -->
    <form method="POST">
        <label for="username">Tên người dùng:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <input type="submit" value="Cập nhật">
    </form>
</div>
</body>
</html>
