<?php
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $user_id = $_POST['user_id'];
        $movie_name = $_POST['movie_name'];
        $year = $_POST['year'];
        $image_url = $_POST['image_url'];
        $movie_link = $_POST['movie_link'];

        if (!empty($_POST['favorite_movie_id'])) {
            $favorite_movie_id = $_POST['favorite_movie_id'];
            $stmt = $conn->prepare("UPDATE favorite_movies SET user_id=?, movie_name=?, year=?, image_url=?, movie_link=? WHERE id=?");
            $stmt->bind_param("issssi", $user_id, $movie_name, $year, $image_url, $movie_link, $favorite_movie_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO favorite_movies (user_id, movie_name, year, image_url, movie_link) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $movie_name, $year, $image_url, $movie_link);
        }
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $favorite_movie_id = $_POST['favorite_movie_id'];
        $stmt = $conn->prepare("DELETE FROM favorite_movies WHERE id = ?");
        $stmt->bind_param("i", $favorite_movie_id);
        $stmt->execute();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM favorite_movies WHERE movie_name LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $favorite_movies = $stmt->get_result();
} else {
    $favorite_movies = $conn->query("SELECT * FROM favorite_movies");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Favorite Movies</title>
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
    <h1>Manage Favorite Movies</h1>

    <form method="get">
        <input type="text" name="search" placeholder="Search by Movie Name" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Search</button>
    </form>
    <br>

    <form method="post">
        <input type="hidden" name="favorite_movie_id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="text" name="movie_name" placeholder="Movie Name" required>
        <input type="text" name="year" placeholder="Year" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <input type="text" name="movie_link" placeholder="Movie Link" required>
        <button type="submit" name="add"><?= isset($_GET['edit']) ? 'Update Favorite Movie' : 'Add Favorite Movie' ?></button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Movie Name</th>
            <th>Year</th>
            <th>Image URL</th>
            <th>Movie Link</th>
            <th>Actions</th>
        </tr>
        <?php while ($favorite_movie = $favorite_movies->fetch_assoc()): ?>
            <tr>
                <td><?= $favorite_movie['id'] ?></td>
                <td><?= $favorite_movie['user_id'] ?></td>
                <td><?= $favorite_movie['movie_name'] ?></td>
                <td><?= $favorite_movie['year'] ?></td>
                <td><img src="<?= $favorite_movie['image_url'] ?>" alt="<?= $favorite_movie['movie_name'] ?>" width="50"></td>
                <td><a href="<?= $favorite_movie['movie_link'] ?>" target="_blank">Watch</a></td>
                <td>
                    <a href="?edit=<?= $favorite_movie['id'] ?>&user_id=<?= $favorite_movie['user_id'] ?>&movie_name=<?= urlencode($favorite_movie['movie_name']) ?>&year=<?= $favorite_movie['year'] ?>&image_url=<?= urlencode($favorite_movie['image_url']) ?>&movie_link=<?= urlencode($favorite_movie['movie_link']) ?>">Edit</a>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="favorite_movie_id" value="<?= $favorite_movie['id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
