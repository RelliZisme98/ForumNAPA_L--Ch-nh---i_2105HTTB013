<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_title = $_POST['section_title'];
    $position = $_POST['position'];

    $insert_query = "INSERT INTO footer_sections (section_title, position, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("si", $section_title, $position);

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
    <title>Add Footer Section</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Footer Section</h1>

<form action="" method="POST">
    <label for="section_title">Section Title:</label>
    <input type="text" name="section_title" required>
    
    <label for="position">Position:</label>
    <input type="number" name="position" required>
    
    <button type="submit">Add Footer Section</button>
</form>
