<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra xem ID có được truyền không
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin của liên kết cần chỉnh sửa
    $query = "SELECT * FROM header_links WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $header_link = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $url = $_POST['url'];
        $position = $_POST['position'];
        $is_logged_in = isset($_POST['is_logged_in']) ? 1 : 0;

        // Cập nhật thông tin
        $update_query = "UPDATE header_links SET title = ?, url = ?, position = ?, is_logged_in = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssiii", $title, $url, $position, $is_logged_in, $id);

        if ($update_stmt->execute()) {
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
    <title>Edit Header Link</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Header Link</h1>

        <form action="" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $header_link['title']; ?>" required>
            
            <label for="url">URL:</label>
            <input type="text" name="url" value="<?php echo $header_link['url']; ?>" required>
            
            <label for="position">Position:</label>
            <input type="number" name="position" value="<?php echo $header_link['position']; ?>" required>
            
            <label for="is_logged_in">Show when logged in only:</label>
            <input type="checkbox" name="is_logged_in" <?php echo ($header_link['is_logged_in'] ? 'checked' : ''); ?>>
            
            <input type="submit" value="Save Changes">
        </form>
    </div>

    <footer>
        © 2024 Your Forum - All Rights Reserved
    </footer>
</body>
</html>
