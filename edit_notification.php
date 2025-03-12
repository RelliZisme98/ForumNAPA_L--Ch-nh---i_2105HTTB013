<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu của thông báo
if (isset($_GET['id'])) {
    $notificationId = $_GET['id'];

    // Lấy thông báo từ cơ sở dữ liệu
    $query = "SELECT user_id, content, is_read FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $notificationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $notification = $result->fetch_assoc();
}

// Kiểm tra nếu có yêu cầu cập nhật thông báo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newContent = $_POST['content'];
    $isRead = isset($_POST['is_read']) ? 1 : 0;

    // Cập nhật thông báo
    $updateQuery = "UPDATE notifications SET content = ?, is_read = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sii', $newContent, $isRead, $notificationId);

    if ($stmt->execute()) {
        echo "Thông báo đã được cập nhật thành công.";
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
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
    <title>Sửa Thông báo</title>
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
    <h1>Sửa Thông báo</h1>
    <form method="POST">
        <label for="content">Nội dung:</label>
        <textarea name="content" required><?php echo htmlspecialchars($notification['content']); ?></textarea>

        <label for="is_read">Trạng thái đã đọc:</label>
        <input type="checkbox" name="is_read" <?php echo $notification['is_read'] ? 'checked' : ''; ?>>

        <input type="su
