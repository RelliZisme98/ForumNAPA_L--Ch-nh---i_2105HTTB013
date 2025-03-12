<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Lấy thông tin sự kiện để chỉnh sửa
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        $error = "Sự kiện không tồn tại!";
    }
}

// Xử lý khi form được submit để cập nhật sự kiện
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];

    if (empty($title) || empty($description) || empty($event_date)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $sql = "UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $event_date, $id);

        if ($stmt->execute()) {
            $success = "Cập nhật sự kiện thành công!";
        } else {
            $error = "Lỗi khi cập nhật sự kiện.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sự Kiện</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS for the form */
        form {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #218838;
        }

        .error, .success {
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .error {
            background-color: #dc3545;
        }

        .success {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <h1>Chỉnh Sửa Sự Kiện</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="title">Tiêu đề sự kiện:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" placeholder="Nhập tiêu đề sự kiện">

        <label for="description">Mô tả sự kiện:</label>
        <textarea id="description" name="description" rows="5" placeholder="Nhập mô tả sự kiện"><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label for="event_date">Ngày diễn ra:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>">

        <input type="submit" value="Cập Nhật Sự Kiện">
    </form>
</body>
</html>
