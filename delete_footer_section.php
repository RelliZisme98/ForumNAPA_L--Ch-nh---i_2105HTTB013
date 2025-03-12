<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_query = "DELETE FROM footer_sections WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: manage_header_footer.php');
        exit();
    }
} else {
    header('Location: manage_header_footer.php');
    exit();
}
?>
