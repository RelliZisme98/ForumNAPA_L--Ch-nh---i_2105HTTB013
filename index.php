<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng có đăng nhập không
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Lấy ID người dùng từ session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
// Truy vấn sự kiện sinh nhật
$sql = "SELECT * FROM birthdays LIMIT 1";
$result = $conn->query($sql);

// Tạo biến lưu trữ dữ liệu sinh nhật
$birthday_data = [];

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Phân tích ngày sinh nhật
    $birthday_date = new DateTime($row['birthday_date']);
    $day = $birthday_date->format('d');
    $month = $birthday_date->format('F');  // Lấy tên tháng

    // Lưu dữ liệu vào mảng
    $birthday_data = [
        'profile_image' => $row['profile_image'],
        'background_image' => $row['background_image'],
        'event_name' => $row['event_name'],
        'user_name' => $row['user_name'],
        'birthday_message' => $row['birthday_message'],
        'day' => $day,
        'month' => $month
    ];
} else {
    // Nếu không có dữ liệu trả về từ truy vấn
    $birthday_data = [
        'profile_image' => 'images/default_profile.png',
        'background_image' => 'images/default_background.png',
        'event_name' => 'Birthday Event',
        'user_name' => 'User Name',
        'birthday_message' => 'Happy Birthday!',
        'day' => '01',
        'month' => 'January'
    ];
}
// Truy vấn lấy thông tin cơ bản của người dùng

if ($user_id > 0) {
    // Truy vấn lấy thông tin cơ bản của người dùng
    $sql_user = "SELECT username, profile_picture FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param('i', $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    // Kiểm tra nếu người dùng tồn tại
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        
        // Kiểm tra xem người dùng có ảnh đại diện không, nếu không thì sử dụng ảnh mặc định
        $profile_picture = !empty($user['profile_picture']) ? '/uploads/profile_pictures/' . $user['profile_picture'] : '/images/resources/author.jpg';
        $username = $user['username'];
    } else {
        echo "Không tìm thấy người dùng.";
        exit;
    }

    $stmt_user->close();
} else {
    echo "Không tìm thấy session của người dùng.";
    exit();
}

// Truy vấn lấy số lượng tin nhắn chưa đọc
$sql_messages = "SELECT COUNT(*) AS total_messages FROM messages WHERE receiver_id = ? AND status = 'unread'";
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param('i', $user_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();
$total_messages = $result_messages->fetch_assoc()['total_messages'];

// Truy vấn lấy số lượng thông báo chưa đọc
$sql_notifications = "SELECT COUNT(*) AS total_notifications FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_notifications = $conn->prepare($sql_notifications);
$stmt_notifications->bind_param('i', $user_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
$total_notifications = $result_notifications->fetch_assoc()['total_notifications'];

// Truy vấn lấy số lượt thích
$sql_likes = "SELECT COUNT(*) AS total_likes FROM likes WHERE user_id = ?";
$stmt_likes = $conn->prepare($sql_likes);
$stmt_likes->bind_param('i', $user_id);
$stmt_likes->execute();
$result_likes = $stmt_likes->get_result();
$total_likes = $result_likes->fetch_assoc()['total_likes'];

// Lấy số lượng likes mới trong tuần
$sql_new_likes = "SELECT COUNT(*) AS new_likes FROM likes WHERE liked_at >= (NOW() - INTERVAL 1 WEEK)";
$result_new_likes = $conn->query($sql_new_likes);
$new_likes = $result_new_likes->fetch_assoc()['new_likes'];

// Truy vấn lấy số lượt xem
$sql_views = "SELECT COUNT(*) AS total_views FROM views WHERE user_id = ?";
$stmt_views = $conn->prepare($sql_views);
$stmt_views->bind_param('i', $user_id);
$stmt_views->execute();
$result_views = $stmt_views->get_result();
$total_views = $result_views->fetch_assoc()['total_views'];

// Lấy số lượng views mới trong tuần
$sql_new_views = "SELECT COUNT(*) AS new_views FROM views WHERE viewed_at >= (NOW() - INTERVAL 1 WEEK)";
$result_new_views = $conn->query($sql_new_views);
$new_views = $result_new_views->fetch_assoc()['new_views'];
        // Truy vấn dữ liệu từ bảng profile_intro
$sql_intro = "SELECT about, fav_tv_show, favourit_music FROM profile_intro WHERE user_id = ?";
$stmt_intro = $conn->prepare($sql_intro);
$stmt_intro->bind_param("i", $user_id);
$stmt_intro->execute();
$result_intro = $stmt_intro->get_result();

// Lấy dữ liệu từ kết quả
if ($result_intro->num_rows > 0) {
$profile_intro = $result_intro->fetch_assoc();
} else {
// Nếu không có thông tin, tạo thông tin mặc định
$profile_intro = [
            'about' => 'Chưa có thông tin.',
            'fav_tv_show' => 'Chưa có thông tin.',
            'favourit_music' => 'Chưa có thông tin.'
 ];
}

// Truy vấn lấy sự kiện từ bảng 'events'
$sql_events = "SELECT event_name, event_link, event_icon, event_class FROM specialevents LIMIT 4"; // Lấy tối đa 4 sự kiện
$result_events = $conn->query($sql_events);

$explore_events = [];
if ($result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $explore_events[] = [
            'event_name' => htmlspecialchars($row['event_name']),
            'event_link' => htmlspecialchars($row['event_link']),
            'event_icon' => htmlspecialchars($row['event_icon']),
            'event_class' => htmlspecialchars($row['event_class']),
        ];
    }
} else {
    $explore_events = null;
}
// Truy vấn dữ liệu từ bảng recent_links
$sql_links = "SELECT title, image, url, created_at FROM recent_links ORDER BY created_at DESC LIMIT 3";
$result_links = $conn->query($sql_links);

// Mảng lưu các liên kết
$recent_links = [];

// Lấy dữ liệu từ CSDL
if ($result_links->num_rows > 0) {
    while ($link = $result_links->fetch_assoc()) {
        // Chuyển đổi thời gian tạo thành định dạng dễ đọc
        $created_at = strtotime($link['created_at']);
        $time_ago = time() - $created_at;

        // Chuyển đổi thời gian thành chuỗi mô tả
        if ($time_ago >= 31536000) {
            $time_ago_str = floor($time_ago / 31536000) . ' years ago';
        } elseif ($time_ago >= 2592000) {
            $time_ago_str = floor($time_ago / 2592000) . ' months ago';
        } elseif ($time_ago >= 604800) {
            $time_ago_str = floor($time_ago / 604800) . ' weeks ago';
        } elseif ($time_ago >= 86400) {
            $time_ago_str = floor($time_ago / 86400) . ' days ago';
        } elseif ($time_ago >= 3600) {
            $time_ago_str = floor($time_ago / 3600) . ' hours ago';
        } else {
            $time_ago_str = floor($time_ago / 60) . ' minutes ago';
        }

        // Thêm liên kết vào mảng
        $recent_links[] = [
            'title' => $link['title'],
            'image' => $link['image'],
            'url' => $link['url'],
            'time_ago' => $time_ago_str
        ];
    }
} else {
    $recent_links[] = null; // Không có liên kết
}
$sql_activity = "SELECT activity_type, activity_content, activity_date, reference_link FROM activities ORDER BY activity_date DESC LIMIT 3";
$result_activity = $conn->query($sql_activity);
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Mảng để lưu kết quả
    $string = [];

    // Tính số năm, tháng, ngày, giờ, phút, giây
    if ($diff->y) {
        $string[] = $diff->y . ' năm';
    }
    if ($diff->m) {
        $string[] = $diff->m . ' tháng';
    }
    if ($diff->d >= 7) {
        // Tính số tuần
        $weeks = floor($diff->d / 7);
        $string[] = $weeks . ' tuần';
        $diff->d -= $weeks * 7; // Giảm số ngày sau khi đã tính tuần
    }
    if ($diff->d) {
        $string[] = $diff->d . ' ngày';
    }
    if ($diff->h) {
        $string[] = $diff->h . ' giờ';
    }
    if ($diff->i) {
        $string[] = $diff->i . ' phút';
    }
    if ($diff->s) {
        $string[] = $diff->s . ' giây';
    }

    // Nếu không có kết quả nào, trả về "Vừa xong"
    if (empty($string)) {
        return 'Vừa xong';
    }

    // Nếu không muốn hiển thị tất cả các đơn vị thời gian, chỉ hiển thị đơn vị đầu tiên
    if (!$full) {
        return $string[0] . ' trước';
    }

    return implode(', ', $string) . ' trước';
}


