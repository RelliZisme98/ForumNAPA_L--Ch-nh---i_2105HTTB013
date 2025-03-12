<?php
session_start();
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thực hiện hành động này.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Thêm hàm xử lý upload file
function handleFileUpload($file, $type, $id) {
    $target_dir = "uploads/" . $type . "/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = $type . "_" . $id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Kiểm tra loại file
    $allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

// Thêm chủ đề
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['description'], $_POST['content'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("INSERT INTO threads (title, user_id, description, content) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $title, $user_id, $description, $content);

    // Thực hiện truy vấn
    if ($stmt->execute()) {
        echo "<script>alert('Chủ đề đã được tạo thành công!'); window.location.href='forum.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi tạo chủ đề, vui lòng thử lại!');</script>";
    }
    $stmt->close();
}

// Thêm câu hỏi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_title'], $_POST['question_content'], $_POST['thread_id'])) {
    $question_title = $_POST['question_title'];
    $question_content = $_POST['question_content'];
    $thread_id = $_POST['thread_id'];

    $image_url = null;
    $attachments = null;
    
    // Xử lý upload ảnh
    if (isset($_FILES["question_image"]) && $_FILES["question_image"]["error"] == 0) {
        $image_url = handleFileUpload($_FILES["question_image"], "questions", time());
    }
    
    // Xử lý upload file đính kèm
    if (isset($_FILES["question_attachment"]) && $_FILES["question_attachment"]["error"] == 0) {
        $attachments = handleFileUpload($_FILES["question_attachment"], "questions", time());
    }

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("INSERT INTO questions (title, content, user_id, thread_id, created_at, image_url, attachments) 
                           VALUES (?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("ssiiss", $question_title, $question_content, $user_id, $thread_id, $image_url, $attachments);

    // Thực hiện truy vấn
    if ($stmt->execute()) {
        echo "<script>alert('Câu hỏi đã được tạo thành công!'); window.location.href='forum.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi tạo câu hỏi, vui lòng thử lại!');</script>";
    }
    $stmt->close();
}

// Truy vấn danh sách các chủ đề
$threads_query = $conn->query("SELECT id, title FROM threads");
$threads = [];
if ($threads_query) {
    while ($row = $threads_query->fetch_assoc()) {
        $threads[] = $row; // Lưu chủ đề vào mảng
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
	<title>NAPA Social Network</title>
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
    <style>
        .forum-form {
            margin-bottom: 30px;
        }
        
        .form-section {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .form-section h4 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .c-form div {
            margin-bottom: 20px;
        }

        .c-form label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }

        .c-form input[type="text"],
        .c-form textarea,
        .c-form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .c-form input[type="text"]:focus,
        .c-form textarea:focus,
        .c-form select:focus {
            border-color: #3498db;
            outline: none;
        }

        .c-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .main-btn {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .main-btn:hover {
            background: #2980b9;
        }

        .form-divider {
            margin: 40px 0;
            text-align: center;
            position: relative;
        }

        .form-divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #eee;
        }

        .form-divider span {
            background: #f8f9fa;
            padding: 0 15px;
            color: #7f8c8d;
            position: relative;
            font-size: 16px;
        }

        .upload-section {
            margin: 20px 0;
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
            transition: all 0.3s ease;
        }

        .upload-item:hover {
            border-color: #3498db;
        }

        .upload-item label {
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 500;
        }

        .file-input {
            display: block;
            width: 100%;
            margin-bottom: 5px;
        }

        .image-preview {
            margin-top: 10px;
            max-width: 200px;
            display: none;
        }

        .image-preview img {
            width: 100%;
            border-radius: 4px;
        }

        .file-info {
            margin-top: 10px;
            font-size: 0.9em;
            color: #666;
        }

        @media (max-width: 768px) {
            .upload-group {
                flex-direction: column;
                gap: 10px;
            }
        }

        .upload-label {
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .upload-placeholder {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-placeholder:hover {
            border-color: #3498db;
            background-color: #f8f9fa;
        }

        .upload-placeholder i {
            font-size: 24px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .upload-placeholder span {
            display: block;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .upload-placeholder small {
            display: block;
            color: #7f8c8d;
        }

        .image-preview {
            margin-top: 15px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .file-info {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            display: none;
        }

        .file-info.active {
            display: block;
        }

        .file-info i {
            margin-right: 5px;
            color: #3498db;
        }

        /* Custom file input styling */
        .file-input {
            display: none;
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
		  <span style="--i:9;">A</span>
		</div>
	</div>
<div class="theme-layout">
	
<?php include 'component/header.php'; ?>
	
	<section>
		<div class="page-header">
			<div class="header-inner">
			<h2>Diễn đàn Câu hỏi và Trả lời Học viện Hành Chính Quốc gia</h2>
				<p>
					Chào mừng đến với NAPA Social Network. Diễn đàn là nơi giúp các bạn sinh viên và giảng viên đặt các câu hỏi về các lĩnh vực liên quan đến các chủ đề học tập và làm việc.
			</div>
			<figure><img src="images/resources/baner-forum.png" alt=""></figure>
		</div>
	</section><!-- sub header -->
	
	<section>
		<div class="gap gray-bg">
			<div class="container">
				<div class="row merged20">
					<div class="col-lg-9">
						<div class="forum-warper">
							<div class="central-meta">
								<div class="title-block">
									<div class="row">
										<div class="col-lg-6">
											<div class="align-left">
												<h5>Tạo chủ đề và câu hỏi mới</h5>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="row">
												<div class="col-lg-7 col-md-6">
												<form method="get" action="search.php" class="search-form">
													<input type="text" name="query" placeholder="Tìm kiếm câu hỏi..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
													<button type="submit"><i class="fa fa-search"></i></button>
												</form>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4">
												<div class="select-options">
													<select class="select" name="sort">
														<option value="">Sắp xếp theo</option>
														<option value="all">Xem Tất cả</option>
														<option value="newest">Mới nhất</option>
														<option value="oldest">Cũ nhất</option>
														<option value="atoz">A đến Z</option>
													</select>
												</div>
											</div>
											<div class="col-lg-1 col-md-1 col-sm-1">
												<div class="option-list">
													<i class="fa fa-ellipsis-v"></i>
													<ul>
														<li class="active"><i class="fa fa-check"></i><a title="" href="#">Hiện công khai</a></li>
														<li><a title="" href="#">Chỉ hiện bạn bè</a></li>
														<li><a title="" href="#">Ẩn tất cả bài viết</a></li>
														<li><a title="" href="#">Tắt thông báo</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									</div>
								</div>
							</div><!-- title block -->
						</div>
						<div class="forum-form">
							<div class="central-meta">
								<!-- Form tạo chủ đề -->
								<div class="form-section">
									<h4><i class="fa fa-folder-open"></i> Tạo chủ đề mới</h4>
									<form method="post" class="c-form">
										<div>
											<label><i class="fa fa-heading"></i> Tiêu đề chủ đề</label>
											<input type="text" name="title" placeholder="Nhập tiêu đề chủ đề" required>
										</div>
										<div>
											<label><i class="fa fa-info-circle"></i> Mô tả ngắn</label>
											<textarea name="description" placeholder="Viết mô tả ngắn về chủ đề" required></textarea>
										</div>
										<div>
											<label><i class="fa fa-file-text"></i> Nội dung chi tiết</label>
											<textarea name="content" placeholder="Nhập nội dung chi tiết của chủ đề" required></textarea>
										</div>
										<div>
											<button type="submit" class="main-btn">
												<i class="fa fa-plus"></i> Tạo chủ đề mới
											</button>
										</div>
									</form>
								</div>

								<div class="form-divider">
									<span>HOẶC</span>
								</div>

								<!-- Form tạo câu hỏi -->
								<div class="form-section">
									<h4><i class="fa fa-question-circle"></i> Tạo câu hỏi mới</h4>
									<form method="post" class="c-form" enctype="multipart/form-data">
										<div>
											<label><i class="fa fa-heading"></i> Tiêu đề câu hỏi</label>
											<input type="text" name="question_title" placeholder="Nhập tiêu đề câu hỏi" required>
										</div>
										<div>
											<label><i class="fa fa-folder"></i> Chọn chủ đề</label>
											<select name="thread_id" required>
												<option value="">-- Chọn một chủ đề --</option>
												<?php foreach ($threads as $thread): ?>
													<option value="<?php echo htmlspecialchars($thread['id']); ?>">
														<?php echo htmlspecialchars($thread['title']); ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
										
										<div class="form-group">
											<label><i class="fa fa-file-text"></i> Nội dung câu hỏi</label>
											<textarea name="question_content" placeholder="Mô tả chi tiết câu hỏi của bạn" required></textarea>
										</div>

										<div class="upload-section">
										    <div class="upload-group">
										        <div class="upload-item">
										            <label for="question_image" class="upload-label">
										                <div class="upload-placeholder">
										                    <i class="fa fa-image"></i>
										                    <span>Click để tải lên hình ảnh</span>
										                    <small>Hỗ trợ: JPG, PNG, GIF (tối đa 5MB)</small>
										                </div>
										                <input type="file" id="question_image" name="question_image" 
										                       accept="image/*" class="file-input" hidden>
										            </label>
										            <div class="preview-area">
										                <div class="image-preview"></div>
										            </div>
										        </div>
										        
										        <div class="upload-item">
										            <label for="question_file" class="upload-label">
										                <div class="upload-placeholder">
										                    <i class="fa fa-file"></i>
										                    <span>Click để tải lên tệp đính kèm</span>
										                    <small>Hỗ trợ: PDF, DOC, DOCX (tối đa 10MB)</small>
										                </div>
										                <input type="file" id="question_file" name="question_file" 
										                       accept=".pdf,.doc,.docx" class="file-input" hidden>
										            </label>
										            <div class="preview-area">
										                <div class="file-info"></div>
										            </div>
										        </div>
										    </div>
										</div>

										<button type="submit" class="main-btn">
											<i class="fa fa-plus"></i> Đăng câu hỏi
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
					
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<section>
		<div class="getquot-baner purple high-opacity">
			<div class="bg-image" style="background-image:url(images/resources/animated-bg2.png)"></div>
			<span>Want to join our awesome forum and start interacting with others?</span>
			<a title="" href="#">Sign up</a>
		</div>
	</section>
	
	<?php include 'component/footer.php'; ?>
</div>
	
	
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>
	<script>
		// Preview image before upload
		document.querySelector('input[name="question_image"]').addEventListener('change', function(e) {
			const preview = this.closest('.upload-item').querySelector('.image-preview');
			const file = e.target.files[0];
			
			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
					preview.style.display = 'block';
				}
				reader.readAsDataURL(file);
			}
		});

		// Show file info
		document.querySelector('input[name="question_attachment"]').addEventListener('change', function(e) {
			const fileInfo = this.closest('.upload-item').querySelector('.file-info');
			const file = e.target.files[0];
			
			if (file) {
				const size = (file.size / 1024 / 1024).toFixed(2);
				fileInfo.innerHTML = `<i class="fa fa-file"></i> ${file.name} (${size}MB)`;
			}
		});

		// Preview image before upload
        document.getElementById('question_image').addEventListener('change', function(e) {
            const preview = this.closest('.upload-item').querySelector('.image-preview');
            const placeholder = this.closest('.upload-item').querySelector('.upload-placeholder');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        // Show file info
        document.getElementById('question_attachment').addEventListener('change', function(e) {
            const fileInfo = this.closest('.upload-item').querySelector('.file-info');
            const placeholder = this.closest('.upload-item').querySelector('.upload-placeholder');
            const file = e.target.files[0];
            
            if (file) {
                const size = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.innerHTML = `
                    <i class="fa fa-file"></i>
                    <strong>${file.name}</strong>
                    <br>
                    <small>Kích thước: ${size}MB</small>
                `;
                fileInfo.classList.add('active');
                placeholder.style.display = 'none';
            }
        });

        // Reset preview when clicking on placeholder
        document.querySelectorAll('.upload-placeholder').forEach(placeholder => {
            placeholder.addEventListener('click', function() {
                const input = this.closest('.upload-item').querySelector('.file-input');
                input.click();
            });
        });
	</script>

</body>	

</html>