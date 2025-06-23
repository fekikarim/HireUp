<?php

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";



?>


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<style>
	.popup-container {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background-color: #fff;
		border-radius: 8px;
		box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
		z-index: 9999;
		padding: 20px;
		max-width: 400px;
	}

	.popup-content {
		text-align: center;
	}

	.popup-header {
		margin-bottom: 20px;
		padding-bottom: 10px;
		border-bottom: 1px solid #ccc;
	}

	.popup-header h2 {
		margin: 0;
		font-size: 24px;
		color: #333;
	}

	.popup-close {
		position: absolute;
		top: 10px;
		right: 10px;
		cursor: pointer;
		font-size: 24px;
		color: #999;
	}

	.popup-body {
		padding: 20px 0;
	}

	.popup-link {
		display: block;
		margin-bottom: 10px;
		color: #007bff;
		text-decoration: none;
		font-size: 18px;
	}

	.popup-link i {
		margin-right: 10px;
	}

	.popup-link:hover {
		text-decoration: underline;
	}

	.popup-body p {
		margin: 10px 0;
		font-size: 16px;
		color: #666;
	}
</style>


<!-- header -->
<div class="page_header">

	<section class="ls s-py-15 text-center">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-lg-4">
					<div class="d-lg-flex justify-content-lg-end align-items-lg-center">
						<span class="social-icons top">
							<a href="https://www.facebook.com/profile.php?id=61557532202485" class="fa fa-facebook"
								title="facebook"></a>
							<a href="https://www.instagram.com/hire.up.tn/" class="fa fa-instagram"
								title="instagram"></a>
							<a href="#" class="fa fa-google" title="google"></a>
							<a href="#" class="fa fa-linkedin" title="linkedin"></a>
							<a href="#" class="fa fa-pinterest-p" title="pinterest"></a>
						</span>
					</div>
				</div>
				<div class="col-lg-4 text-center">
					<div class="text-center">
						<div class="header_logo_center">
							<a href="<?php echo $current_url . "./index.php" ?>" class="logo">
								<span class="logo_text">Hire</span>
								<span class="logo-img-front">
									<img class="img-front" alt="" />
								</span>
								<span class="logo_text">Up</span>
							</a>
						</div>
						<!-- eof .header_left_logo -->
					</div>
				</div>
				<div class="col-lg-4">
					<div class="button-container">

						<?php if ($user_id) {
							?>
							<!-- Profile Dropdown -->
							<div class="dropdown ms-auto">


								<a href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown"
									aria-expanded="false" class="d-flex align-items-center justify-content-center mx-3"
									style="height: 100%;">

									<img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>"
										alt="Profile Photo" class="rounded-circle" width="50" height="50">
								</a>
								<p style="margin-bottom: -35px;">
									<?= $profile['profile_first_name'] . ' ' . $profile['profile_family_name'] ?></p>

								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
									<h5 class="dropdown-header">Account</h5>
									<li><a class="dropdown-item"
											href="<?php echo $current_url . "/view/front_office/profiles_management/profile.php" ?>">Profile</a>
									</li>

									<li><a class="dropdown-item"
											href="<?php echo $current_url . "/view/front_office/jobs management/career_explorers.php" ?>">Career
											Explorers</a>
									</li>

									<?php
									if ($user_role == 'admin') {
										?>
										<li><a class="dropdown-item text-success"
												href="<?php echo $current_url . "/view/back_office/main dashboard" ?>">Dashboard</a>
										</li>
										<?php
									}
									?>

									<li>
										<hr class="dropdown-divider">
									</li>
									<li><a class="dropdown-header"
											href="<?php echo $current_url . "/view/front_office/profiles_management/subscription/subscriptionCards.php"; ?>">Try
											Premium for $0</a></li>
									<li>
										<hr class="dropdown-divider">
									</li>
									<li><a class="dropdown-item"
											href="<?php echo $current_url . "/view/front_office/profiles_management/profile-settings-privacy.php"; ?>">Settings
											& Privacy</a></li>
									<li><a class="dropdown-item" href="#">Help</a></li> <!-- REST -->
									<li><a class="dropdown-item" href="#">Language</a></li> <!-- REST -->
									<li>
										<hr class="dropdown-divider">
									</li>
									<h5 class="dropdown-header">Manage</h5>
									<li><a class="dropdown-item" href="#">Posts & Activity</a></li>
									<li><a class="dropdown-item"
											href="<?php echo $current_url . "/view/front_office/jobs management/jobs_list.php"; ?>">Jobs</a>
									</li>
									<li>
										<hr class="dropdown-divider">
									</li>
									<!-- Reporting Header -->
									<h5 class="dropdown-header">Report</h5>
									<li><a class="dropdown-item" href="javascript:void(0)" onclick="openPopup()">Give
											Feedback</a></li>
									<li>
										<hr class="dropdown-divider">
									</li>
									<li><a class="dropdown-item"
											href="<?php echo $current_url . "/View/front_office/Sign In & Sign Up/logout.php"; ?>">Logout</a>
									</li>
								</ul>
							</div>
							<?php
						} else {
							?>
							<a class="transparent-button"
								href="<?php echo $current_url . "View/front_office/Sign In & Sign Up\authentication-login.php"; ?>">Sign
								In</a>
							<a class="primary-button"
								href="<?php echo $current_url . "View/front_office/Sign In & Sign Up/authentication-register.php"; ?>">Sign
								Up</a>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- header with single Bootstrap column only for navigation and includes. Used with topline and toplogo sections. Menu toggler must be in toplogo section -->
	<header class="ls s-bordertop nav-narrow justify-nav-center text-center">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-xl-12">
					<div class="nav-wrap">
						<!-- main nav start -->
						<nav class="top-nav">
							<ul class="nav sf-menu">
								<li class="<?= ($active_page == 'index') ? 'active' : ''; ?>">
									<a href="<?php echo $current_url . "./index.php" ?>">Homepage</a>
								</li>

								<li class="<?= ($active_page == 'profile') ? 'active' : ''; ?>">
									<!-- <a href="./profiles_management/profile.php">Profile</a> -->
									<a class="logo-img-front" href=<?php echo $current_url . "/view/front_office/profiles_management/profile.php" ?>>Profile</a>
								</li>
								<!-- eof pages -->

								<li class="<?= ($active_page == 'jobs') ? 'active' : ''; ?>">
									<a
										href="<?php echo $current_url . "/view/front_office/jobs management/jobs_list.php" ?>">Jobs</a>
								</li>

								<!-- blog -->
								<li class="<?= ($active_page == 'ads') ? 'active' : ''; ?>">
									<a href="<?php echo $current_url . "/view/front_office/ads/view_ads.php" ?>">Ads</a>
								</li>
								<!-- eof blog -->

								<!-- contacts -->
								<li class="<?= ($active_page == 'msgs') ? 'active' : ''; ?>">
									<a
										href="<?php echo $current_url . "/view/front_office/messenger/messaging.php" ?>">MessengUp</a>
								</li>
								<!-- eof contacts -->

								<li class="<?= ($active_page == 'report') ? 'active' : ''; ?>">
									<a
										href="<?php echo $current_url . "/view/front_office/reclamation/rec_list.php" ?>">Report</a>
								</li>

								<li class="<?= ($active_page == 'about') ? 'active' : ''; ?>">
									<a href="<?php echo $current_url . "/about.php" ?>">About</a>
								</li>


							</ul>
						</nav>
						<!-- eof main nav -->
					</div>
				</div>
			</div>
		</div>

		<!-- header toggler -->

		<span class="toggle_menu">
			<span></span>
		</span>
	</header>

</div>

<!-- Popup Container -->
<div id="reportPopup" class="popup-container" style="display: none;">
	<div class="popup-content">
		<!-- Popup Header -->
		<div class="popup-header">
			<span class="popup-close" onclick="closePopup()">&times;</span>
			<h2>Report to HireUp</h2>
		</div>
		<!-- Popup Body -->
		<div class="popup-body">
			<!-- View My Reports Link -->
			<a href="<?php echo $current_url . "/view/front_office/reclamation/rec_list.php" ?>" class="popup-link">
				<i class="fas fa-clipboard-list"></i> View my reports
			</a>
			<p><?php echo generateSubtitle(); ?></p>
			<hr>
			<!-- Help Us Improve Link -->
			<a href="<?php echo $current_url . "/view/front_office/reclamation/reclamation.php" ?>" class="popup-link">
				<i class="fas fa-comments"></i> Help us improve HireUp
			</a>
			<!-- Help Us Improve Subtitle -->
			<p><?php echo generateFeedbackSubtitle(); ?></p>
		</div>
	</div>
</div>


<?php
function generateSubtitle()
{
	return "Here you can report issues or inappropriate content to the HireUp team.";
}

function generateFeedbackSubtitle()
{
	return "We appreciate your feedback! Share your thoughts and suggestions to help us enhance your HireUp experience.";
}
?>


<script>
	// Function to open the popup
	function openPopup() {
		document.getElementById('reportPopup').style.display = 'block';
	}

	// Function to close the popup
	function closePopup() {
		document.getElementById('reportPopup').style.display = 'none';
	}
</script>
<!-- end header -->