<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Không hợp lệ.");
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách bạn bè từ cơ sở dữ liệu và trạng thái của họ
$sql = "SELECT f.friend_id, f.mutual_friends_count, u.last_activity, u.profile_picture
        FROM friends f
        JOIN users u ON f.friend_id = u.id
        WHERE f.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $friend_id = $row['friend_id'];
        $mutual_friends_count = $row['mutual_friends_count'];
        $last_activity = strtotime($row['last_activity']);
        $current_time = time();
        $profile_picture = $row['profile_picture'];

        // Tính toán trạng thái của bạn bè
        if (($current_time - $last_activity) < 300) { // 5 phút
            $status = 'f-online';
        } elseif (($current_time - $last_activity) < 900) { // 15 phút
            $status = 'f-away';
        } else {
            $status = 'f-offline';
        }

        // Hiển thị bạn bè với trạng thái tương ứng
        echo "
        <li>
            <a href=\"javascript:void(0);\" onclick=\"openChat($friend_id)\">
                <div class='author-thmb'>
                    <img src='$profile_picture' alt=''>
                    <span class='status $status'></span>
                </div>
            </a>
            <div>Bạn chung: $mutual_friends_count</div>
        </li>";
    }
} else {
    echo "<p>Bạn chưa có bạn bè nào.</p>";
}
?>
