<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tìm kiếm câu hỏi nếu có
if (isset($_GET['query'])) {
    $search_query = "%" . $_GET['query'] . "%";  // Thêm ký tự % để tìm kiếm chứa từ khóa
    $stmt = $conn->prepare("
        SELECT questions.*, users.username 
        FROM questions 
        JOIN users ON questions.user_id = users.id 
        WHERE questions.title LIKE ? OR questions.content LIKE ? 
        ORDER BY questions.created_at DESC
    ");
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Nếu không có từ khóa tìm kiếm, có thể chuyển hướng về trang chủ hoặc hiển thị thông báo
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #2c3e50;
            padding: 20px 0;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        header a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid white;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        header a:hover {
            background-color: white;
            color: #2c3e50;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-form button {
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-form button:hover {
            background-color: #2980b9;
        }

        .question-list {
            list-style: none;
            padding: 0;
        }

        .question-list li {
            background: white;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .question-list li:hover {
            transform: translateY(-2px);
        }

        .question-list a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
        }

        .question-list span {
            display: block;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Kết quả tìm kiếm</h1>
        <a href="index.php"><i class="fas fa-home"></i> Trở về trang chủ</a>
    </header>

    <div class="search-box">
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="query" value="<?php echo htmlspecialchars($_GET['query']); ?>" placeholder="Nhập từ khóa tìm kiếm...">
            <button type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
        </form>
    </div>

    <h2>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($_GET['query']); ?>"</h2>
    <ul class="question-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <a href="view_question.php?id=<?php echo $row['id']; ?>">
                        <i class="fas fa-question-circle"></i> <?php echo htmlspecialchars($row['title']); ?>
                    </a>
                    <span>
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($row['username']); ?> 
                        <i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                    </span>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li style="text-align: center; color: #666;">
                <i class="fas fa-info-circle"></i> Không có kết quả nào được tìm thấy.
            </li>
        <?php endif; ?>
    </ul>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let searchForm = document.querySelector('.search-form');
    let queryInput = searchForm.querySelector('input[type="text"]');
    
    searchForm.addEventListener('submit', function(e) {
        if (queryInput.value.trim() === "") {
            e.preventDefault();
            alert('Vui lòng nhập từ khóa tìm kiếm.');
        }
    });
});
</script>
</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
