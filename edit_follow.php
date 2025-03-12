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
    header("Location: manage_follow.php");
    exit();
}

$id = intval($_GET['id']);

// Truy vấn để lấy thông tin theo dõi
$sql = "SELECT * FROM followers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_follow.php");
    exit();
}

$follow = $result->fetch_assoc();

// Xử lý dữ liệu khi gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $question_id = intval($_POST['question_id']);

    $update_sql = "UPDATE followers SET user_id = ?, question_id = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('iii', $user_id, $question_id, $id);
    
    if ($update_stmt->execute()) {
        header("Location: manage_follow.php");
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
    <title>Chỉnh Sửa Theo Dõi</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Chỉnh Sửa Theo Dõi</h1>
    <form action="" method="POST">
        <label for="user_id">ID Người Dùng:</label>
        <input type="number" name="user_id" id="user_id" value="<?php echo $follow['user_id']; ?>" required>

        <label for="question_id">ID Câu Hỏi:</label>
        <input type="number" name="question_id" id="question_id" value="<?php echo $follow['question_id']; ?>" required>

        <button type="submit">Cập Nhật</button>
    </form>
</body>
</html>
