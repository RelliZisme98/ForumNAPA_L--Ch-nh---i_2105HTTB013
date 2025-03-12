<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($message !== "" && $receiver_id > 0) {
    $sql = "INSERT INTO messages (sender_id, receiver_id, message, status, created_at) 
            VALUES (?, ?, ?, 'sent', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $user_id, $receiver_id, $message);
    if ($stmt->execute()) {
        echo "<div class='my-message'>
                <p>$message</p>
                <span class='message-time'>Vừa gửi</span>
                <span class='message-status'>Đã gửi</span>
            </div>";
     } else {
        echo "Lỗi khi gửi tin nhắn: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Vui lòng nhập tin nhắn hợp lệ.";
}
$conn->close();
?>