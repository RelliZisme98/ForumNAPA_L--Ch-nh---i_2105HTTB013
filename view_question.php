<?php
session_start();

// Kết nối database
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm hàm handleFileUpload ngay sau phần kết nối database
function handleFileUpload($file, $type) {
    $target_dir = "uploads/" . $type . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Kiểm tra kích thước file (giới hạn 10MB)
    if ($file["size"] > 10000000) {
        return false;
    }
    
    // Kiểm tra loại file
    if ($type === 'images') {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
    } else {
        $allowed = array('pdf', 'doc', 'docx');
    }
    
    if (!in_array($file_extension, $allowed)) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

// Xử lý đăng trả lời
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer_content'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bạn cần đăng nhập để trả lời câu hỏi.'); window.location.href='login.php';</script>";
        exit;
    }

    $answer_content = $_POST['answer_content'];
    $user_id = $_SESSION['user_id'];
    
    $image_url = null;
    $attachments = null;
    
    if (isset($_FILES["answer_image"]) && $_FILES["answer_image"]["error"] == 0) {
        $image_url = handleFileUpload($_FILES["answer_image"], "images");
    }
    
    if (isset($_FILES["answer_attachment"]) && $_FILES["answer_attachment"]["error"] == 0) {
        $attachments = handleFileUpload($_FILES["answer_attachment"], "attachments");
    }
    
    $stmt = $conn->prepare("INSERT INTO answers (question_id, user_id, content, created_at, image_url, attachments) 
                           VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iisss", $question_id, $user_id, $answer_content, $image_url, $attachments);
    
    if ($stmt->execute()) {
        // Refresh trang sau khi thêm câu trả lời
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        echo "<script>alert('Có lỗi xảy ra khi đăng câu trả lời.');</script>";
    }
}

