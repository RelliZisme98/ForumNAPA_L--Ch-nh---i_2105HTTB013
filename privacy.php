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
	
		
	<section>
		<div class="page-header">
			<div class="header-inner">
				<h2>Privacy & Policy</h2>
				<p>
					Tại đây bạn có thể tìm thấy các thông tin về điều khoản các chính sách và luật để biết thêm thông tin khi sử dụng website.
				</p>
			</div>
			<figure><img src="images/resources/baner-badges.png" alt=""></figure>
		</div>
	</section><!-- sub header -->
	
	<section>
		<div class="gap gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="privacy">
							<div class="d-flex flex-row mt-2">
								<ul class="policy nav nav-tabs nav-tabs--vertical nav-tabs--left" >
									<li class="nav-item">
										<a href="#terms" class="nav-link active" data-toggle="tab" >Terms Of Services</a>
									</li>
									<li class="nav-item">
										<a href="#agreement" class="nav-link" data-toggle="tab" >User Agreement</a>
									</li>
									<li class="nav-item">
										<a href="#privecy" class="nav-link" data-toggle="tab" >Privacy Policy</a>
									</li>
									<li class="nav-item">
										<a href="#p-center" class="nav-link" data-toggle="tab" >Privacy Center</a>
									</li>
									<li class="nav-item">
										<a href="#cookie" class="nav-link" data-toggle="tab" >Cookie Policy</a>
									</li>
									<li class="nav-item">
										<a href="#instr" class="nav-link" data-toggle="tab" >Instructions</a>
									</li>
									<li class="nav-item">
										<a href="#api" class="nav-link" data-toggle="tab"  >API Terms Of Use</a>
									</li>
									<li class="nav-item">
										<a href="#security" class="nav-link" data-toggle="tab" >Data Security & Risk</a>
									</li>
									<li class="nav-item">
										<a href="#apps" class="nav-link" data-toggle="tab" >Apps Policy</a>
									</li>
								</ul>
								<div class="tab-content central-meta">
									<div class="tab-pane fade show active" id="terms" >
										<div class="privacy-meta">
											<div class="set-title">
											<h5>Terms Policy</h5>
											<span>Select push and email notifications you'd like to receive.</span>
										</div>
<p>These cookies allow the Site to remember the choices you make (such as your username, language or the region you are in). For example, the Site uses functionality cookies to remember your language preference. These cookies can also be used to remember changes you have made to text size, font, and other parts of pages that you can customize. They can also be used to provide services you have requested, such as watching a video or commenting on a blog. The information these cookies collect may be anonymous and cannot track your browsing activity on other websites.</p>
<h6>Cookies Flash</h6>
	<p>
	We may, in certain circumstances, use Adobe Flash Player to deliver special content, such as video clips or animation. To improve your user experience, Local Shared Objects (commonly known as "Flash cookies") are used to provide features such as remembering your settings and preferences. Flash cookies are stored on your device, but are managed through a different interface than that provided by your web browser. This means that it is not possible to manage Flash cookies through your browser in the same way as you normally would with cookies. Instead, you can access your Flash management tools from the Adobe website in http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager.html.</p>
	
	<h6>MEASURED CONTENT COOKIES</h6>
<p>Tailor-made content cookies help the Site provide enhanced features and display content in a way that is relevant to you. These cookies help the Site determine what information to show you based on how you have used the Site previously. These cookies do not track your browsing activity on other websites.</p>
	<h6>TARGET COOKIES</h6>
	
<p>These cookies are used to deliver advertisements that are more relevant to you and your interests. They are also used to limit the number of times you see an ad, as well as to help measure the effectiveness of an ad campaign. They remember that you have visited a website and this information can be shared with other organizations, such as advertisers. This means that after you have visited the Site, you may see some advertisements about our services on other parts of the Internet.</p>
											<h6>How long will cookies remain on my navigation device?</h6>
	
<p>The length of time a cookie will remain on your navigation device depends on whether it is a "persistent" cookie or "session" cookie. Session cookies will only remain on your device until you stop browsing. Persistent cookies remain on your navigation device until they expire or are deleted.</p>
	<h6>FIRST AND THIRD COOKIES</h6>
