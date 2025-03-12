<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$friend_id = isset($_POST['friend_id']) ? intval($_POST['friend_id']) : 0;

if ($friend_id > 0) {
    // Cập nhật trạng thái tin nhắn từ 'delivered' thành 'seen'
    $sql = "UPDATE messages SET status = 'seen' 
            WHERE receiver_id = ? AND sender_id = ? AND status = 'delivered'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $friend_id);

    if ($stmt->execute()) {
        echo "success"; // Nếu cập nhật thành công
    } else {
        echo "error: " . $stmt->error; // Ghi lại lỗi nếu có
    }

    $stmt->close();
} else {
    echo "Invalid friend ID.";
}

$conn->close();
?>
