<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy danh sách thông báo và thông tin người dùng
$query = "SELECT n.id, u.username, n.content, n.created_at, n.is_read 
          FROM notifications n 
          JOIN users u ON n.user_id = u.id
          ORDER BY n.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thông báo</title>
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
    <h1>Quản lý Thông báo</h1>

    <!-- Nút thêm thông báo mới -->
    <a href="add_notification.php" class="btn-add">Thêm Thông báo Mới</a>

    <!-- Hiển thị danh sách thông báo -->
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Người dùng</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['content']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['is_read'] ? 'Đã đọc' : 'Chưa đọc'; ?></td>
                <td>
                    <a href="edit_notification.php?id=<?php echo $row['id']; ?>" class="btn-edit">Sửa</a>
                    <a href="delete_notification.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này không?');">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Thông báo nếu không có thông báo nào
    if ($result->num_rows == 0) {
        echo "<p>Không có thông báo nào.</p>";
    }
    ?>
</div>

<footer>
    <p>&copy; 2024 Quản lý thông báo của hệ thống. Tất cả quyền được bảo lưu.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
