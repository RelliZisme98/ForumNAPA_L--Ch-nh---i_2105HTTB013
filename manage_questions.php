<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$query = "SELECT questions.id, questions.title, questions.user_id, questions.created_at, questions.views, questions.status, users.username 
          FROM questions 
          JOIN users ON questions.user_id = users.id 
          ORDER BY questions.created_at DESC";

$result = $conn->query($query);
if (!$result) {
    echo "Lỗi truy vấn: " . $conn->error;
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Diễn Đàn</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

.main-content {
    width: 80%;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
    color: #333;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.btn-approve, .btn-reject {
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size: 14px;
}

.btn-approve {
    background-color: green;
}

.btn-reject {
    background-color: red;
}

.btn-approve:hover, .btn-reject:hover {
    opacity: 0.8;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #007bff;
    color: white;
    position: relative;
    bottom: 0;
    width: 100%;
    margin-top: 40px;
    border-top: 1px solid #ddd;
}

footer p {
    margin: 0;
    font-size: 14px;
}
.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-bottom: 20px;
}

.btn-back:hover {
    background-color: #0056b3;
}
.btn-view {
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    font-size: 14px;
    display: inline-block;
    margin-top: 8px;
}

.btn-view:hover {
    background-color: #0056b3;
}

</style>
<div class="main-content">
    <h1>Quản Lý Câu Hỏi</h1>
    <a href="index.php" class="btn-back">Quay lại</a> <!-- Dẫn đến trang index.php hoặc trang bạn muốn -->
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu Đề</th>
            <th>Người Dùng</th>
            <th>Tên Người Dùng</th>
            <th>Ngày Tạo</th>
            <th>Lượt Xem</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></td>
                <td><?php echo $row['views']; ?></td>
                <td>
                    <?php
                    if ($row['status'] == 0) {
                        echo "Chưa duyệt";
                    } elseif ($row['status'] == 1) {
                        echo "Đã duyệt";
                    } else {
                        echo "Từ chối";
                    }
                    ?>
                </td>
                <td>
    <form method="POST" action="approve_question.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <!-- Hiển thị nút Duyệt hoặc Từ chối với bất kỳ trạng thái nào -->
        <input type="submit" name="action" value="Duyệt" class="btn-approve">
        <input type="submit" name="action" value="Từ Chối" class="btn-reject">
    </form>
    <a href="view_answer.php?question_id=<?php echo $row['id']; ?>" class="btn-view">Xem câu trả lời</a> <!-- Nút Xem câu trả lời -->
</td>

            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer>
    <p>&copy; 2024 Lê Chính Đại Diễn Đàn Câu hỏi và Trả lời cho Sinh viên Học viện Hành chính Quốc gia. Tất cả quyền được bảo lưu.</p>
</footer>
</body>
</html>