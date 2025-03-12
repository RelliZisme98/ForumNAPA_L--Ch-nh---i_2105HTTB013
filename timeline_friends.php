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
<?php include 'component/sidebarright.php'; ?>
<?php include 'component/sidebarleft.php'; ?>
		
	<section>
		<div class="gap2 gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="row merged20" id="page-contents">
							
								
								
								
										<div class="col-lg-10 col-md-9">
											<ul class="profile-menu">
												<li>
													<a class="" href="timeline.php">Timeline</a>
												</li>
												<li>
													<a class="" href="about.php">About</a>
												</li>
												<li>
													<a class="active" href="timeline_friends.php">Friends</a>
												</li>
												<li>
													<a class="" href="timeline_photos.php">Photos</a>
												</li>
												<li>
													<a class="" href="timeline_videos.php">Videos</a>
												</li>
												<li>
													<div class="more">
														<i class="fa fa-ellipsis-h"></i>
														<ul class="more-dropdown">
															<li>
																<a href="timeline_groups.php">Profile Groups</a>
															</li>
															<li>
																<a href="statistics.html">Profile Analytics</a>
															</li>
														</ul>
													</div>
												</li>
											</ul>
											
										</div>
									</div>
								</div>	
							</div><!-- user profile banner  -->
							<div class="col-lg-12">
								<div class="central-meta">
									<div class="title-block">
										<div class="row">
											<div class="col-lg-6">
												<div class="align-left">
													<h5>Friends / Followers <span>44</span></h5>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="row merged20">
													<div class="col-lg-7 col-lg-7 col-sm-7">
														<form method="post">
															<input type="text" placeholder="Search Friend">
															<button type="submit"><i class="fa fa-search"></i></button>
														</form>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-4">
														<div class="select-options">
															<select class="select">
																<option>Sort by</option>
																<option>A to Z</option>
																<option>See All</option>
																<option>Newest</option>
																<option>oldest</option>
															</select>
														</div>
													</div>
													<div class="col-lg-1 col-md-1 col-sm-1">
														<div class="option-list">
															<i class="fa fa-ellipsis-v"></i>
															<ul>
																<li><a title="" href="#">Show Friends Public</a></li>
																<li><a title="" href="#">Show Friends Private</a></li>
																<li><a title="" href="#">Mute Notifications</a></li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div><!-- title block -->
								<div class="central-meta padding30">
									<div class="row">
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover1.jpg" alt="">
													<span>Followers: 120</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure1.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Adam James</a>
														<span>California, USA</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover2.jpg" alt="">
													<span>Followers: 450</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure2.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Andrew</a>
														<span>Tornoto, Canada</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover3.jpg" alt="">
													<span>Followers: 50</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure3.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Arnold</a>
														<span>Istanbul, Turky</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover4.jpg" alt="">
													<span>Followers: 410</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure4.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Ella John</a>
														<span>Maxico city, USA</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover5.jpg" alt="">
													<span>Followers: 16k</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure5.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Madison</a>
														<span>Los Angeles, CA</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover6.jpg" alt="">
													<span>Followers: 2M</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure6.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Victoria</a>
														<span>Brazil</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover7.jpg" alt="">
													<span>Followers: 220</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure7.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Lillian</a>
														<span>Mumbai, India</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="friend-box">
												<figure>
													<img src="images/resources/frnd-cover8.jpg" alt="">
													<span>Followers: 220</span>
												</figure>
												<div class="frnd-meta">
													<img src="images/resources/frnd-figure8.jpg" alt="">
													<div class="frnd-name">
														<a href="#" title="">Xing Weng</a>
														<span>Beijing, China</span>
													</div>
													<ul class="frnd-info">
														<li><span>Friends:</span> 223 (2 mutule friends)</li>
														<li><span>Videos:</span> 240</li>
														<li><span>Photos:</span> 144</li>
														<li><span>Post:</span> 250</li>
														<li><span>Since:</span> December, 2014</li>
													</ul>
													<a class="send-mesg" href="#" title="">Message</a>
													<div class="more-opotnz">
														<i class="fa fa-ellipsis-h"></i>
														<ul>
															<li><a href="#" title="">Block</a></li>
															<li><a href="#" title="">UnBlock</a></li>
															<li><a href="#" title="">Mute Notifications</a></li>
															<li><a href="#" title="">hide from friend list</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="lodmore">
										<span>Viewing 1-8 of 44 friends</span>
										<button class="btn-view btn-load-more"></button>
									</div>
								</div>
							</div>	
						</div>	
					</div>
				</div>
			</div>
		</div>	
	</section><!-- content -->

	<a title="Your Cart Items" href="shop-cart.html" class="shopping-cart" data-toggle="tooltip">Cart <i class="fa fa-shopping-bag"></i><span>02</span></a>

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
	
	<script src="js/main.min.js"></script>
	<script src="js/script.js"></script>

</body>	


</html>