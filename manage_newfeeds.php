<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm hoặc sửa bài đăng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $content = $_POST['content'];
        $location_lat = $_POST['location_lat'];
        $location_lon = $_POST['location_lon'];
        $visibility = $_POST['visibility'];

        if (!empty($_POST['post_id'])) {
            // Cập nhật bài đăng
            $post_id = $_POST['post_id'];
            $stmt = $conn->prepare("UPDATE newfeeds SET user_id=?, content=?, location_lat=?, location_lon=?, visibility=? WHERE post_id=?");
            $stmt->bind_param("isssii", $user_id, $content, $location_lat, $location_lon, $visibility, $post_id);
        } else {
            // Thêm bài đăng mới
            $stmt = $conn->prepare("INSERT INTO newfeeds (user_id, content, location_lat, location_lon, visibility) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $user_id, $content, $location_lat, $location_lon, $visibility);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Xóa bài đăng
        $post_id = $_POST['post_id'];
        $stmt = $conn->prepare("DELETE FROM newfeeds WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
    }
}

// Tìm kiếm bài đăng
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM newfeeds WHERE user_id LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $newfeeds = $stmt->get_result();
} else {
    $newfeeds = $conn->query("SELECT * FROM newfeeds");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Newfeeds</title>
</head>
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
<body>
    <h1>Manage Newfeeds</h1>

    <!-- Form tìm kiếm -->
    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <!-- Form thêm và sửa bài đăng -->
    <form method="post">
        <input type="hidden" name="post_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <textarea name="content" placeholder="Content" required></textarea>
        <input type="text" name="location_lat" placeholder="Location Latitude">
        <input type="text" name="location_lon" placeholder="Location Longitude">
        <input type="number" name="visibility" placeholder="Visibility">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Post' : 'Add Post' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>Post ID</th>
            <th>User ID</th>
            <th>Content</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Visibility</th>
            <th>Actions</th>
        </tr>
        <?php while ($newfeed = $newfeeds->fetch_assoc()): ?>
            <tr>
                <td><?= $newfeed['post_id'] ?></td>
                <td><?= $newfeed['user_id'] ?></td>
                <td><?= $newfeed['content'] ?></td>
                <td><?= $newfeed['location_lat'] ?></td>
                <td><?= $newfeed['location_lon'] ?></td>
                <td><?= $newfeed['visibility'] ?></td>
                <td>
                    <!-- Nút sửa -->
                    <a href="?edit=<?= $newfeed['post_id'] ?>&user_id=<?= $newfeed['user_id'] ?>&content=<?= $newfeed['content'] ?>&location_lat=<?= $newfeed['location_lat'] ?>&location_lon=<?= $newfeed['location_lon'] ?>&visibility=<?= $newfeed['visibility'] ?>">Edit</a>
                    <!-- Nút xóa -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="post_id" value="<?= $newfeed['post_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>