// Lấy thông tin câu hỏi
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];
    
    // Truy vấn thông tin câu hỏi và người đăng
    $stmt = $conn->prepare("
        SELECT questions.*, users.username 
        FROM questions 
        JOIN users ON questions.user_id = users.id 
        WHERE questions.id = ?
    ");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $question = $stmt->get_result()->fetch_assoc();

    // Thêm biến cho phân trang
    $answers_per_page = 3;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $answers_per_page;

    // Sửa truy vấn câu trả lời để thêm LIMIT
    $stmt = $conn->prepare("
        SELECT answers.*, users.username 
        FROM answers 
        JOIN users ON answers.user_id = users.id 
        WHERE answers.question_id = ? 
        ORDER BY answers.created_at ASC
        LIMIT ?, ?
    ");
    $stmt->bind_param("iii", $question_id, $offset, $answers_per_page);
    $stmt->execute();
    $answers = $stmt->get_result();

    // Lấy tổng số câu trả lời
    $total_stmt = $conn->prepare("SELECT COUNT(*) as total FROM answers WHERE question_id = ?");
    $total_stmt->bind_param("i", $question_id);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_answers = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total_answers / $answers_per_page);
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title><?php echo htmlspecialchars($question['title']); ?> - NAPA Forum</title>
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        .question-detail {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .question-title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .question-meta {
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .question-content {
            font-size: 16px;
            line-height: 1.6;
            color: #34495e;
            margin-bottom: 30px;
        }

        .answers-section {
            margin-top: 30px;
        }

        .answer-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .answer-meta {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .back-button {
            margin-bottom: 20px;
            display: inline-block;
        }

        /* Thêm style cho form trả lời */
        .answer-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .answer-form textarea {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            resize: vertical;
        }

        .answer-form button {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .answer-form button:hover {
            background: #2980b9;
        }

        .login-prompt {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 30px;
        }

        .load-more-answers {
            margin: 20px 0;
        }

        .load-more {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .load-more:hover {
            background: #2980b9;
        }

        .remaining {
            font-size: 0.9em;
            opacity: 0.8;
            margin-left: 5px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            padding: 5px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #3498db;
            text-decoration: none;
        }

        .page-link.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .upload-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .upload-item {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }

        .upload-item label {
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 500;
        }

        .upload-item .file-input {
            display: block;
            width: 100%;
            margin-bottom: 5px;
        }

        .upload-item small {
            display: block;
            color: #7f8c8d;
            font-size: 0.8em;
        }

        .submit-btn {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .upload-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="wavy-wraper">
        <div class="wavy">
          <span style="--i:1;">f</span>
          <span style="--i:2;">o</span>
          <span style="--i:3;">r</span>
          <span style="--i:4;">u</span>
          <span style="--i:5;">m</span>
          <span style="--i:6;">N</span>
          <span style="--i:7;">A</span>
          <span style="--i:8;">P</span>
          <span style="--i:8;">A</span>
        </div>
    </div>
    
    <div class="theme-layout">
        <?php include 'component/header.php'; ?>
        
        <section>
            <div class="gap gray-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="central-meta">
                                <div class="forum-warper">
                                    <a href="forum.php" class="back-button btn-primary">
                                        <i class="fa fa-arrow-left"></i> Trở về diễn đàn
                                    </a>
                                    
                                    <div class="question-detail">
                                        <h1 class="question-title">
                                            <?php echo htmlspecialchars($question['title']); ?>
                                        </h1>
                                        <div class="question-meta">
                                            <i class="fa fa-user"></i> Đăng bởi: <?php echo htmlspecialchars($question['username']); ?>
                                            &nbsp;&nbsp;
                                            <i class="fa fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($question['created_at'])); ?>
                                        </div>
                                        <div class="question-content">
                                            <?php echo nl2br(htmlspecialchars($question['content'])); ?>
                                            
                                            <?php if ($question['image_url'] || $question['attachments']): ?>
                                                <div class="question-attachments">
                                                    <?php if ($question['image_url']): ?>
                                                        <?php if (strpos($question['image_url'], '.pdf') !== false): ?>
                                                            <div class="pdf-preview">
                                                                <i class="fa fa-file-pdf"></i>
                                                                <a href="<?php echo $question['image_url']; ?>" target="_blank">Xem tệp PDF</a>
                                                            </div>
                                                        <?php elseif (preg_match('/\.(doc|docx)$/', $question['image_url'])): ?>
                                                            <div class="doc-preview">
                                                                <i class="fa fa-file-word"></i>
                                                                <a href="<?php echo $question['image_url']; ?>" download>Tải tài liệu Word</a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="image-preview">
                                                                <img src="<?php echo $question['image_url']; ?>" alt="Question image">
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="answers-section">
                                        <h3>
                                            <i class="fa fa-comments"></i> 
                                            Các câu trả lời (<?php echo $total_answers; ?>)
                                        </h3>
                                        
                                        <?php if ($total_answers > 0): ?>
                                            <div class="answers-container">
                                                <?php while ($answer = $answers->fetch_assoc()): ?>
                                                    <div class="answer-card">
                                                        <div class="answer-meta">
                                                            <i class="fa fa-user"></i> <?php echo htmlspecialchars($answer['username']); ?>
                                                            &nbsp;&nbsp;
                                                            <i class="fa fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($answer['created_at'])); ?>
                                                        </div>
                                                        <div class="answer-content">
                                                            <?php echo nl2br(htmlspecialchars($answer['content'])); ?>
                                                            
                                                            <?php if ($answer['image_url'] || $answer['attachments']): ?>
                                                                <div class="answer-attachments">
                                                                    <?php if ($answer['image_url']): ?>
                                                                        <?php if (strpos($answer['image_url'], '.pdf') !== false): ?>
                                                                            <div class="pdf-preview">
                                                                                <i class="fa fa-file-pdf"></i>
                                                                                <a href="<?php echo $answer['image_url']; ?>" target="_blank">Xem tệp PDF</a>
                                                                            </div>
                                                                        <?php elseif (preg_match('/\.(doc|docx)$/', $answer['image_url'])): ?>
                                                                            <div class="doc-preview">
                                                                                <i class="fa fa-file-word"></i>
                                                                                <a href="<?php echo $answer['image_url']; ?>" download>Tải tài liệu Word</a>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div class="image-preview">
                                                                                <img src="<?php echo $answer['image_url']; ?>" alt="Answer image">
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            </div>

                                            <?php if ($total_pages > 1): ?>
                                                <div class="load-more-answers text-center mt-4">
                                                    <?php if ($current_page < $total_pages): ?>
                                                        <a href="?id=<?php echo $question_id; ?>&page=<?php echo $current_page + 1; ?>" 
                                                           class="btn btn-primary load-more">
                                                            <i class="fa fa-plus"></i> Xem thêm câu trả lời
                                                            <span class="remaining">
                                                                (Còn <?php echo $total_answers - ($current_page * $answers_per_page); ?> câu trả lời)
                                                            </span>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($total_pages > 2): ?>
                                                        <div class="pagination mt-3">
                                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                <a href="?id=<?php echo $question_id; ?>&page=<?php echo $i; ?>" 
                                                                   class="page-link <?php echo $i === $current_page ? 'active' : ''; ?>">
                                                                    <?php echo $i; ?>
                                                                </a>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="text-center text-muted">
                                                <i class="fa fa-info-circle"></i> 
                                                Chưa có câu trả lời nào cho câu hỏi này.
                                            </p>
                                        <?php endif; ?>

                                        <!-- Form trả lời câu hỏi -->
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <div class="answer-form">
                                                <h3><i class="fa fa-reply"></i> Trả lời câu hỏi</h3>
                                                <form method="POST" action="" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <textarea name="answer_content" 
                                                                  placeholder="Nhập câu trả lời của bạn..." 
                                                                  required></textarea>
                                                    </div>
                                                    
                                                    <div class="form-group upload-group">
                                                        <div class="upload-item">
                                                            <label><i class="fa fa-image"></i> Thêm hình ảnh</label>
                                                            <input type="file" name="answer_image" accept="image/*" class="file-input">
                                                            <small class="text-muted">Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)</small>
                                                        </div>
                                                        
                                                        <div class="upload-item">
                                                            <label><i class="fa fa-paperclip"></i> Đính kèm tập tin</label>
                                                            <input type="file" name="answer_attachment" accept=".pdf,.doc,.docx" class="file-input">
                                                            <small class="text-muted">Hỗ trợ: PDF, DOC, DOCX (tối đa 10MB)</small>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="submit-btn">
                                                        <i class="fa fa-paper-plane"></i> Đăng trả lời
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div class="login-prompt">
                                                <p><i class="fa fa-info-circle"></i> Bạn cần 
                                                    <a href="login.php">đăng nhập</a> để trả lời câu hỏi này.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <?php include 'component/footer.php'; ?>
    </div>

    <script src="js/main.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
