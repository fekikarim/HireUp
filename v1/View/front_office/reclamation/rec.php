<?php

include '../../../Controller/reclamation_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(0, '/', '', true, true);
	session_start();
}

// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");
$profileController = new ProfileC();

$user_id = '';
$user_profile_id = '';

if (isset($_SESSION['user id'])) {

	$user_id = htmlspecialchars($_SESSION['user id']);

	// Get profile ID from the URL
	$user_profile_id = $profileController->getProfileIdByUserId($user_id);

	$profile = $profileController->getProfileById($user_profile_id);
}

$list_rec = $recC->listRecsByIdUser($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>HireUp Report</title>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="icon" type="image/png" href="./../../../front office assets\images\HireUp_icon.ico" />

	<link rel="stylesheet" href="../../../front office assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../front office assets/css/animations.css">
	<link rel="stylesheet" href="../../../front office assets/css/font-awesome.css">
	<link rel="stylesheet" href="../../../front office assets/css/main.css" class="color-switcher-link">
	<script src="../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>

	<!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
	

</head>

<body>


	<?php
	$block_call_back = 'false';
	$access_level = "else";
	include('./../../../View/callback.php');
	?>

	<body>
		<!--[if lt IE 9]>
		<div class="bg-danger text-center">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" class="color-main">upgrade your browser</a> to improve your experience.</div>
	<![endif]-->

		<div class="preloader">
			<div class="preloader_image"></div>
		</div>


		<!-- wrappers for visual page editor and boxed version of template -->
		<div id="canvas">
			<div id="box_wrapper">

				<!-- header -->
				<?php 
				$active_page = 'report'; 
				include('../front_header.php') ;
				?>

				<section class="page_title cs gradientvertical-background s-py-25">
					<div class="container">
						<div class="row">

							<div class="divider-50"></div>

							<div class="col-md-12 text-center">
								<h1 class="">My Reports</h1>
								<ol class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="../../../index.php">Home</a>
									</li>

									<li class="breadcrumb-item active">
										My Reports
									</li>
								</ol>
							</div>

							<div class="divider-50"></div>

						</div>
					</div>
				</section>


				<section class="ls s-py-50 s-py-lg-100">
					<div class="container">

						<?php foreach ($list_rec as $rec) : ?>
							<div class="row mt-5">
								<div class="col-lg-10 offset-lg-1">
									<div class="vertical-item content-padding bordered text-center">
										<div class="item-content">
											<h2><?php echo $rec['sujet']; ?></h2>
											<p><?php echo $rec['description']; ?></p>
											<h6><?php echo $rec['date_creation']; ?></h6>

											<!-- Response container -->
											<div class="response-container">
												<?php
												
												// Get responses for this reclamation
												$responses = $repC->listRepByIdec($rec['id']);

												if (!empty($responses)) {
													// Display responses
													foreach ($responses as $response) {
														echo '<div class="response-item">';
														echo '<p>' . $response['contenu'] . '</p>';
														echo '<p>' . $response['date_reponse'] . '</p>';
														// Add more details if needed
														echo '</div>';
													}
												} else {
													// Display default message if there are no responses
													echo '<p>No responses yet.</p>';
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>


			</div>
			</section>


			<!-- Footer -->
			<?php include(__DIR__ . '/../../../View/front_office/front_footer.php') ?>
			<!-- End Footer -->


		</div>
		<!-- eof #box_wrapper -->
		</div>
		<!-- eof #canvas -->

		<script src="../../../front office assets/js/compressed.js"></script>
		<script src="../../../front office assets/js/main.js"></script>
		<script src="../../../front office assets/js/switcher.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

		<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
		<script src="../../../View/back_office/reclamations managment/recs_management_js.js"></script>

		<!-- voice recognation -->
		<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

	</body>


</html>