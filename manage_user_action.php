<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem yêu cầu có chứa hành động xóa hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'Xóa') {
        $userId = $_POST['id'];

        // Xóa người dùng từ cơ sở dữ liệu
        $deleteQuery = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            echo "Người dùng đã được xóa thành công.";
        } else {
            echo "Lỗi khi xóa người dùng: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();

// Điều hướng trở lại trang quản lý tài khoản sau khi xóa
header("Location: manage_accounts.php");
exit;
?>
