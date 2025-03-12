<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có ID của chủ đề
if (isset($_GET['id'])) {
    $threadId = $_GET['id'];

    // Xóa chủ đề
    $deleteQuery = "DELETE FROM threads WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('i', $threadId);

    if ($stmt->execute()) {
        echo "Chủ đề đã được xóa thành công.";
    } else {
        echo "Lỗi khi xóa: " . $conn->error;
    }

    $stmt->close();

    // Điều hướng về trang quản lý chủ đề
    header("Location: manage_threads.php");
    exit;
}

$conn->close();
?>
