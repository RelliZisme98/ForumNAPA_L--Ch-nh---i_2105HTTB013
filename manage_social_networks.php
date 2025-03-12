<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $platform_name = $_POST['platform_name'];
        $profile_url = $_POST['profile_url'];

        if (!empty($_POST['network_id'])) {
            $network_id = $_POST['network_id'];
            $stmt = $conn->prepare("UPDATE social_networks SET user_id=?, platform_name=?, profile_url=? WHERE id=?");
            $stmt->bind_param("sssi", $user_id, $platform_name, $profile_url, $network_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO social_networks (user_id, platform_name, profile_url) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_id, $platform_name, $profile_url);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $network_id = $_POST['network_id'];
        $stmt = $conn->prepare("DELETE FROM social_networks WHERE id = ?");
        $stmt->bind_param("i", $network_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM social_networks WHERE platform_name LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $social_networks = $stmt->get_result();
} else {
    $social_networks = $conn->query("SELECT * FROM social_networks");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Social Networks</title>
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
    <h1>Manage Social Networks</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Platform Name" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="network_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required value="<?= isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '' ?>">
        <input type="text" name="platform_name" placeholder="Platform Name" required value="<?= isset($_GET['platform_name']) ? htmlspecialchars($_GET['platform_name']) : '' ?>">
        <input type="url" name="profile_url" placeholder="Profile URL" required value="<?= isset($_GET['profile_url']) ? htmlspecialchars($_GET['profile_url']) : '' ?>">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Network' : 'Add Network' ?></button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Platform Name</th>
            <th>Profile URL</th>
            <th>Actions</th>
        </tr>
        <?php while ($network = $social_networks->fetch_assoc()): ?>
            <tr>
                <td><?= $network['id'] ?></td>
                <td><?= htmlspecialchars($network['user_id']) ?></td>
                <td><?= htmlspecialchars($network['platform_name']) ?></td>
                <td><a href="<?= htmlspecialchars($network['profile_url']) ?>" target="_blank"><?= htmlspecialchars($network['profile_url']) ?></a></td>
                <td>
                    <a href="?edit=<?= $network['id'] ?>&user_id=<?= urlencode($network['user_id']) ?>&platform_name=<?= urlencode($network['platform_name']) ?>&profile_url=<?= urlencode($network['profile_url']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="network_id" value="<?= $network['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
