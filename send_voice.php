<?php
session_start();

// Kiểm tra kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$voice_message = $_FILES['voice_message'];

// Kiểm tra xem có file âm thanh không
if ($voice_message) {
    // Đặt tên file âm thanh (có thể thay đổi tùy theo nhu cầu)
    $file_name = uniqid() . '.ogg';
    $upload_dir = 'uploads/voice_messages/';
    $upload_path = $upload_dir . $file_name;

    // Kiểm tra nếu thư mục chưa tồn tại, tạo mới
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Di chuyển file từ tạm thời đến thư mục lưu trữ
    if (move_uploaded_file($voice_message['tmp_name'], $upload_path)) {
        // Lưu thông tin tin nhắn vào cơ sở dữ liệu
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, status, created_at, file_audio) 
                VALUES (?, ?, '', 'sent', NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $user_id, $receiver_id, $upload_path);

        if ($stmt->execute()) {
            // Sau khi lưu thành công, trả về thẻ <audio>
            echo "<div class='my-message'>
                    <audio controls>
                        <source src='$upload_path' type='audio/ogg'>
                        Your browser does not support the audio element.
                    </audio>
                    <span class='message-time'>Vừa gửi</span>
                    <span class='message-status'>Đã gửi</span>
                </div>";
        } else {
            echo "Lỗi khi lưu tin nhắn vào cơ sở dữ liệu.";
        }
    } else {
        echo "Lỗi khi tải file lên.";
    }
}

$conn->close();
?>
