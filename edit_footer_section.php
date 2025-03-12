<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM footer_sections WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $footer_section = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $section_title = $_POST['section_title'];
        $position = $_POST['position'];

        $update_query = "UPDATE footer_sections SET section_title = ?, position = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sii", $section_title, $position, $id);

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
    <title>Edit Footer Section</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Footer Section</h1>

<form action="" method="POST">
    <label for="section_title">Section Title:</label>
    <input type="text" name="section_title" value="<?php echo $footer_section['section_title']; ?>" required>
    
    <label for="position">Position:</label>
    <input type="number" name="position" value="<?php echo $footer_section['position']; ?>" required>
    
    <button type="submit">Save Changes</button>
</form>
