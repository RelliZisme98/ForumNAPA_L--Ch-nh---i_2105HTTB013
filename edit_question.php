<?php
session_start(); // Bắt đầu phiên làm việc
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra ID câu hỏi
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin câu hỏi
    $query = "SELECT * FROM questions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    // Kiểm tra xem có kết quả không
    if (!$question) {
        echo "Không tìm thấy câu hỏi!";
        exit;
    }
} else {
    echo "ID không hợp lệ!";
    exit;
}

// Xử lý cập nhật thông tin câu hỏi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $thread_id = $_POST['thread_id'];
    $status = $_POST['status']; // Nếu bạn có trường trạng thái

    $update_query = "UPDATE questions SET title = ?, content = ?, thread_id = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssisi', $title, $content, $thread_id, $status, $id);

    if ($update_stmt->execute()) {
        echo "Cập nhật câu hỏi thành công!";
        header('Location: manage_questions.php');
        exit;
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<div class="main-content">
    <h1>Sửa Câu Hỏi</h1>
    <form method="post">
        <label for="title">Tiêu Đề:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($question['title']); ?>" required>
        
        <label for="content">Nội Dung:</label>
        <textarea name="content" required><?php echo htmlspecialchars($question['content']); ?></textarea>
        
        <label for="thread_id">Chủ Đề:</label>
        <select name="thread_id">
            <!-- Thêm mã để lấy danh sách các chủ đề (threads) từ cơ sở dữ liệu -->
            <?php
            $threads_query = "SELECT id, name FROM threads";
            $threads_result = $conn->query($threads_query);
            while ($thread = $threads_result->fetch_assoc()):
            ?>
                <option value="<?php echo $thread['id']; ?>" <?php if ($thread['id'] == $question['thread_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($thread['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="status">Trạng Thái:</label>
        <select name="status">
            <option value="active" <?php if ($question['status'] == 'active') echo 'selected'; ?>>Hoạt Động</option>
            <option value="inactive" <?php if ($question['status'] == 'inactive') echo 'selected'; ?>>Không Hoạt Động</option>
        </select>

        <button type="submit">Cập Nhật</button>
    </form>
</div>

<?php include 'footer.php'; ?>
