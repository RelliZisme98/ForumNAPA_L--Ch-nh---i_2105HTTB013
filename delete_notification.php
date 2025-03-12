<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xóa thông báo nếu có yêu cầu
if (isset($_GET['id'])) {
    $notificationId = $_GET['id'];

    // Truy vấn xóa thông báo
    $deleteQuery = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('i', $notificationId);

    if ($stmt->execute()) {
        echo "Thông báo đã được xóa thành công.";
    } else {
        echo "Lỗi khi xóa thông báo: " . $conn->error;
    }

    $stmt->close();

    // Điều hướng về trang quản lý thông báo
    header("Location: manage_notifications.php");
    exit;
}

$conn->close();
?>