// Truy vấn lấy dữ liệu "tweets" từ bảng
$sql_tweets = "SELECT username, twitter_handle, message, timestamp FROM twitter_feed ORDER BY timestamp DESC LIMIT 3";
$result_tweets = $conn->query($sql_tweets);

// Kiểm tra nếu có dữ liệu
if ($result_tweets->num_rows > 0) {
    $tweets = $result_tweets->fetch_all(MYSQLI_ASSOC);
} else {
    $tweets = [];
}


// Truy vấn danh sách bạn bè
$sql_friends = "SELECT u.username, u.profile_picture, f.mutual_friends_count, f.status_add
                FROM friend f 
                JOIN users u ON f.friend_id = u.id 
                WHERE f.user_id = ? AND f.status_add = 'accepted'";
$stmt_friends = $conn->prepare($sql_friends);
$stmt_friends->bind_param('i', $user_id);
$stmt_friends->execute();
$result_friends = $stmt_friends->get_result();

// Truy vấn danh sách người theo dõi
$sql_followers = "SELECT u.username, u.profile_picture
                  FROM follower f
                  JOIN users u ON f.follower_id = u.id
                  WHERE f.user_id = ?";
$stmt_followers = $conn->prepare($sql_followers);
$stmt_followers->bind_param('i', $user_id);
$stmt_followers->execute();
$result_followers = $stmt_followers->get_result();





// Lấy ID của bài đăng vừa được chèn


// Xử lý các file đính kèm (nếu có)
// Kiểm tra tệp đính kèm và xử lý từng tệp

// Kiểm tra nếu biểu mẫu được gửi bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $user_id = $_SESSION['user_id']; // Lấy từ phiên đăng nhập hoặc nguồn hợp lệ

    // Chèn nội dung bài đăng vào bảng Newfeeds
    $sql = "INSERT INTO Newfeeds (user_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $content);
    $stmt->execute();

    // Lấy ID bài đăng vừa chèn
    $post_id = $stmt->insert_id;
    $stmt->close();

    // Kiểm tra tệp đính kèm và xử lý từng tệp
    if (!empty($_FILES['attachments']['name'][0])) {
        foreach ($_FILES['attachments']['name'] as $index => $name) {
            $file_tmp = $_FILES['attachments']['tmp_name'][$index];
            $file_name = uniqid() . '_' . basename($name);
            $upload_dir = 'uploads/';
            $upload_file = $upload_dir . $file_name;

            // Di chuyển tệp và lưu đường dẫn vào CSDL
            if (move_uploaded_file($file_tmp, $upload_file)) {
                $sql_image = "INSERT INTO PostImages (post_id, image_url) VALUES (?, ?)";
                $stmt_image = $conn->prepare($sql_image);
                $stmt_image->bind_param('is', $post_id, $upload_file); // Lưu đường dẫn đầy đủ
                $stmt_image->execute();
                $stmt_image->close();
            }
        }
    }

    echo "<script>alert('Đăng tin thành công!');</script>";
}




// Lấy danh sách các stories
$sql_stories = "
    SELECT Stories.story_id, Stories.image_url, Stories.created_at, Stories.expires_at, 
           Users.username, Users.profile_picture 
    FROM Stories 
    INNER JOIN Users ON Stories.user_id = Users.id
    WHERE Stories.expires_at > NOW()
    ORDER BY Stories.created_at DESC
"; // giới hạn hiển thị 6 stories
$result = $conn->query($sql_stories);
if (!empty($_FILES['story_image']['name'])) {
    $user_id = $_SESSION['user_id']; // Giả sử bạn có user_id từ session
    $file_tmp = $_FILES['story_image']['tmp_name'];
    $file_name = uniqid() . '_' . basename($_FILES['story_image']['name']);
    $upload_dir = 'uploads/stories/';
    $upload_file = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $upload_file)) {
        $sql = "INSERT INTO stories (user_id, image_url, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $user_id, $file_name);
        $stmt->execute();
    }
}



