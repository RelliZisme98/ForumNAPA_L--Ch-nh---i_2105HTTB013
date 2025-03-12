<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $post_id = $_POST['post_id'];
        $image_url = $_POST['image_url'];
        if (!empty($_POST['image_id'])) {
            $image_id = $_POST['image_id'];
            $stmt = $conn->prepare("UPDATE postimages SET post_id=?, image_url=? WHERE image_id=?");
            $stmt->bind_param("isi", $post_id, $image_url, $image_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO postimages (post_id, image_url) VALUES (?, ?)");
            $stmt->bind_param("is", $post_id, $image_url);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $image_id = $_POST['image_id'];
        $stmt = $conn->prepare("DELETE FROM postimages WHERE image_id = ?");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM postimages WHERE image_url LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $images = $stmt->get_result();
} else {
    $images = $conn->query("SELECT * FROM postimages");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Post Images</title>
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
    <h1>Manage Post Images</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Image URL" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="image_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="post_id" placeholder="Post ID" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Image' : 'Add Image' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>Image ID</th>
            <th>Post ID</th>
            <th>Image URL</th>
            <th>Actions</th>
        </tr>
        <?php while ($image = $images->fetch_assoc()): ?>
            <tr>
                <td><?= $image['image_id'] ?></td>
                <td><?= $image['post_id'] ?></td>
                <td><?= $image['image_url'] ?></td>
                <td>
                    <a href="?edit=<?= $image['image_id'] ?>&post_id=<?= $image['post_id'] ?>&image_url=<?= urlencode($image['image_url']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="image_id" value="<?= $image['image_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
