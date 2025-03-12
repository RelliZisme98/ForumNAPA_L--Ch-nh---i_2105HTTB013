<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $twitter_handle = $_POST['twitter_handle'];
        $message = $_POST['message'];
        $timestamp = date("Y-m-d H:i:s");

        if (!empty($_POST['feed_id'])) {
            $feed_id = $_POST['feed_id'];
            $stmt = $conn->prepare("UPDATE twitter_feed SET username=?, twitter_handle=?, message=?, timestamp=? WHERE id=?");
            $stmt->bind_param("ssssi", $username, $twitter_handle, $message, $timestamp, $feed_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO twitter_feed (username, twitter_handle, message, timestamp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $twitter_handle, $message, $timestamp);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $feed_id = $_POST['feed_id'];
        $stmt = $conn->prepare("DELETE FROM twitter_feed WHERE id = ?");
        $stmt->bind_param("i", $feed_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM twitter_feed WHERE username LIKE ? OR twitter_handle LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $twitter_feed = $stmt->get_result();
} else {
    $twitter_feed = $conn->query("SELECT * FROM twitter_feed");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Twitter Feeds</title>
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
    <h1>Manage Twitter Feed</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Username or Handle" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="feed_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="twitter_handle" placeholder="Twitter Handle" required>
        <textarea name="message" placeholder="Message" required></textarea>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Feed' : 'Add Feed' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Twitter Handle</th>
            <th>Message</th>
            <th>Timestamp</th>
            <th>Actions</th>
        </tr>
        <?php while ($feed = $twitter_feed->fetch_assoc()): ?>
            <tr>
                <td><?= $feed['id'] ?></td>
                <td><?= $feed['username'] ?></td>
                <td><?= $feed['twitter_handle'] ?></td>
                <td><?= $feed['message'] ?></td>
                <td><?= $feed['timestamp'] ?></td>
                <td>
                    <a href="?edit=<?= $feed['id'] ?>&username=<?= urlencode($feed['username']) ?>&twitter_handle=<?= urlencode($feed['twitter_handle']) ?>&message=<?= urlencode($feed['message']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="feed_id" value="<?= $feed['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
