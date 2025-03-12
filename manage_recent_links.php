<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $image = $_POST['image'];
        $url = $_POST['url'];
        $created_at = date('Y-m-d H:i:s');

        if (!empty($_POST['link_id'])) {
            $link_id = $_POST['link_id'];
            $stmt = $conn->prepare("UPDATE recent_links SET title=?, image=?, url=?, created_at=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $image, $url, $created_at, $link_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO recent_links (title, image, url, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $image, $url, $created_at);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $link_id = $_POST['link_id'];
        $stmt = $conn->prepare("DELETE FROM recent_links WHERE id = ?");
        $stmt->bind_param("i", $link_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM recent_links WHERE title LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $recent_links = $stmt->get_result();
} else {
    $recent_links = $conn->query("SELECT * FROM recent_links");
}
?>

<
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Recent Links</title>
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

        input[type="text"], input[type="url"], input[type="date"] {
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
</head>
<body>
    <h1>Manage Recent Links</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Title" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="link_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="title" placeholder="Title" required value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '' ?>">
        <input type="text" name="image" placeholder="Image URL" required value="<?= isset($_GET['image']) ? htmlspecialchars($_GET['image']) : '' ?>">
        <input type="url" name="url" placeholder="Link URL" required value="<?= isset($_GET['url']) ? htmlspecialchars($_GET['url']) : '' ?>">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Link' : 'Add Link' ?></button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>URL</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($link = $recent_links->fetch_assoc()): ?>
            <tr>
                <td><?= $link['id'] ?></td>
                <td><?= htmlspecialchars($link['title']) ?></td>
                <td><img src="<?= htmlspecialchars($link['image']) ?>" alt="Link Image" style="width: 50px; height: 50px;"></td>
                <td><a href="<?= htmlspecialchars($link['url']) ?>" target="_blank"><?= htmlspecialchars($link['url']) ?></a></td>
                <td><?= htmlspecialchars($link['created_at']) ?></td>
                <td>
                    <a href="?edit=<?= $link['id'] ?>&title=<?= urlencode($link['title']) ?>&image=<?= urlencode($link['image']) ?>&url=<?= urlencode($link['url']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="link_id" value="<?= $link['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>