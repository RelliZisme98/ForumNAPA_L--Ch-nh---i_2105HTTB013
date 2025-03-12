<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem có dữ liệu POST gửi đến hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    // Xử lý hành động Duyệt hoặc Từ Chối
    if ($action == 'Duyệt') {
        $sql = "UPDATE questions SET status = 1 WHERE id = ?";
    } elseif ($action == 'Từ Chối') {
        $sql = "UPDATE questions SET status = 2 WHERE id = ?";
    }

    // Chuẩn bị câu lệnh truy vấn
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    // Thực thi câu truy vấn
    if ($stmt->execute()) {
        echo "Cập nhật thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    // Chuyển hướng quay lại trang quản lý
    header("Location: manage_questions.php");
    exit();
}
?>
