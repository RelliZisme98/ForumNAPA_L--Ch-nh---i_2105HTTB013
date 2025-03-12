<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $liked_at = date("Y-m-d H:i:s");

        if (!empty($_POST['like_id'])) {
            $like_id = $_POST['like_id'];
            $stmt = $conn->prepare("UPDATE likes SET user_id=?, liked_at=? WHERE id=?");
            $stmt->bind_param("ssi", $user_id, $liked_at, $like_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO likes (user_id, liked_at) VALUES (?, ?)");
            $stmt->bind_param("ss", $user_id, $liked_at);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $like_id = $_POST['like_id'];
        $stmt = $conn->prepare("DELETE FROM likes WHERE id = ?");
        $stmt->bind_param("i", $like_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $likes = $stmt->get_result();
} else {
    $likes = $conn->query("SELECT * FROM likes");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Likes</title>
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
    <h1>Manage Likes</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="like_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Like' : 'Add Like' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Liked At</th>
            <th>Actions</th>
        </tr>
        <?php while ($like = $likes->fetch_assoc()): ?>
            <tr>
                <td><?= $like['id'] ?></td>
                <td><?= $like['user_id'] ?></td>
                <td><?= $like['liked_at'] ?></td>
                <td>
                    <a href="?edit=<?= $like['id'] ?>&user_id=<?= $like['user_id'] ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="like_id" value="<?= $like['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
