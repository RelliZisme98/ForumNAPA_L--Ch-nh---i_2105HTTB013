<?php
session_start();
// Kết nối CSDL
// Kết nối cơ sở dữ liệu
// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xem trang này.";
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


// Lấy id của câu hỏi từ URL
$question_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn câu hỏi
$question_query = $conn->prepare("SELECT q.id, q.title, q.content, q.created_at, u.username 
                                  FROM questions q 
                                  JOIN users u ON q.user_id = u.id 
                                  WHERE q.id = ?");
$question_query->bind_param("i", $question_id);
$question_query->execute();
$question_result = $question_query->get_result();

// Kiểm tra nếu câu hỏi tồn tại
if ($question_result->num_rows == 0) {
    echo "Câu hỏi không tồn tại!";
    exit;
}

// Lấy thông tin câu hỏi
$question = $question_result->fetch_assoc();

// Xử lý khi người dùng gửi trả lời
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_content = isset($_POST['reply']) ? trim($_POST['reply']) : '';

    if (!empty($reply_content)) {
        $user_id = 1; // Giả sử đã đăng nhập, bạn sẽ thay bằng thông tin user thực
        $insert_reply = $conn->prepare("INSERT INTO answers (question_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $insert_reply->bind_param("iis", $question_id, $user_id, $reply_content);
        $insert_reply->execute();
    }
}

// Truy vấn các câu trả lời
$replies_query = $conn->prepare("SELECT r.content, r.created_at, u.username 
                                 FROM answers r 
                                 JOIN users u ON r.user_id = u.id 
                                 WHERE r.question_id = ? 
                                 ORDER BY r.created_at ASC");
$replies_query->bind_param("i", $question_id);
$replies_query->execute();
$replies_result = $replies_query->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
	<title>Diễn đàn Câu hỏi và Trả lời Học viện Hành chính Quốc gia</title> 
    <link rel="stylesheet" href="css/main.min.css">
	<link rel="stylesheet" href="css/weather-icons.min.css">
	<link rel="stylesheet" href="css/toast-notification.css">
	<link rel="stylesheet" href="css/page-tour.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
    <title><?php echo htmlspecialchars($question['title']); ?></title>
</head>

<style>
    .question-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.question-info {
    color: #555;
    margin-bottom: 20px;
}

.question-content {
    font-size: 18px;
    line-height: 1.5;
    margin-bottom: 30px;
}

.reply-title, .replies-title {
    font-size: 22px;
    margin-bottom: 15px;
    color: #333;
}

.reply-form {
    margin-bottom: 40px;
}

.reply-input {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    font-size: 16px;
}

.reply-submit {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.reply-submit:hover {
    background-color: #0056b3;
}

.replies-list {
    list-style-type: none;
    padding: 0;
}

.reply-item {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eaeaea;
}

.reply-username {
    font-weight: bold;
    color: #007BFF;
}

.reply-date {
    color: #999;
    font-size: 14px;
}

.reply-content {
    margin-top: 5px;
    font-size: 16px;
    line-height: 1.4;
}

.no-replies {
    font-size: 16px;
    color: #999;
    margin-top: 10px;
}

</style>
<body>
<?php include 'component/header.php'; ?>
<?php include 'component/sidebarright.php'; ?>
<h1 class="question-title"><?php echo htmlspecialchars($question['title']); ?></h1>
<p class="question-info">Được hỏi bởi: <span class="username"><?php echo htmlspecialchars($question['username']); ?></span> vào <span class="date"><?php echo date('d/m/Y H:i', strtotime($question['created_at'])); ?></span></p>
<p class="question-content"><?php echo htmlspecialchars($question['content']); ?></p>

<h2 class="reply-title">Trả lời câu hỏi</h2>
<form method="post" action="" class="reply-form">
    <textarea name="reply" placeholder="Nhập câu trả lời của bạn..." class="reply-input"></textarea>
    <br>
    <input type="submit" value="Gửi câu trả lời" class="reply-submit">
</form>

<h3 class="replies-title">Các câu trả lời</h3>
<?php if ($replies_result->num_rows > 0): ?>
    <ul class="replies-list">
        <?php while ($reply = $replies_result->fetch_assoc()): ?>
            <li class="reply-item">
                <strong class="reply-username"><?php echo htmlspecialchars($reply['username']); ?></strong> trả lời vào <span class="reply-date"><?php echo date('d/m/Y H:i', strtotime($reply['created_at'])); ?></span>:
                <p class="reply-content"><?php echo htmlspecialchars($reply['content']); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="no-replies">Chưa có câu trả lời nào.</p>
<?php endif; ?>
    
    <?php include 'component/footer.php'; ?>
</body>
</html>

