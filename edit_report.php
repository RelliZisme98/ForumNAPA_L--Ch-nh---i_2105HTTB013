<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra nếu có ID trong URL
if (!isset($_GET['id'])) {
    header("Location: manage_reports.php");
    exit();
}

$id = intval($_GET['id']);

// Truy vấn để lấy thông tin báo cáo
$sql = "SELECT * FROM reports WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_reports.php");
    exit();
}

$report = $result->fetch_assoc();

// Xử lý dữ liệu khi gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $status = $_POST['status'];

    $update_sql = "UPDATE reports SET content = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssi', $content, $status, $id);
    
    if ($update_stmt->execute()) {
        header("Location: manage_reports.php");
        exit();
    } else {
        echo "Cập nhật không thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Báo Cáo</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Chỉnh Sửa Báo Cáo</h1>
    <form action="" method="POST">
        <label for="content">Nội Dung:</label>
        <textarea name="content" id="content" required><?php echo htmlspecialchars($report['content']); ?></textarea>

        <label for="status">Trạng Thái:</label>
        <select name="status" id="status">
            <option value="pending" <?php if ($report['status'] === 'pending') echo 'selected'; ?>>Chờ xử lý</option>
            <option value="resolved" <?php if ($report['status'] === 'resolved') echo 'selected'; ?>>Đã giải quyết</option>
            <option value="rejected" <?php if ($report['status'] === 'rejected') echo 'selected'; ?>>Đã từ chối</option>
        </select>

        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
