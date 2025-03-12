<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $friend_id = $_POST['friend_id'];
        $mutual_friends_counts = $_POST['mutual_friends_count'];
        $status = $_POST['status'];
        $status_add = $_POST['status_add'];

        if (!empty($_POST['friend_id'])) {
            $friend_relationship_id = $_POST['friend_id'];
            $stmt = $conn->prepare("UPDATE friends SET user_id=?, friend_id=?, mutual_friends_count=?, status=?, status_add=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("iisiii", $user_id, $friend_id, $mutual_friends_counts, $status, $status_add, $friend_relationship_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id, mutual_friends_count, status, status_add, updated_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("iisii", $user_id, $friend_id, $mutual_friends_counts, $status, $status_add);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $friend_relationship_id = $_POST['friend_relationship_id'];
        $stmt = $conn->prepare("DELETE FROM friends WHERE id = ?");
        $stmt->bind_param("i", $friend_relationship_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM friends WHERE user_id LIKE ? OR friend_id LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $friends = $stmt->get_result();
} else {
    $friends = $conn->query("SELECT * FROM friends");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Friends</title>
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
    <h1>Manage Friends</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID or Friend ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="friend_relationship_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="friend_id" placeholder="Friend ID" required>
        <input type="number" name="mutual_friends_counts" placeholder="Mutual Friends Counts" required>
        <input type="text" name="status" placeholder="Status" required>
        <input type="text" name="status_add" placeholder="Status Add" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Friend Relationship' : 'Add Friend Relationship' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Friend ID</th>
            <th>Mutual Friends Counts</th>
            <th>Status</th>
            <th>Status Add</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        <?php while ($friend = $friends->fetch_assoc()): ?>
            <tr>
                <td><?= $friend['id'] ?></td>
                <td><?= $friend['user_id'] ?></td>
                <td><?= $friend['friend_id'] ?></td>
                <td><?= $friend['mutual_friends_count'] ?></td>
                <td><?= $friend['status'] ?></td>
                <td><?= $friend['status_add'] ?></td>
                <td><?= $friend['updated_at'] ?></td>
                <td>
                    <a href="?edit=<?= $friend['id'] ?>&user_id=<?= $friend['user_id'] ?>&friend_id=<?= $friend['friend_id'] ?>&mutual_friends_counts=<?= $friend['mutual_friends_count'] ?>&status=<?= urlencode($friend['status']) ?>&status_add=<?= urlencode($friend['status_add']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="friend_relationship_id" value="<?= $friend['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>