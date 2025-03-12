<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra người dùng đã đăng nhập chưa
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Vui lòng đăng nhập để xem tên bạn bè.");
}

$friend_id = $_GET['friend_id'];

// Truy vấn tên của bạn bè
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $friend_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();

echo $username; // Trả về tên của bạn bè

$stmt->close();
$conn->close();
?>