<p>First-party cookies are cookies that belong to us, while third-party cookies are cookies that are placed by another party or</p>
											<p class="p-info"><a href="#">Contact Us</a> for more informations and security</p>
										</div>
									</div><!-- general setting -->
									<div class="tab-pane fade" id="agreement" >
										<div class="privacy-meta">
										<div class="set-title">
											<h5>User Agreement</h5>
											<span>Select push and email notifications you'd like to receive.</span>
										</div>
										<p>
											PLEASE READ THE TERMS OF SERVICE CAREFULLY WHEN YOU CONTAIN IMPORTANT INFORMATION ABOUT YOUR RIGHTS, REMEDIES AND LEGAL OBLIGATIONS. THESE INCLUDE SEVERAL LIMITATIONS AND EXCLUSIONS AND A BINDING ARBITRATION AGREEMENT AND A CLASS ACTION WAIVER.
										</p>
	<p>
	We may, in certain circumstances, use Adobe Flash Player to deliver special content, such as video clips or animation. To improve your user experience, Local Shared Objects (commonly known as "Flash cookies") are used to provide features such as remembering your settings and preferences. Flash cookies are stored on your device, but are managed through a different interface than that provided by your web browser. This means that it is not possible to manage Flash cookies through your browser in the same way as you normally would with cookies. Instead, you can access your Flash management tools from the Adobe website in http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager.html.</p>
											<p class="p-info"><a href="#">Contact Us</a> for more informations and security</p>
											</div>
										
									</div><!-- edit profile -->
									<div class="tab-pane fade" id="privecy" role="tabpanel">
										<div class="set-title">
											<h5>Privacy & Policy</h5>
											<span>Select push and email notifications you'd like to receive.</span>
										</div>
										<div class="privacy-meta">
											<p>
												Pitnik provides this Privacy Policy to let you know our policies and procedures regarding the collection, use and disclosure of information through www.abc.com (the "Site") and any other website, features, applications, widgets or online services that are owned or controlled by Pitnik and that post a link to this Privacy Policy (along with the Site, the "Service"), as well as any information that Pitnik collects offline at related to the Service. It also describes the options available to you regarding your use, your access, and how to update and correct your personal information. This Privacy Policy incorporates by reference the Pitnik Global Data Processing Agreement. Please note that we combine the information we collect from you from the Site, through the Service in general, or offline.
											</p>
											<p class="p-info"><a href="#">Contact Us</a> for more informations and security</p>
										</div>
									</div><!-- notification -->
									<div class="tab-pane fade" id="p-center" role="tabpanel">
										<div class="set-title">
											<h5>Privacy Center</h5>
											<span>Set your login preference, help us personalize your experience and make big account change here.</span>
										</div>
										<div class="privacy-meta">
												<p>
													Pitnik's Privacy and Legal security and information teams have carefully analyzed applicable privacy laws and regulations and taken steps to ensure that Pitnik meets your requirements.
We value the privacy of our users and their rights to control their personal data. Regardless of where you call home, you can close your account or request the deletion of all personal information we hold about you at any time. However, we will only follow the requirements described by the GDPR and CCPA for those living in the EEA and California, respectively. If you live elsewhere, we will be happy to consider your request to delete your data.
												</p>
												<p class="p-info"><a href="#">Contact Us</a> for more informations and security</p>
											</div>
									</div><!-- messages -->
									<div class="tab-pane fade" id="cookie" role="tabpanel">
										<div class="privacy-meta">
											<div class="set-title">
											<h5>Cookies Policy</h5>
											<span>Select push and email notifications you'd like to receive.</span>
										</div>
<h6>Cookies Flash</h6>
	<p>
	We may, in certain circumstances, use Adobe Flash Player to deliver special content, such as video clips or animation. To improve your user experience, Local Shared Objects (commonly known as "Flash cookies") are used to provide features such as remembering your settings and preferences. Flash cookies are stored on your device, but are managed through a different interface than that provided by your web browser. This means that it is not possible to manage Flash cookies through your browser in the same way as you normally would with cookies. Instead, you can access your Flash management tools from the Adobe website in http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager.html.</p>
	
	<h6>MEASURED CONTENT COOKIES</h6>
<p>Tailor-made content cookies help the Site provide enhanced features and display content in a way that is relevant to you. These cookies help the Site determine what information to show you based on how you have used the Site previously. These cookies do not track your browsing activity on other websites.</p>
	<h6>TARGET COOKIES</h6>
	
