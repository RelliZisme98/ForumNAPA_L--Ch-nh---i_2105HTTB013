<?php
session_start();
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'ledai_forum');
// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Truy vấn lấy dữ liệu từ bảng threads
$threads_query = $conn->prepare("
    SELECT threads.id, threads.title, threads.user_id, threads.created_at, COUNT(questions.id) AS total_questions, users.username 
    FROM threads
    LEFT JOIN questions ON threads.id = questions.thread_id
    LEFT JOIN users ON threads.user_id = users.id
    GROUP BY threads.id
    ORDER BY threads.created_at DESC
    LIMIT 5
");
$threads_query->execute();
$threads_result = $threads_query->get_result();
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
				</p>
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
												<h5>Forum Category</h5>
											</div>
										</div>
										<div class="col-lg-6">
										<div class="row merged20">
        <div class="col-lg-7 col-md-7 col-sm-7">
            <!-- Thanh tìm kiếm -->
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
							</div><!-- title block -->
						</div>
					<!-- Hiển thị dữ liệu từ bảng threads -->

					<div class="central-meta">
    <div class="forum-list">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col">Chủ đề</th>
                    <th scope="col">Số câu hỏi</th>
                    <th scope="col">Tạo bởi</th>
                    <th scope="col">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($thread = $threads_result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <i class="fa fa-comments"></i> 
                        <a href="forum_category.php?thread_id=<?php echo $thread['id']; ?>" title="">
                            <?php echo htmlspecialchars($thread['title']); ?>
                        </a>
                    </td>
                    <td><?php echo $thread['total_questions']; ?></td>
                    <td>
                        <h6>Started by: <a href="#" title=""><?php echo htmlspecialchars($thread['username']); ?></a></h6>
                    </td>
                    <td><?php echo date("F j, Y, g:i a", strtotime($thread['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
					<div class="col-lg-3">
						<aside class="sidebar static">
							<div class="widget">
							
							</div>
						
						</aside>	
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

</body>	


</html>