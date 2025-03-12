<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra nếu người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['admin_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit();
}

// Xử lý xóa sự kiện
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: manage_community.php");
        exit();
    }
}

// Lấy danh sách sự kiện từ bảng events
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Community</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }

        th, td {
            padding: 10px;
        }

        th {
            background-color: #0056b3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .add-event-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .add-event-btn:hover {
            background-color: #218838;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-weight: bold;
        }

        .edit-btn {
            background-color: #ffc107;
            color: white;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h1>Quản lý Community</h1>

<a href="add_event.php" class="add-event-btn">Thêm Sự Kiện Mới</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Ngày sự kiện</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($row['event_date'])); ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="edit-btn">Chỉnh sửa</a>
                        <a href="manage_community.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa sự kiện này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Chưa có sự kiện nào.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
