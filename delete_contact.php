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
    header("Location: manage_contact.php");
    exit();
}

$id = intval($_GET['id']);

// Truy vấn để xóa liên hệ
$delete_sql = "DELETE FROM contacts WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param('i', $id);

if ($delete_stmt->execute()) {
    header("Location: manage_contact.php");
    exit();
} else {
    echo "Xóa không thành công!";
}
?>
