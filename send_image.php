<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

    // Kiểm tra định dạng file hợp lệ
    if (in_array($file_ext, $allowed_extensions)) {
        // Đặt tên file mới duy nhất
        $new_file_name = uniqid() . '.' . $file_ext;
        $upload_dir = "uploads/images/";

        // Kiểm tra xem thư mục upload có tồn tại không, nếu không tạo mới
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Di chuyển file đến thư mục uploads/images/
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            // Chuẩn bị truy vấn SQL để thêm tin nhắn
            $file_url = $upload_dir . $new_file_name;

            $sql = "INSERT INTO messages (sender_id, receiver_id, message, status, created_at, file_url) 
                    VALUES (?, ?, ?, 'sent', NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $empty_message = ''; // Tin nhắn rỗng vì chỉ gửi ảnh
            $stmt->bind_param('iiss', $user_id, $receiver_id, $empty_message, $file_url);

            // Thực thi câu lệnh SQL
            if ($stmt->execute()) {
                echo "<div class='my-message'>
                    <img src='$file_url' alt='Image' style='max-width: 100px;'>
                    <span class='message-time'>Vừa gửi</span>
                    <span class='message-status'>Đã gửi</span>
                </div>";
            } else {
                echo "Lỗi khi thêm tin nhắn vào cơ sở dữ liệu: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Không thể di chuyển file đến thư mục uploads/images/.";
        }
    } else {
        echo "Định dạng file không hợp lệ. Vui lòng tải lên file jpg, jpeg, png hoặc gif.";
    }
} else {
    echo "Không có ảnh nào được chọn hoặc có lỗi khi tải lên.";
}

$conn->close();
?>
