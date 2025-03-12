<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có yêu cầu tạo chủ đề mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Thêm chủ đề mới
    $insertQuery = "INSERT INTO threads (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('ss', $title, $content);

    if ($stmt->execute()) {
        echo "Chủ đề mới đã được thêm thành công.";
    } else {
        echo "Lỗi khi thêm chủ đề: " . $conn->error;
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
    <title>Thêm Chủ đề Mới</title>
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
    background-color: #28a745;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

form input[type="submit"]:hover {
    background-color: #218838;
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
    <h1>Thêm Chủ đề Mới</h1>
    <form method="POST">
        <label for="title">Tiêu đề:</label>
        <input type="text" name="title" required>

        <label for="content">Nội dung:</label>
        <textarea name="content" required></textarea>

        <input type="submit" value="Thêm Chủ đề">
    </form>
</div>
</body>
</html>
