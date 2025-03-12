<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Truy vấn để lấy danh sách liên hệ
$sql = "SELECT id, name, email, message, created_at FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Liên Hệ</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Quản Lý Liên Hệ</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Nội Dung</th>
            <th>Thời Gian</th>
            <th>Thao Tác</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo htmlspecialchars($row['message']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="edit_contact.php?id=<?php echo $row['id']; ?>">Sửa</a>
                <a href="delete_contact.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
