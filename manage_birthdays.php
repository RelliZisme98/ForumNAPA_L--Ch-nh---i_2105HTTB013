<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm bản ghi mới hoặc cập nhật bản ghi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_name = $_POST['user_name'];
        $birthday_date = $_POST['birthday_date'];
        $profile_image = $_POST['profile_image'];
        $background_image = $_POST['background_image'];
        $event_name = $_POST['event_name'];
        $message = $_POST['birthday_message'];

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Cập nhật bản ghi
            $id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE birthdays SET user_name=?, birthday_date=?, profile_image=?, background_image=?, event_name=?, birthday_message=? WHERE id=?");
            $stmt->bind_param("ssssssi", $user_name, $birthday_date, $profile_image, $background_image, $event_name, $message, $id);
        } else {
            // Thêm bản ghi mới
            $stmt = $conn->prepare("INSERT INTO birthdays (user_name, birthday_date, profile_image, background_image, event_name, birthday_message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $user_name, $birthday_date, $profile_image, $background_image, $event_name, $message);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Xóa bản ghi
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM birthdays WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// Tìm kiếm theo tên người dùng nếu có yêu cầu
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM birthdays WHERE user_name LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $birthdays = $stmt->get_result();
} else {
    $birthdays = $conn->query("SELECT * FROM birthdays");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Birthdays</title>
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
<body>
    <h1>Manage Birthdays</h1>

    <!-- Form tìm kiếm -->
    <form method="get">
        <input type="text" name="search" placeholder="Search by User Name" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <!-- Form thêm và sửa -->
    <form method="post">
        <input type="hidden" name="id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_name" placeholder="User Name" value="<?= isset($_GET['user_name']) ? $_GET['user_name'] : '' ?>" required>
        <input type="date" name="birthday_date" value="<?= isset($_GET['birthday_date']) ? $_GET['birthday_date'] : '' ?>" required>
        <input type="text" name="profile_image" placeholder="Profile Image URL" value="<?= isset($_GET['profile_image']) ? $_GET['profile_image'] : '' ?>">
        <input type="text" name="background_image" placeholder="Background Image URL" value="<?= isset($_GET['background_image']) ? $_GET['background_image'] : '' ?>">
        <input type="text" name="event_name" placeholder="Event Name" value="<?= isset($_GET['event_name']) ? $_GET['event_name'] : '' ?>">
        <input type="text" name="message" placeholder="Message" value="<?= isset($_GET['message']) ? $_GET['message'] : '' ?>">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Birthday' : 'Add Birthday' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Birthday Date</th>
            <th>Event Name</th>
            <th>Message</th>
            <th>Action</th>
        </tr>
        <?php while ($birthday = $birthdays->fetch_assoc()): ?>
            <tr>
                <td><?= $birthday['id'] ?></td>
                <td><?= $birthday['user_name'] ?></td>
                <td><?= $birthday['birthday_date'] ?></td>
                <td><?= $birthday['event_name'] ?></td>
                <td><?= $birthday['birthday_message'] ?></td>
                <td>
                    <!-- Nút sửa -->
                    <a href="?edit=<?= $birthday['id'] ?>&user_name=<?= $birthday['user_name'] ?>&birthday_date=<?= $birthday['birthday_date'] ?>&profile_image=<?= $birthday['profile_image'] ?>&background_image=<?= $birthday['background_image'] ?>&event_name=<?= $birthday['event_name'] ?>&message=<?= $birthday['birthday_message'] ?>">Edit</a>
                    <!-- Nút xóa -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $birthday['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>