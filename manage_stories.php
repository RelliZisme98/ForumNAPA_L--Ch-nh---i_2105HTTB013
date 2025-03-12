<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $image_url = $_POST['image_url'];
        $created_at = date("Y-m-d H:i:s");
        $expires_at = $_POST['expires_at'];

        if (!empty($_POST['story_id'])) {
            $story_id = $_POST['story_id'];
            $stmt = $conn->prepare("UPDATE stories SET user_id=?, image_url=?, created_at=?, expires_at=? WHERE story_id=?");
            $stmt->bind_param("ssssi", $user_id, $image_url, $created_at, $expires_at, $story_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO stories (user_id, image_url, created_at, expires_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user_id, $image_url, $created_at, $expires_at);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $story_id = $_POST['story_id'];
        $stmt = $conn->prepare("DELETE FROM stories WHERE story_id = ?");
        $stmt->bind_param("i", $story_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM stories WHERE user_id LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stories = $stmt->get_result();
} else {
    $stories = $conn->query("SELECT * FROM stories");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Stories</title>
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
    <h1>Manage Stories</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="story_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <input type="datetime-local" name="expires_at" placeholder="Expires At" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Story' : 'Add Story' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>Story ID</th>
            <th>User ID</th>
            <th>Image URL</th>
            <th>Created At</th>
            <th>Expires At</th>
            <th>Actions</th>
        </tr>
        <?php while ($story = $stories->fetch_assoc()): ?>
            <tr>
                <td><?= $story['story_id'] ?></td>
                <td><?= $story['user_id'] ?></td>
                <td><img src="<?= $story['image_url'] ?>" alt="Story Image" width="50"></td>
                <td><?= $story['created_at'] ?></td>
                <td><?= $story['expires_at'] ?></td>
                <td>
                    <a href="?edit=<?= $story['story_id'] ?>&user_id=<?= $story['user_id'] ?>&image_url=<?= urlencode($story['image_url']) ?>&expires_at=<?= $story['expires_at'] ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="story_id" value="<?= $story['story_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>