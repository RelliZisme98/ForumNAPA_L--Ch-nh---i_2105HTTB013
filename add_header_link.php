<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    $position = $_POST['position'];
    $is_logged_in = isset($_POST['is_logged_in']) ? 1 : 0;

    $query = "INSERT INTO header_links (title, url, position, is_logged_in, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssii', $title, $url, $position, $is_logged_in);

    if ($stmt->execute()) {
        header('Location: manage_header_footer.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Header Link</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Header Link</h1>

<form action="" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" required>
    <label for="url">URL:</label>
    <input type="text" name="url" required>
    <label for="position">Position:</label>
    <input type="number" name="position" required>
    <label for="is_logged_in">Show when logged in only:</label>
    <input type="checkbox" name="is_logged_in">
    <button type="submit">Add Header Link</button>
</form>
