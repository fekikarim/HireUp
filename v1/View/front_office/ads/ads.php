<!DOCTYPE html>

<html>

<head>
	<title>HireUp Ads</title>
	<meta charset="utf-8" />

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

	<link rel="stylesheet" href="../../../front office assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="../../../front office assets/css/animations.css" />
	<link rel="stylesheet" href="../../../front office assets/css/font-awesome.css" />
	<link rel="stylesheet" href="../../../front office assets/css/main.css" class="color-switcher-link" />
	<script src="../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>

	<link href="../../../front office assets/images/HireUp_icon.ico" rel="icon" />
	<link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />

	<script>
		// Enable Bootstrap dropdown functionality
		var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
		var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
			return new bootstrap.Dropdown(dropdownToggleEl);
		});
	</script>

	<!-- voice recognation -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>


<?php

include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

$userC = new userCon("user");
$profileController = new ProfileC();

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
					<input type="text" value="" name="search" class="form-control" placeholder="Search keyword"
						id="modal-search-input">
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
			include ('../front_header.php');
			?>
			<!-- end header -->

			<section class="page_title cs s-py-25" style="background-color: #2E4F4F !important;">
				<div class="divider-100" style="margin-bottom: 150px;"></div>
			</section>

			<section class="page_title cs s-py-25" style="background-color: #2E4F4F !important;">
				<div class="container">
					<div class="row">

						<div class="divider-50"></div>

						<div class="col-md-12 text-center">
							<h1 class="">Advertisements Requests</h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="../../../index.php">Home</a>
								</li>

								<li class="breadcrumb-item active">
									Advertisements Requests
								</li>
							</ol>
						</div>

						<div class="divider-50"></div>

					</div>
				</div>
			</section>


			<section class="pt-20 pb-30 s-py-md-75 s-py-lg-130 candidate-page">
				<div class="container">
					<div class="row">
						<div class=" col-sm-12 contact-header text-center animate" data-animation="pullDown">
							<h5 style="font-size: xx-large;">
								Submit
							</h5>
							<h4>
								Advertisements Requests
							</h4>
						</div>
						<form class="contact-form contact2 c-mb-20 animate" data-animation="pullUp"
							action="./add_dmd.php" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-xs-12 col-sm-6">
									<div class="col-c-mb-60 form-group has-placeholder">
										<label for="titre">Title
											<span class="required">*</span>
										</label>
										<input type="text" aria-required="true" size="200" value="" id="titre"
											name="titre" class="form-control" placeholder="Title">
										<div id="titre_error" style="color: red;"></div>
									</div>
									<div class="col-c-mb-60 form-group has-placeholder">
										<label for="budget">Budget
											<span class="required">*</span>
										</label>
										<input type="text" aria-required="true" size="200" class="form-control"
											id="budget" name="budget" placeholder="Budget">
										<div id="budget_error" style="color: red;"></div>
									</div>
									<div class="col-c-mb-60 form-group has-placeholder">
										<label for="objectif">Objective
											<span class="required">*</span>
										</label>
										<input type="text" class="form-control" id="objectif" name="objectif"
											aria-required="true" size="200" value="" placeholder="Objective">
										<div id="objectif_error" style="color: red;"></div>
									</div>
									<div class="col-c-mb-60 form-group has-placeholder">
										<label for="dure">Duration
											<span class="required">*</span>
										</label>
										<input type="text" aria-required="true" size="200" value="" id="dure"
											name="dure" class="form-control" placeholder="Duration">
										<div id="dure_error" style="color: red;"></div>
									</div>

									<div class="col-c-mb-60 form-group">
										<input type="file" class="custom-file-input button" id="image_publication"
											name="image_publication" accept="image/*">
										<label class="custom-file-label" for="image_publication">Image</label>
										<div id="img_error" style="color: red;"></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6">
									<div class="form-group has-placeholder">
										<label for="contenu">Contenue</label>
										<textarea aria-required="true" rows="6" cols="80" id="contenu" name="contenu"
											class="form-control" placeholder="Contenue"></textarea>
									</div>
									<div id="contenu_error" style="color: red; text-align: end;"></div>
								</div>
								<div class="col-c-mb-60 form-group has-placeholder mr-3 ml-3">
									<label for="link">Ad Link
										<span class="required">*</span>
									</label>
									<input type="url" aria-required="true" size="200" id="link" name="link"
										class="form-control" placeholder="Ad Link">
									<div id="link_error" style="color: red;"></div>
								</div>
							</div>
							<div class="row mt-1">
								<div class="col-sm-12">
									<div class="form-group text-center">
										<button type="submit" onclick="return verif_pub_manaet_inputs()"
											class="btn theme_button">Submit</button>
									</div>
								</div>
							</div>

							<div class="mb-3" id="error_global" style="color: red; text-align: center;"></div>
							<div class="mb-3" id="success_global" style="color: green; text-align: center;"></div>

						</form>
					</div>
				</div>
			</section>

			<!-- Ad -->
			<section class="candidate-page text-center">
				<div class="container">
					<div class="row">
						<div class="card mb-3">
							<h4 class="text-center" style="margin-top: 15px; margin-left: 15px;">Ad</h4>
							<?php
							$add_type = "center";
							require __DIR__ . '/../../../View/front_office/ads/ads_containers.php'
								?>
						</div>
					</div>
				</div>
			</section>
			<!-- End Ad -->

			<!-- Footer -->
			<?php include (__DIR__ . '/../../../View/front_office/front_footer.php') ?>
			<!-- End Footer -->

			<?php
			include './../jobs management/chatbot.php';
			?>



		</div>
		<!-- eof #box_wrapper -->
	</div>
	<!-- eof #canvas -->


	<script src="../../../front office assets/js/compressed.js"></script>
	<script src="../../../front office assets/js/main.js"></script>
	<script src="./dmd_management.js"></script>
	<script src="./../../../front office assets/js/chatbot.js"></script>


	<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>


	<!-- voice recognation -->
	<script type="text/javascript"
		src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


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