<p>These cookies are used to deliver advertisements that are more relevant to you and your interests. They are also used to limit the number of times you see an ad, as well as to help measure the effectiveness of an ad campaign. They remember that you have visited a website and this information can be shared with other organizations, such as advertisers. This means that after you have visited the Site, you may see some advertisements about our services on other parts of the Internet.</p>
											<h6>How long will cookies remain on my navigation device?</h6>
	
<p>The length of time a cookie will remain on your navigation device depends on whether it is a "persistent" cookie or "session" cookie. Session cookies will only remain on your device until you stop browsing. Persistent cookies remain on your navigation device until they expire or are deleted.</p>
	<h6>FIRST AND THIRD COOKIES</h6>
<p>First-party cookies are cookies that belong to us, while third-party cookies are cookies that are placed by another party or</p>
											<p class="p-info"><a href="#">Contact Us</a> for more informations and security</p>
										</div>
									</div><!-- weather widget setting -->
									<div class="tab-pane fade" id="instr" role="tabpanel">
										<div class="set-title">
											<h5>Instructions</h5>
											<span>Deceide whether your profile will be hidden from search engine and what kind of data you want to use to imporve the recommendation and ads you see <a href="#" title="">Learn more</a></span>
										</div>
										<p class="p-info"><a href="manage-page.html">Click here</a> to go widget and page setting area</p>
									</div><!-- privacy -->
									<div class="tab-pane fade" id="api" role="tabpanel">
										<div class="set-title">
											<h5>Privacy & data</h5>
											<span>Deceide whether your profile will be hidden from search engine and what kind of data you want to use to imporve the recommendation and ads you see <a href="#" title="">Learn more</a></span>
										</div>
										<div class="onoff-options ">
											<form method="post">
												<div class="setting-row">
													<span>Search Privacy</span>
													<p>Hide your profile from search engine (Ex.google) <a href="#" title="">Learn more</a>
													</p>
													<input type="checkbox" id="switch0001" /> 
													<label for="switch0001" data-on-label="ON" data-off-label="OFF"></label>
												</div>
												<div class="set-title">
													<h5>Personalization</h5>
												</div>	
												<div class="setting-row">
													<span>Search Privacy</span>
													<p>use sites you visit to improve which recommendation and ads you see. <a href="#" title="">Learn more</a>
													</p>
													<input type="checkbox" id="switch0002" /> 
													<label for="switch0002" data-on-label="ON" data-off-label="OFF"></label>
												</div>
												<div class="setting-row">
													<span>Search Privacy</span>
													<p>use information from our partners to improve which ads you see<a href="#" title="">Learn more</a>
													</p>
													<input type="checkbox" id="switch0003" /> 
													<label for="switch0003" data-on-label="ON" data-off-label="OFF"></label>
												</div>
											</form>
										</div>
									</div><!-- privacy -->
									<div class="tab-pane fade" id="security" role="tabpanel">
										<div class="set-title">
											<h5>Security Setting</h5>
											<span>trun on two factor authentication and check your list of connected device to keep your account posts safe <a href="#" title="">Learn More</a>.</span>
										</div>
										<div class="seting-box">
											<p>to turn on two-factor authentication, you must <a href="#" title=""> confirm Your Email </a> and <a href="#" title="">Set Password</a></p>
											<div class="set-title">
												<h5>Connected Devicese</h5>
											</div>
											<p>This is a list of devices that have logged into your account, Revok any session that you do not recognize. <a href="#" title="">Hide Sessions</a></p>
											<span>Last Accessed</span>
											<p>August 30, 2020 12:25AM</p>
											<span>Location</span>
											<p>Berlin, Germany (based on IP = 103.233.24.5)</p>
											<span>Device Type</span>
											<p>Chrome on windows 10</p>
										</div>
									</div><!-- security -->
									<div class="tab-pane fade" id="apps" role="tabpanel">
										<div class="set-title">
											<h5>Apps</h5>
											<span>Keep track of everywhere you have login with your pintik profile and remove access from apps you are no longer using with pitnik <a href="#" title="">Learn more</a></span>
										</div>
										<p class="p-info">You have not approved any app</p>
									</div><!-- apps -->
								</div>
							</div>
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