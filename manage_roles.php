<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}



// Xử lý yêu cầu chuyển đổi quyền
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    if ($action === 'grant_admin') {
        // Chuyển người dùng từ bảng users sang bảng admin_users
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Thêm người dùng vào bảng admin_users
            $insert_query = "INSERT INTO admin_users (id, username, password, created_at) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("isss", $user['id'], $user['username'], $user['password'], $user['created_at']);
            if ($insert_stmt->execute()) {
                // Xóa người dùng khỏi bảng users
                $delete_query = "DELETE FROM users WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("i", $user_id);
                $delete_stmt->execute();
                echo "Đã chuyển người dùng thành admin.";
            } else {
                echo "Lỗi khi chuyển quyền.";
            }
        } else {
            echo "Người dùng không tồn tại.";
        }
    } elseif ($action === 'revoke_admin') {
        // Chuyển admin từ bảng admin_users sang bảng users
        $query = "SELECT * FROM admin_users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Thêm admin vào bảng users
            $insert_query = "INSERT INTO users (id, username, password, email, created_at) VALUES (?, ?, ?, '', ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("isss", $admin['id'], $admin['username'], $admin['password'], $admin['created_at']);
            if ($insert_stmt->execute()) {
                // Xóa admin khỏi bảng admin_users
                $delete_query = "DELETE FROM admin_users WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("i", $user_id);
                $delete_stmt->execute();
                echo "Đã hạ quyền admin.";
            } else {
                echo "Lỗi khi hạ quyền.";
            }
        } else {
            echo "Admin không tồn tại.";
        }
    }
}

// Lấy danh sách người dùng và admin
$users_query = "SELECT id, username, created_at FROM users";
$users_result = $conn->query($users_query);

$admins_query = "SELECT id, username, created_at FROM admin_users";
$admins_result = $conn->query($admins_query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân Quyền Tài Khoản Admin</title>
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

.btn-edit {
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    background-color: #007bff;
}

.btn-edit:hover {
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
</style>
<div class="main-content">
    <h1>Phân Quyền Tài Khoản Admin</h1>

    <h2>Danh sách người dùng</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên Người Dùng</th>
            <th>Ngày Tạo</th>
            <th>Hành Động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $users_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></td>
                <td>
                    <form method="POST" action="manage_roles.php">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="grant_admin">
                        <input type="submit" value="Chuyển thành Admin" class="btn-edit">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Danh sách Admin</h2>
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
                    <form method="POST" action="manage_roles.php">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="revoke_admin">
                        <input type="submit" value="Hạ Quyền Admin" class="btn-edit">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer>
    <p>&copy; 2024 Lê Chính Đại Quản lý Phân quyền Tài khoản Admin. Tất cả quyền được bảo lưu.</p>
</footer>
</body>
</html>
