<?php

include_once __DIR__ . '/../../../Controller/reclamation_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/reponse_con.php';
require_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Controller/articleC.php';

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(0, '/', '', true, true);
	session_start();
}

// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");
$profileController = new ProfileC();
$reponseC = new repCon("reponses");
$articleC = new ArticleC();

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

	<script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />

	<style>
		.container-reponse {
			border: 2px solid #dedede;
			background-color: #f1f1f1;
			border-radius: 5px;
			padding: 10px;
			margin: 10px 0;
		}

		.container-reponse::after {
			content: "";
			clear: both;
			display: table;
		}

		.time-right {
			float: right;
			color: #aaa;
		}
	</style>

	<!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>


	<?php
	$block_call_back = 'false';
	$access_level = "else";
	include ('./../../../View/callback.php');
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
				include ('../front_header.php');
				?>

				<section class="page_title cs s-py-25" style="background-color: #750E21 !important;">
					<div class="divider-100" style="margin-bottom: 150px;"></div>
				</section>

				<section class="page_title cs s-py-25" style="background-color: #750E21 !important;">
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

				<div class="d-none d-lg-block divider-50"></div>

				<div class="text-center align-items-center justify-content-center">
					<button onclick="window.location.href = './reclamation.php'" class="btn btn-outline-primary"><i
							class="fas fa-exclamation-circle"></i> Send Report</button>
				</div>

				<div class="d-none d-lg-block divider-35"></div>

				<section class="ls s-py-50 divider-35">
					<div class="container">

						<?php foreach ($list_rec as $rec): ?>
							<div class="row mt-5">
								<div class="col-lg-10 offset-lg-1">
									<div class="vertical-item content-padding bordered text-start">
										<div class="item-content">
											<!-- <h2><?php //echo $rec['sujet']; ?></h2> -->
											<h2><?php echo $articleC->filterBadWords($rec['sujet']); ?></h2>
											<hr>
											<!-- <p><b><?php //echo $rec['description']; ?></b></p> -->
											<p><b><?php echo $articleC->filterBadWords($rec['description']); ?></b></p>
											<h6 class="time-right"><?php echo $rec['date_creation']; ?></h6>

											<br>
											<hr>

											<!-- Display responses -->
											<?php
											// Get responses for this reclamation
											$responses = $reponseC->listRepByIdec($rec['id']);

											// Check if there are any responses
											if (!empty($responses)) {
												// If there are responses, display each one
												foreach ($responses as $response) {
													echo '<div class="container-reponse border rounded p-3 mt-3 text-primary">';
													//echo '<p><b>' . $response['contenu'] . '</b></p>';
													echo '<p><b>' . $articleC->filterBadWords($response['contenu']) . '</b></p>';
													echo '<p class="time-right">Response Date: <i>' . $response['date_reponse'] . '</i></p>';
													echo '</div>';
												}
											} else {
												// If there are no responses, display a default message
												echo '<div class="container-reponse border rounded p-3 mt-3">';
												echo '<p class="text-muted">No response yet.</p>';
												echo '</div>';
											}
											?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>


			</div>
			</section>


			<!-- Footer -->
			<?php include (__DIR__ . '/../../../View/front_office/front_footer.php') ?>
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

		<?php
		include './../jobs management/chatbot.php';
		?>
		<script src="./../../../front office assets/js/chatbot.js"></script>

	</body>


</html>