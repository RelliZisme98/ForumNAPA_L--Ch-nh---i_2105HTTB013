<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM footer_links WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $footer_link = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $url = $_POST['url'];
        $icon_class = $_POST['icon_class'];
        $position = $_POST['position'];

        $update_query = "UPDATE footer_links SET title = ?, url = ?, icon_class = ?, position = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssii", $title, $url, $icon_class, $position, $id);

        if ($stmt->execute()) {
            header('Location: manage_header_footer.php');
            exit();
        }
    }
} else {
    header('Location: manage_header_footer.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer Link</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Footer Link</h1>

<form action="" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?php echo $footer_link['title']; ?>" required>
    
    <label for="url">URL:</label>
    <input type="text" name="url" value="<?php echo $footer_link['url']; ?>" required>
    
    <label for="icon_class">Icon Class:</label>
    <input type="text" name="icon_class" value="<?php echo $footer_link['icon_class']; ?>">
    
    <label for="position">Position:</label>
    <input type="number" name="position" value="<?php echo $footer_link['position']; ?>" required>
    
    <button type="submit">Save Changes</button>
</form>
