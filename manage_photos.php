<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $photo_url = $_POST['photo_url'];
        if (!empty($_POST['photo_id'])) {
            $photo_id = $_POST['photo_id'];
            $stmt = $conn->prepare("UPDATE photo SET user_id=?, photo_url=? WHERE photo_id=?");
            $stmt->bind_param("isi", $user_id, $photo_url, $photo_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO photo (user_id, photo_url) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $photo_url);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $photo_id = $_POST['photo_id'];
        $stmt = $conn->prepare("DELETE FROM photo WHERE photo_id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM photo WHERE photo_url LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $photos = $stmt->get_result();
} else {
    $photos = $conn->query("SELECT * FROM photo");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Photos</title>
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
    <h1>Manage Photos</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Photo URL" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="photo_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="photo_url" placeholder="Photo URL" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Photo' : 'Add Photo' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>Photo ID</th>
            <th>User ID</th>
            <th>Photo URL</th>
            <th>Actions</th>
        </tr>
        <?php while ($photo = $photos->fetch_assoc()): ?>
            <tr>
                <td><?= $photo['photo_id'] ?></td>
                <td><?= $photo['user_id'] ?></td>
                <td><?= $photo['photo_url'] ?></td>
                <td>
                    <a href="?edit=<?= $photo['photo_id'] ?>&user_id=<?= $photo['user_id'] ?>&photo_url=<?= urlencode($photo['photo_url']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="photo_id" value="<?= $photo['photo_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
