<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý người dùng trực tuyến
$is_logged_in = isset($_SESSION['username']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
$ip_address = $_SERVER['REMOTE_ADDR'];
$timeout = 5 * 60;
$expired_time = time() - $timeout;

// Cập nhật người dùng trực tuyến
$conn->query("DELETE FROM active_users WHERE UNIX_TIMESTAMP(last_activity) < $expired_time");
$stmt = $conn->prepare("SELECT id FROM active_users WHERE session_id = ?");
$stmt->bind_param("s", $session_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt_update = $conn->prepare("UPDATE active_users SET last_activity = CURRENT_TIMESTAMP WHERE session_id = ?");
    $stmt_update->bind_param("s", $session_id);
    $stmt_update->execute();
} elseif ($is_logged_in) {
    $stmt_insert = $conn->prepare("INSERT INTO active_users (user_id, session_id, ip_address) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("iss", $user_id, $session_id, $ip_address);
    $stmt_insert->execute();
}

// Truy vấn thống kê cơ bản
$queries = [
    'active_users' => "SELECT COUNT(*) AS count FROM active_users",
    'new_posts_today' => "SELECT COUNT(*) AS count FROM questions WHERE DATE(created_at) = CURDATE()",
    'total_members' => "SELECT COUNT(*) AS count FROM users",
    'total_topics' => "SELECT COUNT(*) AS count FROM threads"
];

$stats = [];
foreach ($queries as $key => $query) {
    $result = $conn->query($query);
    $stats[$key] = $result->fetch_assoc()['count'];
}

// Truy vấn chính
$latest_questions_query = "
    SELECT q.*, t.title AS thread_title, u.username, u.profile_picture,
           COUNT(a.id) as answer_count 
    FROM questions q
    JOIN users u ON q.user_id = u.id
    JOIN threads t ON q.thread_id = t.id
    LEFT JOIN answers a ON q.id = a.question_id
    WHERE q.status = 1
    GROUP BY q.id, q.created_at
    ORDER BY q.created_at DESC 
    LIMIT 5";
$latest_questions = $conn->query($latest_questions_query);

// Truy vấn câu hỏi nổi bật
$popular_questions_query = "
    SELECT q.*, u.username 
    FROM questions q
    JOIN users u ON q.user_id = u.id
    ORDER BY q.views DESC 
    LIMIT 5";
$popular_questions = $conn->query($popular_questions_query);

// Truy vấn chủ đề mới nhất
$latest_threads_query = "
    SELECT t.*, COUNT(q.id) as question_count 
    FROM threads t
    LEFT JOIN questions q ON t.id = q.thread_id
    GROUP BY t.id
    ORDER BY t.created_at DESC 
    LIMIT 5";
$latest_threads = $conn->query($latest_threads_query);

// Truy vấn thành viên tiêu biểu
$top_members_query = "
    SELECT u.id, u.username, COUNT(q.id) AS total_questions
    FROM users u
    JOIN questions q ON u.id = q.user_id
    GROUP BY u.id
    ORDER BY total_questions DESC
    LIMIT 5";
$top_members = $conn->query($top_members_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAPA Forum</title>
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <!-- Hiển thị số người dùng đang trực tuyến -->
    <!-- <div class="central-meta">
        <div class="user-count">
            Hiện tại có  người đang trực tuyến.
        </div>
    </div> -->
    <div class="wavy-wraper">
        <div class="wavy">
            <span style="--i:1;">F</span>
            <span style="--i:2;">O</span>
            <span style="--i:3;">R</span>
            <span style="--i:4;">U</span>
            <span style="--i:5;">M</span>
            <span style="--i:6;">-</span>
            <span style="--i:7;">N</span>
            <span style="--i:8;">A</span>
            <span style="--i:9;">P</span>
            <span style="--i:10;">A</span>
        </div>
    </div>

    <div class="theme-layout">
        <?php include 'component/header.php'; ?>

        <!-- Banner Section -->
        <section>
            <div class="page-header">
                <div class="header-inner">
                    <h2>Diễn đàn NAPA - Nơi Chia Sẻ Kiến Thức</h2>
                    <p>Diễn đàn trao đổi học tập dành cho sinh viên và giảng viên Học viện Hành chính Quốc gia</p>
                </div>
                <figure><img src="images/resources/baner-forum.png" alt=""></figure>
            </div>
        </section>

        <!-- Main Content Section -->
        <section>
            <div class="gap gray-bg">
                <div class="container">
                    <div class="row merged20">
                        <div class="col-lg-9">
                            <!-- Tạo chủ đề mới -->
                            <div class="forum-warper">
                                <div class="central-meta">
                                    <div class="title-block">
                                        <h4>Tạo mới</h4>
                                        <a href="create_topic_forum.php" class="btn-create-new">
                                            <i class="fa fa-plus"></i> Tạo chủ đề hoặc câu hỏi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Câu hỏi mới nhất -->
                            <div class="central-meta">
                                <div class="forum-open">
                                    <h5><i class="fa fa-star"></i> Câu hỏi mới nhất</h5>
                                    <p class="view-category">
                                        <a href="forum_category.php" class="btn-view-category">
                                            <i class="fa fa-folder-open"></i> Xem chủ đề
                                        </a>
                                    </p>
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Tác giả</th>
                                                <th>Ngày đăng</th>
                                                <th>Chủ đề</th>
                                                <th>Bài viết</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($question = $latest_questions->fetch_assoc()) : ?>
                                            <tr>
                                                <td class="topic-data">
                                                    <img src="<?php echo htmlspecialchars($question['profile_picture']); ?>" alt="">
                                                    <span><?php echo htmlspecialchars($question['username']); ?></span>
                                                    <em>Người tham gia</em>
                                                </td>
                                                <td class="date-n-reply">
                                                    <span><?php echo date("d/m/Y H:i", strtotime($question['created_at'])); ?></span>
                                                    <a href="view_question.php?id=<?php echo $question['id']; ?>">Trả lời</a>
                                                </td>
                                                <td class="question-topic">
                                                    <span><?php echo htmlspecialchars($question['thread_title']); ?></span>
                                                </td>
                                                <td class="topic-detail">
                                                    <p>
                                                        <a href="view_question.php?id=<?php echo $question['id']; ?>">
                                                            <?php echo htmlspecialchars($question['title']); ?>
                                                        </a>
                                                    </p>
                                                    <?php if ($question['image_url']): ?>
                                                        <div class="question-media">
                                                            <?php if (strpos($question['image_url'], '.pdf') !== false): ?>
                                                                <i class="fa fa-file-pdf"></i> <a href="<?php echo $question['image_url']; ?>" target="_blank">Xem PDF</a>
                                                            <?php elseif (strpos($question['image_url'], '.doc') !== false): ?>
                                                                <i class="fa fa-file-word"></i> <a href="<?php echo $question['image_url']; ?>" target="_blank">Tải tài liệu</a>
                                                            <?php else: ?>
                                                                <img src="<?php echo $question['image_url']; ?>" alt="Question image" style="max-width:200px;">
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <p>Lượt xem: <?php echo number_format($question['views']); ?> | 
                                                       Trả lời: <?php echo $question['answer_count']; ?></p>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tất cả câu hỏi -->
                            <div class="central-meta">
                                <div class="forum-open">
                                    <!-- Add topics list here -->
                                </div>
                            </div>

                            <!-- Thành viên tiêu biểu -->
                            <!-- Add top members content here -->

                            <!-- Thành viên mới nhất -->
                            <!-- Add newest member content here -->

                            <!-- Thông báo chưa đọc -->
                            <!-- Add notifications content here -->
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-3">
                            <aside class="sidebar static">
                                <!-- Forum Statistics -->
                                <div class="widget">
                                    <h4 class="widget-title">Thống kê</h4>
                                    <ul class="forum-stats">
                                        <li><i class="fa fa-users"></i> Đang online: <?php echo $stats['active_users']; ?></li>
                                        <li><i class="fa fa-file-text"></i> Bài mới hôm nay: <?php echo $stats['new_posts_today']; ?></li>
                                        <li><i class="fa fa-user"></i> Tổng thành viên: <?php echo $stats['total_members']; ?></li>
                                        <li><i class="fa fa-folder"></i> Tổng chủ đề: <?php echo $stats['total_topics']; ?></li>
                                    </ul>
                                </div>

                                <!-- Recent Topics Widget -->
                                <div class="widget">
                                    <h4 class="widget-title">Chủ đề mới nhất</h4>
                                    <ul class="recent-topics">
                                        <?php while ($thread = $latest_threads->fetch_assoc()): ?>
                                            <li>
                                                <a href="forum_category.php?thread_id=<?php echo $thread['id']; ?>">
                                                    <?php echo htmlspecialchars($thread['title']); ?>
                                                </a>
                                                <span>Ngày tạo: <?php echo date("d/m/Y", strtotime($thread['created_at'])); ?></span>
                                                <span>Số câu hỏi: <?php echo $thread['question_count']; ?></span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>

                                <!-- Popular Questions Widget -->
                                <div class="widget">
                                    <h4 class="widget-title">Câu hỏi nổi bật</h4>
                                    <ul class="feature-topics">
                                        <?php while ($question = $popular_questions->fetch_assoc()): ?>
                                            <li>
                                                <i class="fa fa-star"></i>
                                                <a href="view_question.php?id=<?php echo $question['id']; ?>">
                                                    <?php echo htmlspecialchars($question['title']); ?>
                                                </a>
                                                <span><?php echo date("d/m/Y", strtotime($question['created_at'])); ?> | 
                                                      Lượt xem: <?php echo number_format($question['views']); ?></span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>

                                <!-- Thành viên tiêu biểu -->
                                <div class="widget">
                                    <h4 class="widget-title">Thành viên tiêu biểu</h4>
                                    <ul class="top-members">
                                        <?php while ($member = $top_members->fetch_assoc()): ?>
                                            <li>
                                                <i class="fa fa-user"></i>
                                                <a href="profile.php?id=<?php echo $member['id']; ?>">
                                                    <?php echo htmlspecialchars($member['username']); ?>
                                                </a>
                                                <span><?php echo $member['total_questions']; ?> câu hỏi</span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </aside>
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