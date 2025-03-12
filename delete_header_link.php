<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xóa liên kết
    $delete_query = "DELETE FROM header_links WHERE id = ?";
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
