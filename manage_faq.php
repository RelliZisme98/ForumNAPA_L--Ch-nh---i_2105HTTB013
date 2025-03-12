<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem admin đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ledai_forum";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý thêm mới FAQ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faq'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    if ($question && $answer) {
        $stmt = $conn->prepare("INSERT INTO faqs (question, answer, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $question, $answer);
        $stmt->execute();
        $stmt->close();
    }
}

// Xử lý xóa FAQ
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Lấy danh sách tất cả các FAQ
$sql = "SELECT * FROM faqs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý FAQ</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Quản lý FAQ</h1>

        <!-- Form thêm mới FAQ -->
        <h2>Thêm FAQ mới</h2>
        <form method="POST">
            <label for="question">Câu hỏi:</label>
            <input type="text" id="question" name="question" required>

            <label for="answer">Câu trả lời:</label>
            <textarea id="answer" name="answer" required></textarea>

            <input type="submit" name="add_faq" value="Thêm FAQ">
        </form>

        <!-- Danh sách các FAQ hiện có -->
        <h2>Danh sách các FAQ</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Câu hỏi</th>
                    <th>Câu trả lời</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['question']); ?></td>
                    <td><?php echo htmlspecialchars($row['answer']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="edit_faq.php?id=<?php echo $row['id']; ?>" class="edit-btn">Sửa</a>
                        <a href="manage_faq.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa FAQ này?');">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        © 2024 Your Forum - All Rights Reserved
    </footer>
</body>
</html>

<?php
$conn->close();
?>