// Giới hạn số bài viết hiển thị (ví dụ: 5 bài)
$limit = 10;

// Truy vấn lấy dữ liệu từ bảng Newfeeds và users
$sql_posts = "
    SELECT Newfeeds.*, users.username, users.profile_picture
    FROM Newfeeds
    JOIN users ON Newfeeds.user_id = users.id
    LIMIT $limit
";
$result_posts = $conn->query($sql_posts);

$posts = [];
if ($result_posts->num_rows > 0) {
    // Duyệt qua từng bài viết và lấy dữ liệu
    while($post = $result_posts->fetch_assoc()) {
        // Lấy tất cả bình luận tương ứng với post_id
        $post_id = $post['post_id'];
        $sql_comments = "SELECT * FROM Comments WHERE post_id = $post_id";
        $result_comments = $conn->query($sql_comments);
        
        $comments = [];
        if ($result_comments->num_rows > 0) {
            while ($comment = $result_comments->fetch_assoc()) {
                $comments[] = $comment;
            }
        }
        $post['comments'] = $comments;
        $posts[] = $post;
    }
}

// Đóng kết nối

$stmt_intro->close();
$stmt_messages->close();
$stmt_notifications->close();
$stmt_likes->close();
$stmt_views->close();
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
	<link rel="stylesheet" href="css/weather-icons.min.css">
	<link rel="stylesheet" href="css/toast-notification.css">
	<link rel="stylesheet" href="css/page-tour.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">

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
	
	<div class="postoverlay"></div>
	
	
	<!-- Header -->
	<?php include 'component/header.php'; ?>
	<!-- END HEADER	 -->

	<!-- SIDEBAR RIGHT -->
    <?php include 'component/sidebarright.php'; ?>
	<!-- END SIDEBAR RIGHT -->

	<!-- SIDEBAR LEFT -->
    <?php include 'component/sidebarleft.php'; ?>
		<!-- END SIDEBAR LEFT -->
	<section>
		<div class="gap2 gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="row merged20" id="page-contents">
							<div class="col-lg-3">
								<aside class="sidebar static left">
								<div class="widget">
  <div class="weather-widget low-opacity bluesh">
    <div class="bg-image" style="background-image: url(images/resources/weather.jpg)"></div>
    <span class="refresh-content"><i class="fa fa-refresh"></i></span>
    <div class="weather-week">
      <div class="icon sun-shower">
        <div class="cloud"></div>
        <div class="sun">
          <div class="rays"></div>
        </div>
        <div class="rain"></div>
      </div>
    </div>
    <div class="weather-infos">
      <span class="weather-tem">--</span>
      <h3>Loading weather...<i>Hanoi, Vietnam</i></h3>
      <div class="weather-date skyblue-bg">
        <span>---<strong>--</strong></span>
      </div>
    </div>
    <div class="monthly-weather">
      <ul>
        <li>
          <span>Sun</span>
          <a href="#" title=""><i class="wi wi-day-sunny"></i></a>
          <em>--°</em>
        </li>
        <li>
          <span>Mon</span>
          <a href="#" title=""><i class="wi wi-day-cloudy"></i></a>
          <em>--°</em>
        </li>
        <!-- Thêm các ngày khác... -->
      </ul>
    </div>
  </div>
</div>

<script>
  const apiKey = 'da9f770c8cb4fa4c253c1aa2523dfbf1'; // Thay thế bằng API key từ OpenWeatherMap của bạn
  const city = 'Hanoi';
  const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

  async function getWeather() {
    try {
      const response = await fetch(apiUrl);
      const data = await response.json();

      // Lấy dữ liệu từ API
      const temp = Math.round(data.main.temp); // Nhiệt độ hiện tại
      const description = data.weather[0].description; // Mô tả thời tiết
      const date = new Date();
      const day = date.getDate();
      const month = date.toLocaleString('default', { month: 'short' });

      // Cập nhật dữ liệu lên HTML
      document.querySelector('.weather-tem').textContent = temp;
      document.querySelector('.weather-infos h3').innerHTML = `${description}<i>Hanoi, Vietnam</i>`;
      document.querySelector('.weather-date span').innerHTML = `${month.toUpperCase()}<strong>${day}</strong>`;
    } catch (error) {
      console.error('Error fetching weather data:', error);
    }
  }

  // Gọi hàm khi tải trang
  window.onload = getWeather;
