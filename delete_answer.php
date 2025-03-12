<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có `answer_id` trong URL
if (isset($_GET['answer_id'])) {
    $answer_id = $_GET['answer_id'];

    // Thực hiện truy vấn xóa câu trả lời
    $query = "DELETE FROM answers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $answer_id);

    if ($stmt->execute()) {
        echo "Câu trả lời đã được xóa thành công.";
    } else {
        echo "Lỗi khi xóa câu trả lời: " . $conn->error;
    }

    // Chuyển hướng quay lại trang view_answer.php với `question_id` hiện tại
    header('Location: view_answer.php?question_id=' . $_GET['question_id']);
    exit;
} else {
    echo "Dữ liệu không hợp lệ.";
    exit;
}
?>
