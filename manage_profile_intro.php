<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $about = $_POST['about'];
        $fav_tv_show = $_POST['fav_tv_show'];
        $favourit_music = $_POST['favourit_music'];

        if (!empty($_POST['profile_id'])) {
            $profile_id = $_POST['profile_id'];
            $stmt = $conn->prepare("UPDATE profile_intro SET user_id=?, about=?, fav_tv_show=?, favourit_music=? WHERE id=?");
            $stmt->bind_param("ssssi", $user_id, $about, $fav_tv_show, $favourit_music, $profile_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO profile_intro (user_id, about, fav_tv_show, favourit_music) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user_id, $about, $fav_tv_show, $favourit_music);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $profile_id = $_POST['profile_id'];
        $stmt = $conn->prepare("DELETE FROM profile_intro WHERE id = ?");
        $stmt->bind_param("i", $profile_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM profile_intro WHERE about LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $profile_intro = $stmt->get_result();
} else {
    $profile_intro = $conn->query("SELECT * FROM profile_intro");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Profile Introduction</title>
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

        input[type="text"], textarea {
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
    <h1>Manage Profile Introduction</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by About" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="profile_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required value="<?= isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '' ?>">
        <textarea name="about" placeholder="About" required><?= isset($_GET['about']) ? htmlspecialchars($_GET['about']) : '' ?></textarea>
        <input type="text" name="fav_tv_show" placeholder="Favorite TV Show" required value="<?= isset($_GET['fav_tv_show']) ? htmlspecialchars($_GET['fav_tv_show']) : '' ?>">
        <input type="text" name="favourit_music" placeholder="Favorite Music" required value="<?= isset($_GET['favourit_music']) ? htmlspecialchars($_GET['favourit_music']) : '' ?>">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Profile' : 'Add Profile' ?></button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>About</th>
            <th>Favorite TV Show</th>
            <th>Favorite Music</th>
            <th>Actions</th>
        </tr>
        <?php while ($profile = $profile_intro->fetch_assoc()): ?>
            <tr>
                <td><?= $profile['id'] ?></td>
                <td><?= htmlspecialchars($profile['user_id']) ?></td>
                <td><?= htmlspecialchars($profile['about']) ?></td>
                <td><?= htmlspecialchars($profile['fav_tv_show']) ?></td>
                <td><?= htmlspecialchars($profile['favourit_music']) ?></td>
                <td>
                    <a href="?edit=<?= $profile['id'] ?>&user_id=<?= urlencode($profile['user_id']) ?>&about=<?= urlencode($profile['about']) ?>&fav_tv_show=<?= urlencode($profile['fav_tv_show']) ?>&favourit_music=<?= urlencode($profile['favourit_music']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="profile_id" value="<?= $profile['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
