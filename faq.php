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
		  <span style="--i:1;">F</span>
		  <span style="--i:2;">O</span>
		  <span style="--i:3;">R</span>
		  <span style="--i:4;">U</span>
		  <span style="--i:5;">M</span>
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
				<h2>Faq's</h2>
				<form method="post">
					<input type="text" placeholder="Ask Question">
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>
				<p>
					Chào mừng đến với NAPA Social Network. Tại đây bạn có thể tìm kiếm các câu hỏi thường gặp và câu trả lời để trải nghiệm của bạn trở nên tốt hơn! 
				</p>
			</div>
			<figure><img src="images/resources/baner-forum.png" alt=""></figure>
		</div>
	</section><!-- sub header -->
	
	<section>
		<div class="gap gray-bg">
			<div class="container">
				<div class="row" id="page-contents">
					<div class="col-lg-3">
						<aside class="sidebar static left">
							<div class="widget">
								<h4 class="widget-title">Socials</h4>
								<ul class="socials">
									<li class="facebook">
										<a title="" href="#"><i class="fa fa-facebook"></i> <span>facebook</span> <ins>45 likes</ins></a>
									</li>
									<li class="twitter">
										<a title="" href="#"><i class="fa fa-twitter"></i> <span>twitter</span><ins>25 likes</ins></a>
									</li>
									<li class="google">
										<a title="" href="#"><i class="fa fa-google"></i> <span>google</span><ins>35 likes</ins></a>
									</li>
								</ul>
							</div>
							<div class="widget stick-widget">
								<h4 class="widget-title">Shortcuts</h4>
								<ul class="naves">
									<li>
										<i class="ti-clipboard"></i>
										<a href="newsfeed.html" title="">News feed</a>
									</li>
									<li>
										<i class="ti-mouse-alt"></i>
										<a href="inbox.html" title="">Inbox</a>
									</li>
									<li>
										<i class="ti-files"></i>
										<a href="fav-page.html" title="">My pages</a>
									</li>
									<li>
										<i class="ti-user"></i>
										<a href="timeline-friends.html" title="">friends</a>
									</li>
									<li>
										<i class="ti-image"></i>
										<a href="timeline-photos.html" title="">images</a>
									</li>
									<li>
										<i class="ti-video-camera"></i>
										<a href="timeline-videos.html" title="">videos</a>
									</li>

								</ul>
							</div><!-- Shortcuts -->
						</aside>	
					</div>
					<div class="col-lg-9">
						<div class="faq-area">
							<h4>Ask help about Pitnik</h4>
							<p>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
							</p>
							<div class="accordion" id="accordion">
							  <div class="card">
								<div class="card-header" id="headingOne">
								  <h5 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									  How to make your own social website ?
									</button>
								  </h5>
								</div>

								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								  <div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
									  <a href="#" title="">Go for Create Page</a>
									<ol>
										<li>register yourself on friendzone</li>
										<li>go to the setting panal</li>
										<li>click on <a href="#" title="">create page</a></li>
									</ol>
								  </div>
								</div>
							  </div>
							  <div class="card">
								<div class="card-header" id="headingTwo">
								  <h5 class="mb-0">
									<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									  How to edit my page setting?
									</button>
								  </h5>
								</div>
								<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								  <div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, 
								  </div>
								</div>
							  </div>
							  <div class="card">
								<div class="card-header" id="headingThree">
								  <h5 class="mb-0">
									<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									  How to change password ?
									</button>
								  </h5>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
								  <div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. 
								  </div>
								</div>
							  </div>
							  <div class="card">
								<div class="card-header" id="headingfour">
								  <h5 class="mb-0">
									<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
									  How to search people nearby with location ?
									</button>
								  </h5>
								</div>
								<div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordion">
								  <div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. 
								  </div>
								</div>
							  </div>
							  <div class="card">
								<div class="card-header" id="headingfive">
								  <h5 class="mb-0">
									<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">
									  How to Make your favourit page ?
									</button>
								  </h5>
								</div>
								<div id="collapsefive" class="collapse" aria-labelledby="headingfive" data-parent="#accordion">
								  <div class="card-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. 
								  </div>
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="gap">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="video-info">
							<h2 class="main-title">getting started tutorial video</h2>
							<div class="row">
								<div class="col-lg-7 col-md-8">
									<div class="tutorial-video">
										<iframe height="315" src="https://www.youtube.com/embed/cw0jRD7mn1k"></iframe>
									</div>
								</div>
								<div class="col-lg-5 col-md-4">
									<div class="vid-links">
										<h4>video guide</h4>
										<ul class="tutor-links">
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> useful video</a></li>
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> how to start</a></li>
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> theme installation</a></li>
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> warnings</a></li>
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> configurations</a></li>
											<li><a href="#" title=""><i class="fa fa-play-circle-o"></i> total videos</a></li>
										</ul>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- video section -->

	<section>
		<div class="getquot-baner purple high-opacity">
			<div class="bg-image" style="background-image:url(images/resources/animated-bg2.png)"></div>
			<span>Bạn có muốn tham gia diễn đàn của chúng tôi và xem những nội dung đặc sắc?</span>
			<a title="" href="register.php">Đăng kí</a>
		</div>
	</section>

    <?php include 'component/footer.php'; ?>
</div>
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
	
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>

</body>	


</html>