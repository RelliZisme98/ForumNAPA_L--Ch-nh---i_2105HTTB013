<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Truy vấn để lấy danh sách báo cáo
$sql = "SELECT r.id, u.username AS reporter, r.content, r.type, r.reference_id, r.created_at, r.status
        FROM reports r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Báo Cáo</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Quản Lý Báo Cáo</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Người Báo Cáo</th>
            <th>Nội Dung</th>
            <th>Loại</th>
            <th>ID Tham Chiếu</th>
            <th>Thời Gian</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['reporter']; ?></td>
            <td><?php echo $row['content']; ?></td>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['reference_id']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <a href="edit_report.php?id=<?php echo $row['id']; ?>">Sửa</a>
                <a href="delete_report.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
