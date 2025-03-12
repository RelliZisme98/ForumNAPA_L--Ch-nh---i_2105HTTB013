<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem có yêu cầu tìm kiếm không
$searchTerm = "";
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = "%$searchTerm%";
    
    // Truy vấn tìm kiếm người dùng
    $query = "SELECT id, username, email FROM users WHERE username LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
} else {
    // Truy vấn tất cả người dùng nếu không có yêu cầu tìm kiếm
    $query = "SELECT id, username, email FROM users";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài khoản</title>
    <link rel="stylesheet" href="style.css">
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

        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
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

        input[type="text"] {
            width: 70%;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="main-content">
    <h1>Quản Lý Tài Khoản</h1>
    <a href="index.php" class="btn-back">Quay lại</a> <!-- Dẫn đến trang index.php hoặc trang bạn muốn -->  
    <!-- Form tìm kiếm -->
    <form method="GET" action="manage_accounts.php">
        <input type="text" name="search" placeholder="Tìm kiếm theo tên hoặc email" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="submit" value="Tìm kiếm">
    </form>

    <!-- Hiển thị danh sách người dùng -->
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Email</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-edit">Sửa</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?');">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Thông báo nếu không tìm thấy kết quả nào
    if ($result->num_rows == 0) {
        echo "<p>Không tìm thấy người dùng nào.</p>";
    }
    ?>
</div>

<footer>
    <p>&copy; 2024 Lê Chính Đại Diễn Đàn Câu hỏi và Trả lời cho Sinh viên Học viện Hành chính Quốc gia. Tất cả quyền được bảo lưu.</p>
</footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
