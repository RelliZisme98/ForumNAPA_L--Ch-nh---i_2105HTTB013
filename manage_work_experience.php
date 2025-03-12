<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $position = $_POST['position'];
        $company_name = $_POST['company_name'];
        $years_of_experience = $_POST['years_of_experience'];

        if (!empty($_POST['work_id'])) {
            $work_id = $_POST['work_id'];
            $stmt = $conn->prepare("UPDATE work_experience SET user_id=?, position=?, company_name=?, years_of_experience=? WHERE id=?");
            $stmt->bind_param("ssssi", $user_id, $position, $company_name, $years_of_experience, $work_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO work_experience (user_id, position, company_name, years_of_experience) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $user_id, $position, $company_name, $years_of_experience);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $work_id = $_POST['work_id'];
        $stmt = $conn->prepare("DELETE FROM work_experience WHERE id = ?");
        $stmt->bind_param("i", $work_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM work_experience WHERE user_id LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $work_experience = $stmt->get_result();
} else {
    $work_experience = $conn->query("SELECT * FROM work_experience");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Work Experience</title>
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
    <h1>Manage Work Experience</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by User ID" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="work_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="position" placeholder="Position" required>
        <input type="text" name="company_name" placeholder="Company Name" required>
        <input type="number" name="years_of_experience" placeholder="Years of Experience" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Work Experience' : 'Add Work Experience' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Position</th>
            <th>Company Name</th>
            <th>Years of Experience</th>
            <th>Actions</th>
        </tr>
        <?php while ($experience = $work_experience->fetch_assoc()): ?>
            <tr>
                <td><?= $experience['id'] ?></td>
                <td><?= $experience['user_id'] ?></td>
                <td><?= $experience['position'] ?></td>
                <td><?= $experience['company_name'] ?></td>
                <td><?= $experience['years_of_experience'] ?></td>
                <td>
                    <a href="?edit=<?= $experience['id'] ?>&user_id=<?= $experience['user_id'] ?>&position=<?= urlencode($experience['position']) ?>&company_name=<?= urlencode($experience['company_name']) ?>&years_of_experience=<?= $experience['years_of_experience'] ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="work_id" value="<?= $experience['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>