<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $event_name = $_POST['event_name'];
        $event_link = $_POST['event_link'];
        $event_icon = $_POST['event_icon'];
        $event_class = $_POST['event_class'];

        if (!empty($_POST['event_id'])) {
            $event_id = $_POST['event_id'];
            $stmt = $conn->prepare("UPDATE specialevents SET event_name=?, event_link=?, event_icon=?, event_class=? WHERE id=?");
            $stmt->bind_param("ssssi", $event_name, $event_link, $event_icon, $event_class, $event_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO specialevents (event_name, event_link, event_icon, event_class) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $event_name, $event_link, $event_icon, $event_class);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $event_id = $_POST['event_id'];
        $stmt = $conn->prepare("DELETE FROM specialevents WHERE id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM specialevents WHERE event_name LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $special_events = $stmt->get_result();
} else {
    $special_events = $conn->query("SELECT * FROM specialevents");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Special Events</title>
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
</head>
<body>
    <h1>Manage Special Events</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Event Name" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="event_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="event_name" placeholder="Event Name" required value="<?= isset($_GET['event_name']) ? htmlspecialchars($_GET['event_name']) : '' ?>">
        <input type="text" name="event_link" placeholder="Event Link" required value="<?= isset($_GET['event_link']) ? htmlspecialchars($_GET['event_link']) : '' ?>">
        <input type="text" name="event_icon" placeholder="Event Icon" required value="<?= isset($_GET['event_icon']) ? htmlspecialchars($_GET['event_icon']) : '' ?>">
        <input type="text" name="event_class" placeholder="Event Class" required value="<?= isset($_GET['event_class']) ? htmlspecialchars($_GET['event_class']) : '' ?>">
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Event' : 'Add Event' ?></button>
    </form>

    <table>
        <tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Event Link</th>
            <th>Event Icon</th>
            <th>Event Class</th>
            <th>Actions</th>
        </tr>
        <?php while ($event = $special_events->fetch_assoc()): ?>
            <tr>
                <td><?= $event['id'] ?></td>
                <td><?= htmlspecialchars($event['event_name']) ?></td>
                <td><?= htmlspecialchars($event['event_link']) ?></td>
                <td><?= htmlspecialchars($event['event_icon']) ?></td>
                <td><?= htmlspecialchars($event['event_class']) ?></td>
                <td>
                    <a href="?edit=<?= $event['id'] ?>&event_name=<?= urlencode($event['event_name']) ?>&event_link=<?= urlencode($event['event_link']) ?>&event_icon=<?= urlencode($event['event_icon']) ?>&event_class=<?= urlencode($event['event_class']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
