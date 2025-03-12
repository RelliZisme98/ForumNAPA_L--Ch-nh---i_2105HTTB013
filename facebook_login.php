<?php
session_start(); // Bắt đầu session

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi nhận dữ liệu từ AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu email tồn tại, lấy thông tin người dùng
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Chuyển hướng tới trang chủ hoặc trang khác
        echo "success";
    } else {
        // Nếu chưa tồn tại, thêm mới người dùng
        $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $email);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;

            echo "success";
        } else {
            echo "error";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>