</script>

																		
																		
									<?php if (!empty($birthday_data)): ?>
									<div class="widget whitish low-opacity">
										<div style="background-image: url('<?php echo $birthday_data['background_image']; ?>')" class="bg-image"></div>
										<div class="dob-head">
											<img src="<?php echo $birthday_data['profile_image']; ?>" alt="">
											<span><?php echo $birthday_data['event_name']; ?></span>
											<div class="dob">
												<i><?php echo $birthday_data['day']; ?></i>
												<span><?php echo $birthday_data['month']; ?></span>
											</div>
										</div>
										<div class="dob-meta">
											<figure><img src="images/resources/dob-cake.gif" alt=""></figure>
											<h6><a href="#" title=""><?php echo $birthday_data['user_name']; ?></a> <?php echo $birthday_data['event_name']; ?></h6>
											<p><?php echo $birthday_data['birthday_message']; ?></p>
										</div>
									</div><!-- birthday widget -->
									<?php else: ?>
										<p>Không có dữ liệu sinh nhật để hiển thị.</p>
									<?php endif; ?>
									<div class="widget">
										<h4 class="widget-title">Bảng tin Twitter</h4>
										<ul class="twiter-feed">
											<?php foreach ($tweets as $tweet): ?>
											<li>
												<i class="fa fa-twitter"></i>
												<span>
													<i><?php echo htmlspecialchars($tweet['username']); ?></i>
													@<?php echo htmlspecialchars($tweet['twitter_handle']); ?>
												</span>
												<p>
													<?php echo htmlspecialchars($tweet['message']); ?>
													<a href="#" title="">#laptrinhphp</a>
												</p>
												<em><?php echo htmlspecialchars($tweet['timestamp']); ?></em>
											</li>
											<?php endforeach; ?>
										</ul>
									</div><!-- twitter feed-->
									<script>
									// Array of image sources
									let bannerImages = [
										"images/anhbanner1.jpeg",
										"images/anhbanner2.jpeg",
										"images/anhbanner3.jpeg",
										"images/anhbanner4.jpg",
										"images/anhbanner5.png",
										"images/anhbanner6.png",
										"images/anhbanner7.jpeg",
										"images/anhbanner8.jpg",
										"images/anhbanner9.jpg",
										"images/anhbanner10.png",
										"images/anhbanner11.jpg",
										"images/anhbanner12.jpg"
									];

									let currentIndex = 0; // Start with the first image

									// Function to change the image
									function changeBannerImage() {
										currentIndex++;  // Move to the next image
										if (currentIndex >= bannerImages.length) {
											currentIndex = 0;  // Loop back to the first image if the end is reached
										}
										document.getElementById("bannerImage").src = bannerImages[currentIndex];  // Update image source
									}

									// Set interval to change image every 4 seconds (4000 milliseconds)
									setInterval(changeBannerImage, 3000);
									</script>
									<div class="advertisment-box">
										<h4 class="">advertisment</h4>
										<figure>
											<a href="https://www1.napa.vn/" target="_blank" title="Advertisment"> <img id="bannerImage" src="images/anhbanner1.jpeg" alt="Quảng cáo">  </a>
										</figure>
									</div><!-- advertisment box -->
									<div class="widget">
										<h4 class="widget-title">Lối tắt</h4>
										<ul class="naves">
											<li>
												<i class="ti-clipboard"></i>
												<a href="index.php" title="">Bảng tin</a>
											</li>
											
											<li>
												<i class="ti-files"></i>
												<a href="about.php" title="">Trang của tôi</a>
											</li>
											<li>
												<i class="ti-user"></i>
												<a href="timeline_friends.php" title="">Bạn bè</a>
											</li>
											<li>
												<i class="ti-image"></i>
												<a href="timeline_photos.php" title="">Ảnh</a>
											</li>
											<li>
												<i class="ti-video-camera"></i>
												<a href="timeline_videos.php" title="">Videos</a>
											</li>
											<li>
												<i class="ti-comments-smiley"></i>
												<a href="messages.php" title="">Tin nhắn</a>
											</li>
											<li>
												<i class="ti-bell"></i>
												<a href="notifications.php" title="">Thông báo</a>
											</li>
											<li>
												<i class="ti-power-off"></i>
												<a href="logout.php" title="">Đăng xuất</a>
											</li>
										</ul>
									</div><!-- Shortcuts -->
									<div class="widget">
										<h4 class="widget-title">Hoạt động gần đây</h4>
										<ul class="activitiez">
											<?php while($activity = $result_activity->fetch_assoc()): ?>
												<li>
													<div class="activity-meta">
														<i><?php echo time_elapsed_string($activity['activity_date']); ?></i>
														<span><a href="<?php echo $activity['reference_link']; ?>" title=""><?php echo $activity['activity_content']; ?></a></span>
													</div>
												</li>
											<?php endwhile; ?>
										</ul>
									</div>
									<!-- recent activites -->
								<!-- Hiển thị bạn bè -->
								<div class="widget stick-widget">
									<h4 class="widget-title">Đang theo dõi</h4>
									<ul class="followers">
										<?php
										while ($friend = $result_friends->fetch_assoc()) {
											?>
											<li>
												<figure><img src="<?php echo htmlspecialchars($friend['profile_picture']); ?>" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title=""><?php echo htmlspecialchars($friend['username']); ?></a></h4>
													<p>Bạn chung <?php echo htmlspecialchars($friend['mutual_friends_count']); ?></p>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
											<?php
										}
										?>
									</ul>
								</div>

								<!-- Hiển thị người theo dõi -->
								<div class="widget">
									<h4 class="widget-title">Theo dõi lại</h4>
									<ul class="followers">
										<?php
										while ($follower = $result_followers->fetch_assoc()) {
											?>
											<li>
												<figure><img src="<?php echo htmlspecialchars($follower['profile_picture']); ?>" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title=""><?php echo htmlspecialchars($follower['username']); ?></a></h4>
													<a href="#" title="" class="underline">Follow back</a>
												</div>
											</li>
											<?php
										}
										?>
									</ul>
								</div>
								</aside>
							</div><!-- sidebar -->
							<div class="col-lg-6">
											<div class="central-meta postbox">
											<span class="create-post">Đăng tin</span>
									<div class="new-postbox">
										<figure>
											<!-- <img src="uploads/profile_pictures/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-avatar.jpg'); ?>" alt="Profile Picture"> -->
										</figure>
										<div class="newpst-input">
											<form method="post" enctype="multipart/form-data">
												<textarea name="content" rows="2" placeholder="Bạn đang nghĩ gì?"></textarea>
												<div class="attachments">
													<ul>
														<li>
															<span class="add-loc">
																<i class="fa fa-map-marker"></i>
															</span>
														</li>
														<li>
															<i class="fa fa-music"></i>
															<label class="fileContainer">
																<input type="file" name="attachments[]" multiple>
															</label>
														</li>
														<li>
															<i class="fa fa-image"></i>
															<label class="fileContainer">
																<input type="file" name="attachments[]" multiple>
															</label>
														</li>
														<li class="preview-btn">
															<button class="post-btn-preview" type="button" data-ripple="">Xem trước</button>
														</li>
													</ul>
													<button class="post-btn" type="submit" data-ripple="">Đăng</button>
												</div>
											</form>
										</div>

										<div class="add-location-post">
											<span>Drag map point to selected area</span>
											<div class="row">

											    <div class="col-lg-6">
											      	<label class="control-label">Lat :</label>
											      	<input type="text" class="" id="us3-lat" />
											    </div>
											    <div class="col-lg-6">
											      	<label>Long :</label>
											      	<input type="text" class="" id="us3-lon" />
											    </div>
											</div>
										  	<!-- map -->
										  	<div id="us3"></div>
										</div>
									</div>	
								</div><!-- add post new box -->
								<div class="central-meta">
									<span class="create-post">Tin gần đây <a href="all_stories.php" title="">Xem tất cả</a></span>
									<div class="story-postbox">
										<div class="row">
											<!-- Story của người dùng thêm mới -->
											<div class="col-lg-3 col-md-3 col-sm-3">
												<div class="story-box">
													<figure>
														<img src="images/resources/story-1.jpg" alt="">
														<span>Tin của bạn</span>
													</figure>
													<!-- Tạo dấu + mở form tải lên -->
													<div class="story-thumb" data-toggle="tooltip" title="Add Your Story" style="cursor: pointer;" onclick="document.getElementById('storyUploadInput').click()">
														<i class="fa fa-plus"></i>
													</div>
													<!-- Input file ẩn để tải lên ảnh -->
													<form method="post" enctype="multipart/form-data" style="display: none;">
														<input id="storyUploadInput" type="file" name="story_image" onchange="this.form.submit()" style="display: none;">
													</form>
												</div>
											</div>

											<!-- Lặp qua từng story từ cơ sở dữ liệu -->
											<?php while ($story = $result->fetch_assoc()): ?>
												<div class="col-lg-3 col-md-3 col-sm-3">
													<div class="story-box">
														<figure>
															<img src="uploads/stories/<?php echo htmlspecialchars($story['image_url']); ?>" alt="">
															<span><?php echo htmlspecialchars($story['username']); ?></span>
														</figure>
														<div class="story-thumb" data-toggle="tooltip" title="<?php echo htmlspecialchars($story['username']); ?>">
															<img src="uploads/profile_pictures/<?php echo htmlspecialchars($story['profile_picture']); ?>" alt="">
														</div>
													</div>
												</div>
											<?php endwhile; ?>
										</div>
									</div>
								</div>


								<div class="loadMore">
									<?php foreach ($posts as $post): ?>
										<div class="central-meta item">
											<div class="user-post">
												<div class="friend-info">
													<figure>
														<!-- Hiển thị ảnh đại diện người dùng -->
														<img src="<?php echo $post['profile_picture']; ?>" alt="Avatar"
														style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
													</figure>
													<div class="friend-name">
														<div class="more">
															<div class="more-post-optns"><i class="ti-more-alt"></i>
																<ul>
																	<li><i class="fa fa-pencil-square-o"></i>Edit Post</li>
																	<li><i class="fa fa-trash-o"></i>Delete Post</li>
																	<li class="bad-report"><i class="fa fa-flag"></i>Report Post</li>
																	<li><i class="fa fa-address-card-o"></i>Boost This Post</li>
																	<li><i class="fa fa-clock-o"></i>Schedule Post</li>
																	<li><i class="fa fa-wpexplorer"></i>Select as featured</li>
																	<li><i class="fa fa-bell-slash-o"></i>Turn off Notifications</li>
																</ul>
															</div>
														</div>
														<!-- Hiển thị tên người dùng -->
														<ins><a href="time-line.html" title=""><?php echo $post['username']; ?></a> Post Album</ins>
														<span><i class="fa fa-globe"></i> published: <?php echo $post['published_at']; ?> </span>
													</div>
													<div class="post-meta">
														<p><?php echo $post['content']; ?></p>
														<!-- Thêm phần hình ảnh nếu có -->
														<ul class="like-dislike">
															<li><a class="bg-purple" href="#" title="Save to Pin Post"><i class="fa fa-thumb-tack"></i></a></li>
															<li><a class="bg-blue" href="#" title="Like Post"><i class="ti-thumb-up"></i></a></li>
															<li><a class="bg-red" href="#" title="Dislike Post"><i class="ti-thumb-down"></i></a></li>
														</ul>
													</div>
													<div class="we-video-info">
														<ul>
															<li><span class="views" title="views"><i class="fa fa-eye"></i><ins><?php echo $post['views_count']; ?></ins></span></li>
															<li><div class="likes heart" title="Like/Dislike">❤ <span><?php echo $post['likes_count']; ?></span></div></li>
															<li><span class="comment" title="Comments"><i class="fa fa-commenting"></i><ins><?php echo $post['comments_count']; ?></ins></span></li>
															<li><span><a class="share-pst" href="#" title="Share"><i class="fa fa-share-alt"></i></a><ins><?php echo $post['shares_count']; ?></ins></span></li>
														</ul>
													</div>
												</div>

												<div class="coment-area" style="display: block;">
													<ul class="we-comet">
														<?php foreach ($post['comments'] as $comment): ?>
														<li>
															<div class="comet-avatar">
															<img src="<?php echo $post['profile_picture']; ?>" alt="Avatar"
															style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
															<div class="we-comment">
																<h5><a href="time-line.html" title=""><?php echo $comment['user_id']; ?></a></h5>
																<p><?php echo $comment['comment_text']; ?></p>
																<div class="inline-itms">
																	<span><?php echo $comment['created_at']; ?></span>
																	<a class="we-reply" href="#" title="Reply"><i class="fa fa-reply"></i></a>
																	<a href="#" title=""><i class="fa fa-heart"></i><span><?php echo $comment['likes_count']; ?></span></a>
																</div>
															</div>
														</li>
														<?php endforeach; ?>
														<li class="post-comment">
															<div class="comet-avatar">
															<img src="<?php echo $post['profile_picture']; ?>" alt="Avatar"
															 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
															</div>
															<div class="post-comt-box">
																<form method="post">
																	<textarea placeholder="Post your comment"></textarea>
																	<button type="submit"></button>
																</form>    
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									<?php endforeach; ?>			
								
							
							    </div>
							</div><!-- centerl meta -->
							<div class="col-lg-3">
								<aside class="sidebar static right">
								<div class="widget">
								<h4 class="widget-title">Your page</h4>
								<div class="your-page">
									<!-- <figure>
										<a href="#" title="Xem trang cá nhân">
											<img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
										</a>
									</figure> -->
									<div class="page-meta">
										<a href="#" title="" class="underline"><?php echo htmlspecialchars($username); ?></a>
										<span><i class="ti-comment"></i><a href="insight.html" title="">Messages <em><?php echo $total_messages; ?></em></a></span>
										<span><i class="ti-bell"></i><a href="insight.html" title="">Notifications <em><?php echo $total_notifications; ?></em></a></span>
									</div>
									<ul class="page-publishes">
										<li><span><i class="ti-pencil-alt"></i>Publish</span></li>
										<li><span><i class="ti-camera"></i>Photo</span></li>
										<li><span><i class="ti-video-camera"></i>Live</span></li>
										<li><span><i class="fa fa-user-plus"></i>Invite</span></li>
									</ul>
									<div class="page-likes">
										<ul class="nav nav-tabs likes-btn">
											<li class="nav-item"><a class="active" href="#link1" data-toggle="tab" data-ripple="">likes</a></li>
											<li class="nav-item"><a class="" href="#link2" data-toggle="tab" data-ripple="">views</a></li>
										</ul>
										<!-- Tab panes -->
										<div class="tab-content">
											<div class="tab-pane active fade show" id="link1">
												<span><i class="ti-heart"></i><?php echo $total_likes; ?></span>
												<a href="#" title="weekly-likes"><?php echo $new_likes; ?> new likes this week</a>
											</div>
											<div class="tab-pane fade" id="link2">
												<span><i class="fa fa-eye"></i><?php echo $total_views; ?></span>
												<a href="#" title="weekly-views"><?php echo $new_views; ?> new views this week</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="widget">
								<h4 class="widget-title">Khám phá sự kiện <a title="" href="#" class="see-all">Xem tất cả</a></h4>

								<?php if ($explore_events): ?>
									<?php foreach ($explore_events as $event): ?>
										<div class="rec-events <?php echo $event['event_class']; ?>">
											<i class="<?php echo $event['event_icon']; ?>"></i>
											<h6><a href="<?php echo $event['event_link']; ?>" title=""><?php echo $event['event_name']; ?></a></h6>
											<img src="images/clock.png" alt="">
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<p>Không có sự kiện nào.</p>
								<?php endif; ?>
							</div>
									<div class="widget">
										<h4 class="widget-title">Tiểu sử</h4>
										<ul class="short-profile">
										<li>
											<span>Giới thiệu</span>
											<p><?php echo htmlspecialchars($profile_intro['about']); ?></p>
										</li>
										<li>
											<span>Chương trình yêu thích</span>
											<p><?php echo htmlspecialchars($profile_intro['fav_tv_show']); ?></p>
										</li>
										<li>
											<span>Bài hát yêu thích</span>
											<p><?php echo htmlspecialchars($profile_intro['favourit_music']); ?></p>
										</li>
									</ul>
								</div><!-- profile intro widget -->
								<div class="widget stick-widget">
										<h4 class="widget-title">Recent Links <a title="" href="#" class="see-all">See All</a></h4>
										<ul class="recent-links">
											<?php
											// Hiển thị các liên kết lấy từ CSDL
											if (!empty($recent_links) && $recent_links[0] != null) {
												foreach ($recent_links as $link) {
													echo '<li>
															<figure><img src="' . $link['image'] . '" alt=""></figure>
															<div class="re-links-meta">
																<h6><a href="' . $link['url'] . '" title="">' . $link['title'] . '</a></h6>
																<span>' . $link['time_ago'] . '</span>
															</div>
														</li>';
												}
											} else {
												echo '<li><p>No recent links found.</p></li>';
											}
											?>
										</ul>
									</div>
								</aside>
							</div><!-- sidebar -->
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</section>

	
<!-- FOOTER -->
	 <?php include 'component/footer.php'; ?>
