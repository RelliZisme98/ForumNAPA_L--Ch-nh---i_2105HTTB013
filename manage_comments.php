<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm, sửa hoặc xóa bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        $comment_text = $_POST['comment_text'];
        $likes_count = $_POST['likes_count'];

        if (!empty($_POST['comment_id'])) {
            // Cập nhật bình luận
            $comment_id = $_POST['comment_id'];
            $stmt = $conn->prepare("UPDATE comments SET post_id=?, user_id=?, comment_text=?, likes_count=? WHERE comment_id=?");
            $stmt->bind_param("iisii", $post_id, $user_id, $comment_text, $likes_count, $comment_id);
        } else {
            // Thêm bình luận mới
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment_text, likes_count) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iisi", $post_id, $user_id, $comment_text, $likes_count);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        // Xóa bình luận
        $comment_id = $_POST['comment_id'];
        $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();
    }
}

// Tìm kiếm bình luận
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM comments WHERE user_id LIKE ? OR post_id LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $comments = $stmt->get_result();
} else {
    $comments = $conn->query("SELECT * FROM comments");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Comments</title>
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
    <h1>Manage Comments</h1>

    <!-- Form tìm kiếm -->
    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID or Post ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <!-- Form thêm và sửa bình luận -->
    <form method="post">
        <input type="hidden" name="comment_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="post_id" placeholder="Post ID" required>
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="comment_text" placeholder="Comment Text" required>
        <input type="number" name="likes_count" placeholder="Likes Count">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Comment' : 'Add Comment' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>Comment ID</th>
            <th>Post ID</th>
            <th>User ID</th>
            <th>Comment Text</th>
            <th>Likes Count</th>
            <th>Actions</th>
        </tr>
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <tr>
                <td><?= $comment['comment_id'] ?></td>
                <td><?= $comment['post_id'] ?></td>
                <td><?= $comment['user_id'] ?></td>
                <td><?= $comment['comment_text'] ?></td>
                <td><?= $comment['likes_count'] ?></td>
                <td>
                    <!-- Nút sửa -->
                    <a href="?edit=<?= $comment['comment_id'] ?>&post_id=<?= $comment['post_id'] ?>&user_id=<?= $comment['user_id'] ?>&comment_text=<?= $comment['comment_text'] ?>&likes_count=<?= $comment['likes_count'] ?>">Edit</a>
                    <!-- Nút xóa -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>