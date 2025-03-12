<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem có ID của chủ đề không
if (isset($_GET['id'])) {
    $threadId = $_GET['id'];

    // Lấy dữ liệu của chủ đề
    $query = "SELECT title, content FROM threads WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $threadId);
    $stmt->execute();
    $result = $stmt->get_result();
    $thread = $result->fetch_assoc();
}

// Kiểm tra nếu có yêu cầu cập nhật chủ đề
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = $_POST['title'];
    $newContent = $_POST['content'];

    // Cập nhật chủ đề
    $updateQuery = "UPDATE threads SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssi', $newTitle, $newContent, $threadId);

    if ($stmt->execute()) {
        echo "Chủ đề đã được cập nhật thành công.";
    } else {
        echo "Lỗi khi cập nhật: " . $conn->error;
    }

    $stmt->close();

    // Điều hướng về trang quản lý chủ đề
    header("Location: manage_threads.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Chủ đề</title>
    <link rel="stylesheet" href="style.css">
</head>
<style> 
/* Đặt các thiết lập cơ bản cho body */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Đặt kiểu cho phần chính */
.main-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
}

/* Tiêu đề */
.main-content h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Các nhãn (label) */
form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    text-align: left;
    color: #555;
}

/* Ô nhập tiêu đề */
form input[type="text"], 
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

/* Khu vực nội dung */
form textarea {
    height: 150px;
    resize: none;
}

/* Nút submit */
form input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Đáp ứng với màn hình nhỏ */
@media (max-width: 500px) {
    .main-content {
        width: 100%;
        padding: 15px;
        box-sizing: border-box;
    }
}

</style>
<body>
<div class="main-content">
    <h1>Sửa Chủ đề</h1>
    <form method="POST">
        <label for="title">Tiêu đề:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($thread['title']); ?>" required>

        <label for="content">Nội dung:</label>
        <textarea name="content" required><?php echo htmlspecialchars($thread['content']); ?></textarea>

        <input type="submit" value="Cập nhật">
    </form>
</div>
</body>
</html>
