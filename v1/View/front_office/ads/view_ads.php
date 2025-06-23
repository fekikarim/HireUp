<!DOCTYPE html>
<html lang="en">
<head>
	<title>HireUp View Ads</title>
	<meta charset="utf-8" />

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

	<link rel="stylesheet" href="../../../front office assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="../../../front office assets/css/animations.css" />
	<link rel="stylesheet" href="../../../front office assets/css/font-awesome.css" />
	<link rel="stylesheet" href="../../../front office assets/css/main.css" class="color-switcher-link" />
	<script src="../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

	<link href="../../../front office assets/images/HireUp_icon.ico" rel="icon" />

	<link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />

	<style>
		/* Popup card styles */
		#popup-card {
			display: none;
			/* Hide the popup card by default */
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 9999;
			/* Ensure the popup card is above other elements */
			background-color: rgba(255, 255, 255, 0.9);
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
			max-width: 80%;
			/* Adjust the maximum width as needed */
			width: 60%;
			max-width: 60%;
			min-width: auto;
			height: 60%;
			max-height: 60%;
			min-height: auto;
		}

		#popup-card .popup-content {
			text-align: center;
		}

		#popup-card .popup-content h2 {
			margin-bottom: 10px;
		}

		#popup-card .popup-content p {
			margin-bottom: 5px;
		}

		#popup-card .close {
			position: absolute;
			top: 10px;
			right: 10px;
			cursor: pointer;
			font-size: 20px;
		}

		/* Overlay styles */
		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			z-index: 999;
			/* Ensure the overlay is above other elements */
		}
	</style>

	<!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>
<?php

include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

include_once __DIR__ . '/../../../Controller/dmd_con.php';

$userC = new userCon("user");
$profileController = new ProfileC();

$dmdCon = new dmdCon();

$user_id = null;

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(0, '/', '', true, true);
	session_start();
}

if (isset($_SESSION['user id'])) {
	$user_id = htmlspecialchars($_SESSION['user id']);

	$user_role = $userC->get_user_role_by_id($user_id);

	$user_banned = $userC->get_user_banned_by_id($user_id);

	// Get profile ID from the URL
	$profile_id = $profileController->getProfileIdByUserId($user_id);

	// Fetch profile data from the database
	$profile = $profileController->getProfileById($profile_id);
}

// Fetch all ads
$all_ads = $dmdCon->searchdmd('paid', '', 'accepted');

// Fetch payed ads
$payed_ads = $dmdCon->searchdmd('paid', 'payed', 'accepted');

// Fetch unpayed ads
$unpayed_ads = $dmdCon->searchdmd('paid', 'pending', 'accepted');

?>


