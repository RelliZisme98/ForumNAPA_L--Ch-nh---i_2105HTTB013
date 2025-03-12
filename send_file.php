<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Người dùng chưa đăng nhập.");
}

$user_id = $_SESSION['user_id'];
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;

if ($receiver_id > 0 && isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $new_file_name = uniqid() . '_' . $file_name;
    $upload_dir = "uploads/files/";
    $file_path = $upload_dir . $new_file_name;

    if ($_FILES['file']['size'] <= 10000000) { // Giới hạn 10MB
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql = "INSERT INTO messages (sender_id, receiver_id, message, status, created_at, file_url) 
                    VALUES (?, ?, ?, 'sent', NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $empty_message = ''; 
            $stmt->bind_param('iiss', $user_id, $receiver_id, $empty_message, $file_path);

            if ($stmt->execute()) {
                echo "<div class='my-message'>
                        <a href='$file_path' target='_blank'>" . htmlspecialchars($file_name) . "</a>
                        <span class='message-time'>Vừa gửi</span>
                        <span class='message-status'>Đã gửi</span>
                      </div>";
            } else {
                echo "Lỗi khi thêm tin nhắn vào cơ sở dữ liệu: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Không thể di chuyển file đến thư mục uploads/files/.";
        }
    } else {
        echo "File vượt quá giới hạn kích thước 10MB.";
    }
} else {
    echo "Không có tệp nào được chọn hoặc có lỗi khi tải lên.";
}

$conn->close();
?>