<!-- END FOOTER -->
	<div class="side-panel">
		<h4 class="panel-title">General Setting</h4>
		<form method="post">
			<div class="setting-row">
				<span>use night mode</span>
				<input type="checkbox" id="nightmode1"/> 
				<label for="nightmode1" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Notifications</span>
				<input type="checkbox" id="switch22" /> 
				<label for="switch22" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Notification sound</span>
				<input type="checkbox" id="switch33" /> 
				<label for="switch33" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>My profile</span>
				<input type="checkbox" id="switch44" /> 
				<label for="switch44" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Show profile</span>
				<input type="checkbox" id="switch55" /> 
				<label for="switch55" data-on-label="ON" data-off-label="OFF"></label>
			</div>
		</form>
		<h4 class="panel-title">Account Setting</h4>
		<form method="post">
			<div class="setting-row">
				<span>Sub users</span>
				<input type="checkbox" id="switch66" /> 
				<label for="switch66" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>personal account</span>
				<input type="checkbox" id="switch77" /> 
				<label for="switch77" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Business account</span>
				<input type="checkbox" id="switch88" /> 
				<label for="switch88" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Show me online</span>
				<input type="checkbox" id="switch99" /> 
				<label for="switch99" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Delete history</span>
				<input type="checkbox" id="switch101" /> 
				<label for="switch101" data-on-label="ON" data-off-label="OFF"></label>
			</div>
			<div class="setting-row">
				<span>Expose author name</span>
				<input type="checkbox" id="switch111" /> 
				<label for="switch111" data-on-label="ON" data-off-label="OFF"></label>
			</div>
		</form>
	</div><!-- side panel -->
	
	<div class="popup-wraper2">
		<div class="popup post-sharing">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<select data-placeholder="Share to friends..." multiple class="chosen-select multi">
						<option>Share in your feed</option>
						<option>Share in friend feed</option>
						<option>Share in a page</option>
						<option>Share in a group</option>
						<option>Share in message</option>
					</select>
					<div class="post-status">
						<span><i class="fa fa-globe"></i></span>
						<ul>
							<li><a href="#" title=""><i class="fa fa-globe"></i> Post Globaly</a></li>
							<li><a href="#" title=""><i class="fa fa-user"></i> Post Private</a></li>
							<li><a href="#" title=""><i class="fa fa-user-plus"></i> Post Friends</a></li>
						</ul>
					</div>
				</div>
				<div class="postbox">
					<div class="post-comt-box">
						<form method="post">
							<input type="text" placeholder="Search Friends, Pages, Groups, etc....">
							<textarea placeholder="Say something about this..."></textarea>
							<div class="add-smiles">
								<span title="add icon" class="em em-expressionless"></span>
								<div class="smiles-bunch">
									<i class="em em---1"></i>
									<i class="em em-smiley"></i>
									<i class="em em-anguished"></i>
									<i class="em em-laughing"></i>
									<i class="em em-angry"></i>
									<i class="em em-astonished"></i>
									<i class="em em-blush"></i>
									<i class="em em-disappointed"></i>
									<i class="em em-worried"></i>
									<i class="em em-kissing_heart"></i>
									<i class="em em-rage"></i>
									<i class="em em-stuck_out_tongue"></i>
								</div>
							</div>

							<button type="submit"></button>
						</form>	
					</div>
					<figure><img src="images/resources/share-post.jpg" alt=""></figure>
					<div class="friend-info">
						<figure>
							<img alt="" src="images/resources/admin.jpg">
						</figure>
						<div class="friend-name">
							<ins><a title="" href="time-line.html">Jack Carter</a> share <a title="" href="#">link</a></ins>
							<span>Yesterday with @Jack Piller and @Emily Stone at the concert of # Rock'n'Rolla in Ontario.</span>
						</div>
					</div>
					<div class="share-to-other">
						<span>Share to other socials</span>
						<ul>
							<li><a class="facebook-color" href="#" title=""><i class="fa fa-facebook-square"></i></a></li>
							<li><a class="twitter-color" href="#" title=""><i class="fa fa-twitter-square"></i></a></li>
							<li><a class="dribble-color" href="#" title=""><i class="fa fa-dribbble"></i></a></li>
							<li><a class="instagram-color" href="#" title=""><i class="fa fa-instagram"></i></a></li>
							<li><a class="pinterest-color" href="#" title=""><i class="fa fa-pinterest-square"></i></a></li>
						</ul>
					</div>
					<div class="copy-email">
						<span>Copy & Email</span>
						<ul>
							<li><a href="#" title="Copy Post Link"><i class="fa fa-link"></i></a></li>
							<li><a href="#" title="Email this Post"><i class="fa fa-envelope"></i></a></li>
						</ul>
					</div>
					<div class="we-video-info">
						<ul>
							<li>
								<span title="" data-toggle="tooltip" class="views" data-original-title="views">
									<i class="fa fa-eye"></i>
									<ins>1.2k</ins>
								</span>
							</li>
							<li>
								<span title="" data-toggle="tooltip" class="views" data-original-title="views">
									<i class="fa fa-share-alt"></i>
									<ins>20k</ins>
								</span>
							</li>
						</ul>
						<button class="main-btn color" data-ripple="">Submit</button>
						<button class="main-btn cancel" data-ripple="">Cancel</button>
					</div>
				</div>
			</div>	
		</div>
	</div><!-- share popup -->
	
	<div class="popup-wraper3">
		<div class="popup">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<h5>Report Post</h5>
				</div>
				<div class="Rpt-meta">
					<span>We're sorry something's wrong. How can we help?</span>
					<form method="post" class="c-form">
						<div class="form-radio">
						  <div class="radio">
							<label>
							  <input type="radio" name="radio" checked="checked"><i class="check-box"></i>It's spam or abuse
							</label>
						  </div>
						  <div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>It breaks r/technology's rules
							</label>
						  </div>
							<div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>Not Related
							</label>
						  </div>
							<div class="radio">
							<label>
							  <input type="radio" name="radio"><i class="check-box"></i>Other issues
							</label>
						  </div>
						</div>
					<div>
						<label>Write about Report</label>
						<textarea placeholder="write someting about Post" rows="2"></textarea>
					</div>
					<div>
						<button data-ripple="" type="submit" class="main-btn">Submit</button>
						<a href="#" data-ripple="" class="main-btn3 cancel">Close</a>
					</div>
					</form>	
				</div>
			</div>	
		</div>
	</div><!-- report popup -->
	
	<div class="popup-wraper1">
		<div class="popup direct-mesg">
			<span class="popup-closed"><i class="ti-close"></i></span>
			<div class="popup-meta">
				<div class="popup-head">
					<h5>Send Message</h5>
				</div>
				<div class="send-message">
					<form method="post" class="c-form">
						<input type="text" placeholder="Sophia">
						<textarea placeholder="Write Message"></textarea>
						<button type="submit" class="main-btn">Send</button>
					</form>
					<div class="add-smiles">
						<div class="uploadimage">
							<i class="fa fa-image"></i>
							<label class="fileContainer">
								<input type="file">
							</label>
						</div>
						<span title="add icon" class="em em-expressionless"></span>
						<div class="smiles-bunch">
							<i class="em em---1"></i>
							<i class="em em-smiley"></i>
							<i class="em em-anguished"></i>
							<i class="em em-laughing"></i>
							<i class="em em-angry"></i>
							<i class="em em-astonished"></i>
							<i class="em em-blush"></i>
							<i class="em em-disappointed"></i>
							<i class="em em-worried"></i>
							<i class="em em-kissing_heart"></i>
							<i class="em em-rage"></i>
							<i class="em em-stuck_out_tongue"></i>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div><!-- send message popup -->
	
	<div class="modal fade" id="img-comt">
		<div class="modal-dialog">
		  <div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">×</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-8">
						<div class="pop-image">
							<div class="pop-item">
								<figure><img src="images/resources/blog-detail.jpg" alt=""></figure>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="user">
							<figure><img src="images/resources/user1.jpg" alt=""></figure>
							<div class="user-information">
								<h4><a href="#" title="">Danile Walker</a></h4>
								<span>2 hours ago</span>
							</div>
							<a href="#" title="Follow" data-ripple="">Follow</a>
						</div>
						<div class="we-video-info">
							<ul>
								<li>
									<div title="Like/Dislike" class="likes heart">❤ <span>2K</span></div>
								</li>
								<li>
									<span title="Comments" class="comment">
										<i class="fa fa-commenting"></i>
										<ins>52</ins>
									</span>
								</li>

								<li>
									<span>
										<a title="Share" href="#" class="">
											<i class="fa fa-share-alt"></i>
										</a>
										<ins>20</ins>
									</span>	
								</li>
							</ul>
							<div class="users-thumb-list">
								<a href="#" title="" data-toggle="tooltip" data-original-title="Anderw">
									<img src="images/resources/userlist-1.jpg" alt="">  
								</a>
								<a href="#" title="" data-toggle="tooltip" data-original-title="frank">
									<img src="images/resources/userlist-2.jpg" alt="">  
								</a>
								<a href="#" title="" data-toggle="tooltip" data-original-title="Sara">
									<img src="images/resources/userlist-3.jpg" alt="">  
								</a>
								<a href="#" title="" data-toggle="tooltip" data-original-title="Amy">
									<img src="images/resources/userlist-4.jpg" alt="">  
								</a>
								<span><strong>You</strong>, <b>Sarah</b> and <a title="" href="#">24+ more</a> liked</span>
							</div>
						</div>
						<div style="display: block;" class="coment-area">
							<ul class="we-comet">
								<li>
									<div class="comet-avatar">
										<img alt="" src="images/resources/nearly3.jpg">
									</div>
									<div class="we-comment">
										<h5><a title="" href="time-line.html">Jason borne</a></h5>
										<p>we are working for the dance and sing songs. this video is very awesome for the youngster. please vote this video and like our channel</p>
										<div class="inline-itms">
											<span>1 year ago</span>
											<a title="Reply" href="#" class="we-reply"><i class="fa fa-reply"></i></a>
											<a title="" href="#"><i class="fa fa-heart"></i><span>20</span></a>
										</div>
									</div>

								</li>
								<li>
									<div class="comet-avatar">
										<img alt="" src="images/resources/comet-4.jpg">
									</div>
									<div class="we-comment">
										<h5><a title="" href="time-line.html">Sophia</a></h5>
										<p>we are working for the dance and sing songs. this video is very awesome for the youngster.
											<i class="em em-smiley"></i>
										</p>
										<div class="inline-itms">
											<span>1 year ago</span>
											<a title="Reply" href="#" class="we-reply"><i class="fa fa-reply"></i></a>
											<a title="" href="#"><i class="fa fa-heart"></i><span>20</span></a>
										</div>
									</div>
								</li>
								<li>
									<div class="comet-avatar">
										<img alt="" src="images/resources/comet-4.jpg">
									</div>
									<div class="we-comment">
										<h5><a title="" href="time-line.html">Sophia</a></h5>
										<p>we are working for the dance and sing songs. this video is very awesome for the youngster.
											<i class="em em-smiley"></i>
										</p>
										<div class="inline-itms">
											<span>1 year ago</span>
											<a title="Reply" href="#" class="we-reply"><i class="fa fa-reply"></i></a>
											<a title="" href="#"><i class="fa fa-heart"></i><span>20</span></a>
										</div>
									</div>
								</li>
								<li>
									<a class="showmore underline" title="" href="#">more comments+</a>
								</li>
								<li class="post-comment">
									<div class="comet-avatar">
										<img alt="" src="images/resources/nearly1.jpg">
									</div>
									<div class="post-comt-box">
										<form method="post">
											<textarea placeholder="Post your comment"></textarea>
											<div class="add-smiles">
												<div class="uploadimage">
													<i class="fa fa-image"></i>
													<label class="fileContainer">
														<input type="file">
													</label>
												</div>
												<span title="add icon" class="em em-expressionless"></span>
												<div class="smiles-bunch">
													<i class="em em---1"></i>
													<i class="em em-smiley"></i>
													<i class="em em-anguished"></i>
													<i class="em em-laughing"></i>
													<i class="em em-angry"></i>
													<i class="em em-astonished"></i>
													<i class="em em-blush"></i>
													<i class="em em-disappointed"></i>
													<i class="em em-worried"></i>
													<i class="em em-kissing_heart"></i>
													<i class="em em-rage"></i>
													<i class="em em-stuck_out_tongue"></i>
												</div>
											</div>

											<button type="submit"></button>
										</form>	
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		  </div>
		</div>
    </div><!-- The Scrolling Modal image with comment -->
	
	<script src="js/main.min.js"></script>
	<script src="js/jquery-stories.js"></script>
	<script src="js/toast-notificatons.js"></script>
	<script src="../../../cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/TweenMax.min.js"></script><!-- For timeline slide show -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8c55_YHLvDHGACkQscgbGLtLRdxBDCfI"></script><!-- for location picker map -->
	<script src="js/locationpicker.jquery.js"></script><!-- for loaction picker map -->
	<script src="js/map-init.js"></script><!-- map initilasition -->
	<script src="js/page-tourintro.js"></script>
	<script src="js/page-tour-init.js"></script>
	<script src="js/script.js"></script>
	<script>
	jQuery(document).ready(function($) {
$('#us3').locationpicker({
  location: {
	latitude: 21.028511, // Vĩ độ của Hà Nội
	longitude: 105.804817 // Kinh độ của Hà Nội
  },
  radius: 0,
  inputBinding: {
	latitudeInput: $('#us3-lat'),
	longitudeInput: $('#us3-lon'),
	radiusInput: $('#us3-radius'),
	locationNameInput: $('#us3-address')
  },
  enableAutocomplete: true,
  onchanged: function (currentLocation, radius, isMarkerDropped) {
	// Uncomment line below to show alert on each Location Changed event
	//alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
  }
});

if ($.isFunction($.fn.toast)) {
	$.toast({
		heading: 'Welcome To NAPA Social Network',
		text: '',
		showHideTransition: 'slide',
		icon: 'success',
		loaderBg: '#fa6342',
		position: 'bottom-right',
		hideAfter: 3000,
	});

	$.toast({
		heading: 'Information',
		text: 'Now you can full demo of NAPA Social Network and hope you like',
		showHideTransition: 'slide',
		icon: 'info',
		hideAfter: 5000,
		loaderBg: '#fa6342',
		position: 'bottom-right',
	});
	$.toast({
		heading: 'Support & Help',
		text: 'Report any <a href="#">issues</a> by email',
		showHideTransition: 'fade',
		icon: 'error',
		hideAfter: 7000,
		loaderBg: '#fa6342',
		position: 'bottom-right',
	});
}

});
</script>

</body>	


</html>