<body>

	<div class="preloader">
		<div class="preloader_image"></div>
	</div>

	<!-- search modal -->
	<div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="widget widget_search">
			<form method="get" class="searchform search-form">
				<div class="form-group">
					<input type="text" value="" name="search" class="form-control" placeholder="Search keyword" id="modal-search-input">
				</div>
				<button type="submit" class="btn">Search</button>
			</form>
		</div>
	</div>

	<!-- wrappers for visual page editor and boxed version of template -->
	<div id="canvas">
		<div id="box_wrapper">

			<!-- header -->
			<?php 
			$active_page = 'ads';
			include('../front_header.php') ;
			?>
			<!-- end header -->

            <section class="page_title cs s-py-25" style="background-color: #2C3333 !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #2C3333 !important;">
				<div class="container">
					<div class="row">

						<div class="divider-50"></div>

						<div class="col-md-12 text-center">
							<h1 class="">View Ads Requests</h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="../../../index.php">Home</a>
								</li>

								<li class="breadcrumb-item active">
									View Ads Requests
								</li>
							</ol>
						</div>

						<div class="divider-50"></div>

					</div>
				</div>
			</section>

			<div class="d-none d-lg-block divider-50"></div>

			<div class="text-center align-items-center justify-content-center">
				<button onclick="window.location.href = './ads.php'" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i>Ads</button>
			</div>




			<section class="s-pt-15 s-pb-50 pb-10">
				<div class="container">
					<div class="row">



						<div class="d-none d-lg-block divider-35"></div>


						<div class="col-lg-12">
							<div class="row justify-content-center">
								<div class="col-md-10 col-xl-8">
									<div class="filters gallery-filters text-lg-right">
										<!-- Filter links for all, payed, and unpayed ads -->
										<a href="#" data-filter="*" class="active selected">ALL</a>
										<a href="#" data-filter=".corporate">Payed</a>
										<a href="#" data-filter=".business">Unpayed</a>
									</div>
								</div>
							</div>


							<div class="row isotope-wrapper masonry-layout c-gutter-30 c-mb-30 gallery-image-regular" data-filters=".gallery-filters">
								<!-- Loop through all ads and display them within the "ALL" frame -->
								<?php foreach ($all_ads as $ad) : ?>
									<div class="col-xl-6 col-sm-6 <?= $ad['paid'] === 'payed' ? 'corporate' : 'business' ?>">
										<div class="vertical-item item-gallery content-absolute text-center ds">
											<div class="item-media">
												<!-- Display ad image -->
												<img src="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>" alt="">
												<div class="media-links">
													<div class="links-wrap">
														<!-- Link to view ad details -->
														<a class="link-zoom photoswipe-link" title="" href="data:image/jpeg;base64,<?= base64_encode($ad['image']) ?>"></a>
														<a class="links-infos-dmd" href="javascript:displayPopup('<?= $ad['titre'] ?>', '<?= $ad['contenu'] ?>', '<?= $ad['objectif'] ?>', '<?= $ad['dure'] ?>', '<?= $ad['budget'] ?>')" title=""><i class="fa fa-info-circle"></i></a>
														<?php if ($ad['paid'] == 'pending') { ?>
															<a class="links-infos-dmd" title="" href="ads_payment.php?iddemande=<?= $ad['iddemande'] ?>"><i class="fa fa-credit-card"></i></a>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="item-content gradientdarken-background">
												<!-- Display ad title -->
												<h4><a href="javascript:void(0)"><?= $ad['titre'] ?></a></h4>
												<h5><a><i class="fa fa-coins mr-3"></i><?= $ad['budget'] ?></a></h5>
											</div>
										</div>
									</div>
								<?php endforeach; ?>

								<!-- Other frames for payed and unpayed ads -->
								<!-- Loop through payed ads and display them within the "Payed" frame -->

							</div>

							<!-- .isotope-wrapper-->

							<div class="row gallery">
								<div class="col-sm-12 text-center ">
									<a href="./view_ads.php" class="btn btn-outline-darkgrey">Load More</a>
								</div>
							</div>

						</div>

						<div class="d-none d-lg-block divider-100"></div>
					</div>

				</div>
			</section>

			<div id="popup-card" class="popup-card">
				<div class="popup-content">
					<span id="close-popup" class="close">&times;</span>
					<h1 id="popup-title" class="text-capitalize"></h1>
					<hr><br>
					<p id="popup-description"></p>
					<p id="popup-objective"></p>
					<p id="popup-duration"></p>
					<p id="popup-budget"></p>
					<!-- Add more fields here -->
				</div>
			</div>

			<!-- Footer -->
			<?php include(__DIR__ .'/../../../View/front_office/front_footer.php') ?>
        	<!-- End Footer -->

			<?php
            include './../jobs management/chatbot.php';
            ?>

			<script src="../../../front office assets/js/compressed.js"></script>
			<script src="../../../front office assets/js/main.js"></script>

			<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
			<script src="./../../../front office assets/js/chatbot.js"></script>

			<script>
				// Get the modal
				var modal = document.getElementById("popup-card");

				// Get the close button element
				var closeButton = document.getElementById("close-popup");

				// When the user clicks on the close button, hide the modal
				closeButton.onclick = function() {
					modal.style.display = "none";
				};


				function displayPopup(title, description, objective, duration, budget) {
					var popupTitle = document.getElementById("popup-title");
					var popupDescription = document.getElementById("popup-description");
					var popupObjective = document.getElementById("popup-objective");
					var popupDuration = document.getElementById("popup-duration");
					var popupBudget = document.getElementById("popup-budget");

					popupTitle.innerHTML = "<b>" + title + "</b>";
					popupDescription.innerHTML = "<b>Description:</b> " + description;
					popupObjective.innerHTML = "<b>Objective:</b> " + objective;
					popupDuration.innerHTML = "<b>Duration:</b> " + duration;
					popupBudget.innerHTML = "<b>Budget:</b> " + budget;

					modal.style.display = "block";
				}
			</script>

			<!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

			<?php

			//title error
			if (isset($_GET['error_titre'])) {
				// Retrieve and sanitize the error message
				$error = htmlspecialchars($_GET['error_titre']);
				// Inject the error message into the div element
				echo ("<script>document.getElementById('titre_error').innerText = '$error';</script>");
			}

			//global error
			if (isset($_GET['error_global'])) {
				// Retrieve and sanitize the error message
				$error = htmlspecialchars($_GET['error_global']);
				// Inject the error message into the div element
				echo ("<script>document.getElementById('error_global').innerText = '$error';</script>");
			}

			//global success
			if (isset($_GET['success_global'])) {
				// Retrieve and sanitize the error message
				$error = htmlspecialchars($_GET['success_global']);
				// Inject the error message into the div element
				echo ("<script>document.getElementById('success_global').innerText = '$error';</script>");
			}

			?>

</body>


</html>