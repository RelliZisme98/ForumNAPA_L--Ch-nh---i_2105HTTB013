<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có `question_id` trong URL
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    
    // Lấy thông tin câu hỏi
    $question_query = "SELECT * FROM questions WHERE id = ?";
    $stmt = $conn->prepare($question_query);
    $stmt->bind_param('i', $question_id);
    $stmt->execute();
    $question_result = $stmt->get_result();
    $question = $question_result->fetch_assoc();

    // Lấy danh sách câu trả lời và thông tin người dùng
    $answers_query = "SELECT answers.id, answers.content, answers.created_at, users.username 
                      FROM answers 
                      JOIN users ON answers.user_id = users.id 
                      WHERE answers.question_id = ?";
    $stmt = $conn->prepare($answers_query);
    $stmt->bind_param('i', $question_id);
    $stmt->execute();
    $answers_result = $stmt->get_result();
} else {
    echo "Không tìm thấy câu hỏi.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Câu Trả Lời</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .main-content {
        width: 80%;
        margin: 40px auto;
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1, h2 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 24px;
    }

    h2 {
        font-size: 20px;
    }

    p {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 20px;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        background-color: #f9f9f9;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    li:hover {
        background-color: #f1f1f1;
    }

    small {
        font-size: 12px;
        color: #777;
        display: block;
        margin-top: 10px;
    }

    .btn-back, .btn-delete {
        display: inline-block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .btn-back {
        background-color: #007bff;
    }

    .btn-back:hover {
        background-color: #0056b3;
    }

    .btn-delete {
        background-color: #ff0000;
        margin-left: 10px;
    }

    .btn-delete:hover {
        background-color: #cc0000;
    }

</style>

<body>
<div class="main-content">
    <h1>Câu Hỏi: <?php echo htmlspecialchars($question['title']); ?></h1>
    <p><?php echo htmlspecialchars($question['content']); ?></p>
    <h2>Các Câu Trả Lời</h2>
    <ul>
        <?php while ($answer = $answers_result->fetch_assoc()): ?>
            <li>
                <p><?php echo htmlspecialchars($answer['content']); ?></p>
                <p><small>Người trả lời: <?php echo htmlspecialchars($answer['username']); ?> vào <?php echo date("d/m/Y H:i", strtotime($answer['created_at'])); ?></small></p>
                <!-- Nút xóa câu trả lời -->
                <a href="delete_answer.php?answer_id=<?php echo $answer['id']; ?>&question_id=<?php echo $question_id; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa câu trả lời này?');">Xóa</a>
            </li>
        <?php endwhile; ?>
    </ul>
    <a href="manage_questions.php" class="btn-back">Quay lại</a>
</div>
</body>
</html>
