<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra nếu có ID trong URL
if (!isset($_GET['id'])) {
    header("Location: manage_contact.php");
    exit();
}

$id = intval($_GET['id']);

// Truy vấn để lấy thông tin liên hệ
$sql = "SELECT * FROM contacts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_contact.php");
    exit();
}

$contact = $result->fetch_assoc();

// Xử lý dữ liệu khi gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $update_sql = "UPDATE contacts SET name = ?, email = ?, message = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssi', $name, $email, $message, $id);
    
    if ($update_stmt->execute()) {
        header("Location: manage_contact.php");
        exit();
    } else {
        echo "Cập nhật không thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Liên Hệ</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Chỉnh Sửa Liên Hệ</h1>
    <form action="" method="POST">
        <label for="name">Tên:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>

        <label for="message">Nội Dung:</label>
        <textarea name="message" id="message" required><?php echo htmlspecialchars($contact['message']); ?></textarea>

        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
