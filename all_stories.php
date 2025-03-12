<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xem trang này.";
    exit; // Dừng thực thi nếu chưa đăng nhập
}

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID từ phiên
$user_id = $_SESSION['user_id']; 

// Lấy tất cả các stories
$sql = "SELECT s.image_url, u.username, u.profile_picture
        FROM stories s
        JOIN users u ON s.user_id = u.id
        ORDER BY s.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAPA Social Network</title>
    <style>
        /* CSS Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .central-meta {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .widget-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }
        .story-postbox {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        .story-container {
            display: flex;
            transition: transform 0.5s ease;
            width: 100%;
        }
        .story-box {
            position: relative;
            min-width: 120px; /* Độ rộng tối thiểu cho mỗi story */
            margin: 0 5px;
            text-align: center;
        }
        .story-box img {
            width: 100%;
            border-radius: 10px;
            max-height: 200px; /* Giới hạn chiều cao của ảnh */
            object-fit: cover; /* Cắt ảnh để giữ tỷ lệ */
            border: 3px solid #fff; /* Viền trắng quanh ảnh */
        }
        .story-name {
            margin-top: 5px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        .story-thumb {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
            background-color: #fff;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .story-thumb img {
            width: 100%;
            height: auto;
            border-radius: 50%; /* Hình tròn cho ảnh đại diện */
        }
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
            font-size: 24px;
        }
        .arrow-left {
            left: 10px;
        }
        .arrow-right {
            right: 10px;
        }
        /* Thêm CSS cho biểu tượng cảm xúc và gửi tin nhắn */
        .action-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }
        .action-buttons button {
            background-color: #3b5998;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .action-buttons button:hover {
            background-color: #2e4688;
        }
        .emojis {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .emoji {
            font-size: 24px;
            margin: 0 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="central-meta">
    <h4 class="widget-title">Tất cả Tin</h4>
    <div class="story-postbox">
        <div class="story-container" id="storyContainer">
            <?php while ($story = $result->fetch_assoc()): ?>
                <div class="story-box">
                    <img src="uploads/stories/<?php echo htmlspecialchars($story['image_url']); ?>" alt="">
                    <div class="story-name"><?php echo htmlspecialchars($story['username']); ?></div>
                    <div class="story-thumb" data-toggle="tooltip" title="<?php echo htmlspecialchars($story['username']); ?>">
                        <img src="uploads/profile_pictures/<?php echo htmlspecialchars($story['profile_picture']); ?>" alt="">
                    </div>
                    <div class="action-buttons">
                        <button>Gửi tin nhắn</button>
                    </div>
                    <div class="emojis">
                        <span class="emoji" title="Thích">😍</span>
                        <span class="emoji" title="Buồn">😢</span>
                        <span class="emoji" title="Cười">😂</span>
                        <span class="emoji" title="Yêu thích">❤️</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <button class="arrow arrow-left" id="prevBtn">&lt;</button>
        <button class="arrow arrow-right" id="nextBtn">&gt;</button>
    </div>
</div>

<script>
    let currentIndex = 0;
    const stories = document.querySelectorAll('.story-box');
    const totalStories = stories.length;

    document.getElementById('nextBtn').onclick = function() {
        if (currentIndex < totalStories - 1) {
            currentIndex++;
            updateCarousel();
        }
    };

    document.getElementById('prevBtn').onclick = function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    };

    function updateCarousel() {
        const storyContainer = document.getElementById('storyContainer');
        storyContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
    }
</script>

</body>
</html>
