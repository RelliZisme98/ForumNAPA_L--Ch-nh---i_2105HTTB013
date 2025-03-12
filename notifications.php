<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
	<title>NAPA Social Network</title>
    <link rel="icon" href="images/fav.png" type="image/png" sizes="16x16"> 
    
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
							<div class="user-profile">
								<figure>
									<div class="edit-pp">
										<label class="fileContainer">
											<i class="fa fa-camera"></i>
											<input type="file">
										</label>
									</div>
									<img src="images/resources/profile-image.jpg" alt="">
									<ul class="profile-controls">
										<li><a href="#" title="Add friend" data-toggle="tooltip"><i class="fa fa-user-plus"></i></a></li>
										<li><a href="#" title="Follow" data-toggle="tooltip"><i class="fa fa-star"></i></a></li>
										<li><a class="send-mesg" href="#" title="Send Message" data-toggle="tooltip"><i class="fa fa-comment"></i></a></li>
										<li>
											<div class="edit-seting" title="Edit Profile image"><i class="fa fa-sliders"></i>
												<ul class="more-dropdown">
													<li><a href="setting.html" title="">Update Profile Photo</a></li>
													<li><a href="setting.html" title="">Update Header Photo</a></li>
													<li><a href="setting.html" title="">Account Settings</a></li>
													<li><a href="support-and-help.html" title="">Find Support</a></li>
													<li><a class="bad-report" href="#" title="">Report Profile</a></li>
													<li><a href="#" title="">Block Profile</a></li>
												</ul>
											</div>
										</li>
									</ul>
									<ol class="pit-rate">
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class="rated"><i class="fa fa-star"></i></li>
										<li class=""><i class="fa fa-star"></i></li>
										<li><span>4.7/5</span></li>
									</ol>
								</figure>
								
								<div class="profile-section">
									<div class="row">
										<div class="col-lg-2 col-md-3">
											<div class="profile-author">
												<div class="profile-author-thumb">
													<img alt="author" src="images/resources/author.jpg">
													<div class="edit-dp">
														<label class="fileContainer">
															<i class="fa fa-camera"></i>
															<input type="file">
														</label>
													</div>
												</div>
													
												<div class="author-content">
													<a class="h4 author-name" href="about.html">Jack Carter</a>
													<div class="country">Ontario, CA</div>
												</div>
											</div>
										</div>
										<div class="col-lg-10 col-md-9">
											<ul class="profile-menu">
												<li>
													<a class="" href="timeline.html">Timeline</a>
												</li>
												<li>
													<a class="" href="about.html">About</a>
												</li>
												<li>
													<a class="" href="timeline-friends.html">Friends</a>
												</li>
												<li>
													<a class="" href="timeline-photos.html">Photos</a>
												</li>
												<li>
													<a class="" href="timeline-videos.html">Videos</a>
												</li>
												<li>
													<div class="more">
														<i class="fa fa-ellipsis-h"></i>
														<ul class="more-dropdown">
															<li>
																<a href="timeline-groups.html">Profile Groups</a>
															</li>
															<li>
																<a href="statistics.html">Profile Analytics</a>
															</li>
														</ul>
													</div>
												</li>
											</ul>
											<ol class="folw-detail">
												<li><span>Posts</span><ins>101</ins></li>
												<li><span>Followers</span><ins>1.3K</ins></li>
												<li><span>Following</span><ins>22</ins></li>
											</ol>
										</div>
									</div>
								</div>	
							</div><!-- user profile banner  -->
							<div class="col-lg-9">
								<div class="central-meta">
									<div class="editing-interest">
										<span class="create-post"><i class="ti-bell"></i> All Notifications<a title="" href="setting.html">Notifications Setting</a></span>
										<div class="notification-box">
											<ul>
												<li>
													<figure><img src="images/resources/friend-avatar.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>bob frank like your post</p>
														<span><i class="fa fa-thumbs-up"></i> 30 mints ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close" title="Remove"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar2.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Sarah Hetfield commented on your photo. </p>
														<span><i class="fa fa-comment"></i> 1 hours ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar3.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Mathilda Brinker commented on your new profile status. </p>
														<span><i class="fa fa-comment"></i> 2 hours ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar4.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Motel Pitnik invited you to attend to his event Goo & Gotham Bar. </p>
														<span><i class="fa fa-address-card"></i> 2 hours ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar5.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Chris Greyson liked your profile status. </p>
														<span><i class="fa fa-thumbs-up"></i> 1 day ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar6.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>You and Nicholas Grissom just became friends. Write on his wall. </p>
														<span><i class="fa fa-user"></i> 2 days ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar3.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Mathilda Brinker commented on your new profile status. </p>
														<span><i class="fa fa-comment"></i> 2 hours ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar4.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>Motel Pitnik invited you to attend to his event Goo & Gotham Bar. </p>
														<span><i class="fa fa-address-card"></i> 2 hours ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close"></i>
												</li>
												<li>
													<figure><img src="images/resources/friend-avatar.jpg" alt=""></figure>
													<div class="notifi-meta">
														<p>bob frank like your post</p>
														<span><i class="fa fa-thumbs-up"></i> 30 mints ago</span>
													</div>
													<div class="more">
														<div class="more-post-optns"><i class="ti-more-alt"></i>
															<ul>
																<li><i class="fa fa-bell-slash-o"></i>Mute</li>
																<li><i class="fa fa-wpexplorer"></i>Report</li>
																<li><i class="fa fa-bell-slash-o"></i>Block</li>
															</ul>
														</div>
													</div>
													<i class="del ti-close" title="Remove"></i>
												</li>
											</ul>
										</div>
									</div>
								</div>	
							</div><!-- centerl meta -->
							<div class="col-lg-3">
								<aside class="sidebar static">
									<div class="widget">
											<div class="banner medium-opacity bluesh">
											<div class="bg-image" style="background-image: url(images/resources/baner-widgetbg.jpg)"></div>
											<div class="baner-top">
												<span><img alt="" src="images/book-icon.png"></span>
												<i class="fa fa-ellipsis-h"></i>
											</div>
											<div class="banermeta">
												<p>
													create your own favourit page.
												</p>
												<span>like them all</span>
												<a data-ripple="" title="" href="#">start now!</a>
											</div>
										</div>											
										</div>
									<div class="widget stick-widget">
										<h4 class="widget-title">Who's follownig</h4>
										<ul class="followers">
											<li>
												<figure><img src="images/resources/friend-avatar2.jpg" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title="">Kelly Bill</a></h4>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
											<li>
												<figure><img src="images/resources/friend-avatar4.jpg" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title="">Issabel</a></h4>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
											<li>
												<figure><img src="images/resources/friend-avatar6.jpg" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title="">Andrew</a></h4>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
											<li>
												<figure><img src="images/resources/friend-avatar8.jpg" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title="">Sophia</a></h4>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
											<li>
												<figure><img src="images/resources/friend-avatar3.jpg" alt=""></figure>
												<div class="friend-meta">
													<h4><a href="time-line.html" title="">Allen</a></h4>
													<a href="#" title="" class="underline">Add Friend</a>
												</div>
											</li>
										</ul>
									</div><!-- who's following -->
								</aside>
							</div><!-- sidebar -->
						</div>	
					</div>
				</div>
			</div>
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