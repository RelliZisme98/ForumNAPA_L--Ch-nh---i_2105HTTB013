<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_id = $_POST['section_id'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $icon_class = $_POST['icon_class'];
    $position = $_POST['position'];

    $insert_query = "INSERT INTO footer_links (section_id, title, url, icon_class, position, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isssi", $section_id, $title, $url, $icon_class, $position);

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
    <title>Add Footer Link</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Footer Link</h1>

<form action="" method="POST">
    <label for="section_id">Section ID:</label>
    <input type="number" name="section_id" required>
    
    <label for="title">Title:</label>
    <input type="text" name="title" required>
    
    <label for="url">URL:</label>
    <input type="text" name="url" required>
    
    <label for="icon_class">Icon Class:</label>
    <input type="text" name="icon_class">
    
    <label for="position">Position:</label>
    <input type="number" name="position" required>
    
    <button type="submit">Add Footer Link</button>
</form>
