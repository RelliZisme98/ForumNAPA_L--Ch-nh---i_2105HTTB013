<?php
session_start();

// Kiểm tra xem admin đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin FAQ cần chỉnh sửa
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $faq = $result->fetch_assoc();
    $stmt->close();
}

// Cập nhật FAQ sau khi chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $stmt = $conn->prepare("UPDATE faqs SET question = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("ssi", $question, $answer, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_faq.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa FAQ</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Sửa FAQ</h1>

        <form method="POST">
            <label for="question">Câu hỏi:</label>
            <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($faq['question']); ?>" required>

            <label for="answer">Câu trả lời:</label>
            <textarea id="answer" name="answer" required><?php echo htmlspecialchars($faq['answer']); ?></textarea>

            <input type="submit" value="Lưu thay đổi">
        </form>
    </div>

    <footer>
        © 2024 Your Forum - All Rights Reserved
    </footer>
</body>
</html>

<?php
$conn->close();
?>
