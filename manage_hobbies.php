<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $hobby_name = $_POST['hobby_name'];
        $is_main = isset($_POST['is_main']) ? 1 : 0;

        if (!empty($_POST['hobby_id'])) {
            $hobby_id = $_POST['hobby_id'];
            $stmt = $conn->prepare("UPDATE hobbies SET user_id=?, hobby_name=?, is_main=? WHERE id=?");
            $stmt->bind_param("ssii", $user_id, $hobby_name, $is_main, $hobby_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO hobbies (user_id, hobby_name, is_main) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $user_id, $hobby_name, $is_main);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $hobby_id = $_POST['hobby_id'];
        $stmt = $conn->prepare("DELETE FROM hobbies WHERE id = ?");
        $stmt->bind_param("i", $hobby_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM hobbies WHERE user_id LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $hobbies = $stmt->get_result();
} else {
    $hobbies = $conn->query("SELECT * FROM hobbies");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Hobbies</title>
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
    <h1>Manage Hobbies</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="hobby_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="hobby_name" placeholder="Hobby Name" required>
        <label>
            <input type="checkbox" name="is_main"> Main Hobby
        </label>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Hobby' : 'Add Hobby' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Hobby Name</th>
            <th>Main Hobby</th>
            <th>Actions</th>
        </tr>
        <?php while ($hobby = $hobbies->fetch_assoc()): ?>
            <tr>
                <td><?= $hobby['id'] ?></td>
                <td><?= $hobby['user_id'] ?></td>
                <td><?= $hobby['hobby_name'] ?></td>
                <td><?= $hobby['is_main'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="?edit=<?= $hobby['id'] ?>&user_id=<?= $hobby['user_id'] ?>&hobby_name=<?= urlencode($hobby['hobby_name']) ?>&is_main=<?= $hobby['is_main'] ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="hobby_id" value="<?= $hobby['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>