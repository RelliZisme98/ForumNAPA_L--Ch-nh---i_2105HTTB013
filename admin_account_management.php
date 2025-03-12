<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý các yêu cầu POST (thêm mới, chỉnh sửa, xóa)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Xóa tài khoản admin
    if ($action === 'delete_admin') {
        $admin_id = intval($_POST['admin_id']);
        $delete_query = "DELETE FROM admin_users WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $admin_id);
        if ($stmt->execute()) {
            echo "Đã xóa tài khoản admin.";
        } else {
            echo "Lỗi khi xóa tài khoản admin.";
        }
    }

    // Thêm tài khoản admin mới
    if ($action === 'add_admin') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu

        $insert_query = "INSERT INTO admin_users (username, password, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            echo "Đã thêm tài khoản admin mới.";
        } else {
            echo "Lỗi khi thêm tài khoản admin.";
        }
    }

    // Sửa thông tin tài khoản admin
    if ($action === 'edit_admin') {
        $admin_id = intval($_POST['admin_id']);
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        if ($password) {
            $update_query = "UPDATE admin_users SET username = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssi", $username, $password, $admin_id);
        } else {
            $update_query = "UPDATE admin_users SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $username, $admin_id);
        }

        if ($stmt->execute()) {
            echo "Đã cập nhật tài khoản admin.";
        } else {
            echo "Lỗi khi cập nhật tài khoản admin.";
        }
    }
}

// Lấy danh sách admin từ cơ sở dữ liệu
$admins_query = "SELECT id, username, created_at FROM admin_users";
$admins_result = $conn->query($admins_query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản Admin</title>
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

.btn-edit, .btn-delete {
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size: 14px;
}

.btn-edit {
    background-color: #007bff;
}

.btn-delete {
    background-color: red;
}

.btn-edit:hover, .btn-delete:hover {
    opacity: 0.8;
}

/* Form thêm tài khoản admin */
.add-admin-form, .edit-admin-form {
    margin-top: 40px;
}

input[type="text"], input[type="password"] {
    padding: 10px;
    width: 100%;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

input[type="submit"] {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
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
</style>
<body>
<div class="main-content">
    <h1>Quản Lý Tài Khoản Admin</h1>

    <h2>Danh sách tài khoản Admin</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên Admin</th>
            <th>Ngày Tạo</th>
            <th>Hành Động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $admins_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></td>
                <td>
                    <form method="POST" action="admin_account_management.php" style="display:inline;">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="action" value="Sửa" class="btn-edit">
                    </form>
                    <form method="POST" action="admin_account_management.php" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản admin này không?');">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="delete_admin">
                        <input type="submit" value="Xóa" class="btn-delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Nếu có yêu cầu sửa, hiển thị form sửa tài khoản admin -->
    <?php if (isset($_POST['action']) && $_POST['action'] === 'Sửa'): 
        $admin_id = intval($_POST['admin_id']);
        $edit_query = "SELECT id, username FROM admin_users WHERE id = ?";
        $stmt = $conn->prepare($edit_query);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->bind_result($admin_id, $admin_username);
        $stmt->fetch();
    ?>
        <h2>Sửa Tài Khoản Admin</h2>
        <form method="POST" action="admin_account_management.php" class="edit-admin-form">
            <input type="hidden" name="action" value="edit_admin">
            <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">

            <label for="username">Tên Admin:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin_username); ?>" required>

            <label for="password">Mật khẩu mới (bỏ trống nếu không muốn thay đổi):</label>
            <input type="password" name="password">

            <input type="submit" value="Cập nhật">
        </form>
    <?php endif; ?>

<footer>
    <p>&copy; 2024 Lê Chính Đại Quản lý Tài khoản Admin. Tất cả quyền được bảo lưu.</p>
</footer>
</body>
</html>
