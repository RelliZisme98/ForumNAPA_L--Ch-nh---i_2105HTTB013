<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách người dùng
$userQuery = "SELECT id, username FROM users";
$userResult = $conn->query($userQuery);

// Kiểm tra nếu có yêu cầu thêm mới thông báo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $content = $_POST['content'];

    // Thêm thông báo mới
    $insertQuery = "INSERT INTO notifications (user_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('is', $userId, $content);

    if ($stmt->execute()) {
        echo "Thông báo mới đã được thêm thành công.";
    } else {
        echo "Lỗi khi thêm thông báo: " . $conn->error;
    }

    $stmt->close();

    // Điều hướng về trang quản lý thông báo
    header("Location: manage_notifications.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thông báo Mới</title>
    <link rel="stylesheet" href="style.css">
</head>
<style> 
/* Reset các thuộc tính cơ bản */
body, h1, h2, h3, h4, p, ul, li {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

h1 {
    text-align: center;
    margin-top: 20px;
    color: #333;
}

/* Giao diện chính cho trang */
.main-content {
    width: 80%;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Định dạng bảng */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Định dạng form */
form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-size: 16px;
    color: #333;
}

input[type="text"], textarea, select {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    width: 100%;
}

textarea {
    height: 100px;
    resize: vertical;
}

/* Định dạng nút (buttons) */
input[type="submit"], .btn-add, .btn-edit, .btn-delete {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
}

input[type="submit"]:hover, .btn-add:hover, .btn-edit:hover, .btn-delete:hover {
    background-color: #0056b3;
}

/* Định dạng cho nút thêm thông báo mới */
.btn-add {
    display: inline-block;
    margin-bottom: 20px;
}

/* Nút quay lại */
.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-bottom: 20px;
}

.btn-back:hover {
    background-color: #218838;
}

/* Định dạng cho footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #007bff;
    color: white;
    margin-top: 40px;
    border-top: 1px solid #ddd;
}

footer p {
    margin: 0;
    font-size: 14px;
}

</style>
<body>
<div class="main-content">
    <h1>Thêm Thông báo Mới</h1>
    <form method="POST">
        <label for="user_id">Người dùng:</label>
        <select name="user_id" required>
            <?php while ($user = $userResult->fetch_assoc()): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="content">Nội dung:</label>
        <textarea name="content" required></textarea>

        <input type="submit" value="Thêm Thông báo">
    </form>
</div>
</body>
</html>
