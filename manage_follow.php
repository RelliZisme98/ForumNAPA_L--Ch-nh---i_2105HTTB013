<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Truy vấn để lấy danh sách theo dõi
$sql = "SELECT f.id, u.username AS user, q.title AS question, f.created_at
        FROM followers f
        JOIN users u ON f.user_id = u.id
        JOIN questions q ON f.question_id = q.id
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Theo Dõi</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Quản Lý Theo Dõi</h1>
    
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Người Dùng</th>
            <th>Câu Hỏi</th>
            <th>Thời Gian</th>
            <th>Thao Tác</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user']; ?></td>
            <td><?php echo $row['question']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="edit_follow.php?id=<?php echo $row['id']; ?>">Sửa</a>
                <a href="delete_follow.php?id=<?php echo $row['id']; ?>">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
