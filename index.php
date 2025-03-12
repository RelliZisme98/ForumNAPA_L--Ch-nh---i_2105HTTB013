<?php
// Kết nối đến cơ sở dữ liệu
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy số lượng tài khoản người dùng
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['user_count'];

// Lấy số lượng thread đang hoạt động
$threadCountQuery = "SELECT COUNT(*) AS thread_count FROM threads";
$threadCountResult = $conn->query($threadCountQuery);
$threadCount = $threadCountResult->fetch_assoc()['thread_count'];

// Lấy số thông báo chưa đọc
$notificationQuery = "SELECT COUNT(*) AS unread_notifications FROM notifications WHERE is_read = 0";
$notificationResult = $conn->query($notificationQuery);
$unreadNotifications = $notificationResult->fetch_assoc()['unread_notifications'];

// Lấy số lượng báo cáo
$reportCountQuery = "SELECT COUNT(*) AS report_count FROM reports";
$reportCountResult = $conn->query($reportCountQuery);
$reportCount = $reportCountResult->fetch_assoc()['report_count'];

// Lấy các hoạt động mới nhất (giả định: từ bảng threads và notifications)
$latestThreadsQuery = "SELECT users.username, threads.title, threads.created_at 
                        FROM threads 
                        JOIN users ON threads.user_id = users.id 
                        ORDER BY threads.created_at DESC LIMIT 4";
$latestThreadsResult = $conn->query($latestThreadsQuery);


// Lấy số lượng tài khoản người dùng
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['user_count'];

// Lấy số lượng thread đang hoạt động
$threadCountQuery = "SELECT COUNT(*) AS thread_count FROM threads";
$threadCountResult = $conn->query($threadCountQuery);
$threadCount = $threadCountResult->fetch_assoc()['thread_count'];

// Lấy số thông báo chưa đọc
$notificationQuery = "SELECT COUNT(*) AS unread_notifications FROM notifications WHERE is_read = 0";
$notificationResult = $conn->query($notificationQuery);
$unreadNotifications = $notificationResult->fetch_assoc()['unread_notifications'];

// Lấy số lượng báo cáo
$reportCountQuery = "SELECT COUNT(*) AS report_count FROM reports";
$reportCountResult = $conn->query($reportCountQuery);
$reportCount = $reportCountResult->fetch_assoc()['report_count'];

// Lấy các hoạt động mới nhất (giả định: từ bảng threads và notifications)
$latestThreadsQuery = "SELECT users.username, threads.title, threads.created_at 
                        FROM threads 
                        JOIN users ON threads.user_id = users.id 
                        ORDER BY threads.created_at DESC LIMIT 4";
$latestThreadsResult = $conn->query($latestThreadsQuery);

// Hiển thị phần HTML với dữ liệu từ CSDL
// Hiển thị phần HTML với dữ liệu từ CSDL


// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Diễn Đàn</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
        /* Reset mặc định */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Cài đặt font chữ và màu nền */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo body bao trọn chiều cao màn hình */
        }

        /* Sidebar */
        aside {
            width: 250px;
            background-color: #0056b3;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        aside h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            margin-bottom: 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: block;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        nav ul li a:hover {
            background-color: #004494;
        }

        /* Main Content */
        main {
            flex: 1;
            margin-left: 220px;
            padding: 30px 20px;
            max-width: 1200px;
            width: 100%;
        }

        h2 {
            margin-bottom: 20px;
            color: #0056b3;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Stats Section */
        .stats {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .stats div {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 25px;
            flex: 1;
            text-align: center;
            transition: transform 0.2s ease-in-out;
            min-width: 200px;
            margin-bottom: 20px;
        }

        .stats div:hover {
            transform: translateY(-5px);
        }

        .stats div h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats div p {
            font-size: 36px;
            font-weight: bold;
            color: #0056b3;
            margin-top: 10px;
        }

        /* Latest Activities Section */
        section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #0056b3;
            font-weight: bold;
            text-align: left;
        }

        section ul {
            list-style-type: none;
        }

        section ul li {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s ease-in-out;
        }

        section ul li:hover {
            background-color: #f1f1f1;
        }

        section ul li span {
            font-weight: bold;
            color: #333;
        }

        /* Footer */
        footer {
            background-color: #0056b3;
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
            font-size: 14px;
            position: relative;
            bottom: 0;
            left: 0;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        /* CSS cho phần đăng nhập / đăng xuất */
        .user-options {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: -40px; /* Để di chuyển khớp với phần tiêu đề */
        }

        .login-btn, .logout-btn {
            text-align: center;
            padding: 1px 1px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 15px;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover, .logout-btn:hover {

            background-color: #218838;
        }

        .user-name {
            color: white;
            font-weight: bold;
            margin-right: 15px;
        }

    </style>

<aside>
        <header>
            <h1>Quản lý Diễn Đàn</h1>
            <div class="user-options">
                <!-- Kiểm tra xem người dùng đã đăng nhập hay chưa -->
                <?php if (!isset($_SESSION['admin_id'])): ?>
                    <!-- Nút đăng nhập khi chưa đăng nhập -->
                    <a href="login.php" class="login-btn">Đăng Nhập</a>
                <?php else: ?>
                    <!-- Hiển thị thông tin người dùng khi đã đăng nhập -->
                    <span class="user-name">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="logout-btn">Đăng Xuất</a>
                <?php endif; ?>
            </div>
        </header>

        <nav>
            <ul>
                <li><a href="create_admin.php">Tạo tài khoản Admin</a></li>
                <li><a href="manage_accounts.php">Quản lý Tài Khoản</a></li>
                <li><a href="admin_account_management.php">Quản lý Tài Khoản ADMIN</a></li>
                <li><a href="manage_newfeeds.php">Quản lý Newfeeds</a></li>
                <li><a href="manage_postimages.php">Quản lý Đăng Ảnh</a></li>
                <li><a href="manage_favorite_movies.php">Quản lý phim yêu thích</a></li>
                <li><a href="manage_friends.php">Quản lý Bạn Bè</a></li>
                <li><a href="manage_stories.php">Quản lý Story</a></li>
                <li><a href="manage_videos.php">Quản lý Videos</a></li>
                <li><a href="manage_photos.php">Quản lý Photos</a></li>
                <li><a href="manage_likes.php">Quản lý số lượng Like</a></li>
                <li><a href="manage_hobbies.php">Quản lý sở thích</a></li>
                <li><a href="manage_twitter_feeds.php">Quản lý Twitter</a></li>
                <li><a href="manage_work_experience.php">Quản lý kinh nghiệm làm việc</a></li>
                <li><a href="manage_special_events.php">Quản lý Sự kiện đặc biệt</a></li>
                <li><a href="manage_social_networks.php">Quản lý mạng xã hội</a></li>
                <li><a href="manage_recent_links.php">Quản lý link</a></li>
                <li><a href="manage_profile_intro.php">Quản lý Profile Intro</a></li>
                <li><a href="manage_threads.php">Quản lý Thread</a></li>
                <li><a href="manage_questions.php">Quản lý Câu hỏi và Trả lời</a></li>
                <li><a href="manage_notifications.php">Quản lý Thông Báo</a></li>
                <li><a href="manage_faq.php">Quản lý FAQ</a></li>
                <li><a href="manage_community.php">Quản lý Community</a></li>
                <li><a href="manage_follow.php">Quản lý Follow</a></li>
                <li><a href="manage_roles.php">Phân Quyền Tài Khoản Admin</a></li>
                <li><a href="manage_reports.php">Quản lý Report</a></li>
                <li><a href="manage_birthdays.php">Quản lý Sinh nhật</a></li>
                <li><a href="manage_comments.php">Quản lý bình luận</a></li>
                <li><a href="manage_contact.php">Quản lý Contact</a></li>
                <li><a href="manage_header_footer.php">Quản lý Header & Footer</a></li>
            </ul>
        </nav>
    </aside>

 


<main>
    <h2>Thống kê Tổng Quan</h2>
    <div class="stats">
        <div>
            <h3>Tài Khoản Người Dùng</h3>
            <p><?php echo $userCount; ?></p>
        </div>
        <div>
            <h3>Thread Đang Hoạt Động</h3>
            <p><?php echo $threadCount; ?></p>
        </div>
        <div>
            <h3>Thông Báo Chưa Đọc</h3>
            <p><?php echo $unreadNotifications; ?></p>
        </div>
        <div>
            <h3>Số Report</h3>
            <p><?php echo $reportCount; ?></p>
        </div>
    </div>

    <section>
        <h2>Các Hoạt Động Mới Nhất</h2>
        <ul>
            <?php while ($row = $latestThreadsResult->fetch_assoc()): ?>
                <li>
                    <span><?php echo htmlspecialchars($row['username']); ?></span> 
                    vừa tạo thread mới: "<?php echo htmlspecialchars($row['title']); ?>" vào 
                    <?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?>.
                </li>
            <?php endwhile; ?>
        </ul>
    </section>
</main>


    <footer>
        <p>&copy; 2024 Lê Chính Đại Diễn Đàn Câu hỏi và Trả lời cho Sinh viên Học viện Hành chính Quốc gia. Tất cả quyền được bảo lưu.</p>
    </footer>
</body>
</html>
