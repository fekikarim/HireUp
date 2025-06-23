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

	<link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon">
	<link rel="stylesheet" href="../../../front office assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../front office assets/css/animations.css">
	<link rel="stylesheet" href="../../../front office assets/css/font-awesome.css">
	<link rel="stylesheet" href="../../../front office assets/css/main.css" class="color-switcher-link">
	<script src="../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>

	<script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />

	<!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>


	<?php
	$block_call_back = 'false';
	$access_level = "else";
	include ('./../../../View/callback.php');
	?>

	<div class="preloader">
		<div class="preloader_image"></div>
	</div>

	<!-- search modal -->
	<div class="modal" tabindex="-1" role="dialog" aria-labelledby="search_modal" id="search_modal">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="widget widget_search">
			<form method="get" class="searchform search-form" action="http://webdesign-finder.com/">
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
			$active_page = 'report';
			include ('../front_header.php');
			?>


			<section class="page_title cs s-py-25" style="background-color: #344955 !important;">
				<div class="divider-100" style="margin-bottom: 150px;"></div>
			</section>

			<section class="page_title cs s-py-25" style="background-color: #344955 !important;">
				<div class="container">
					<div class="row">

						<div class="divider-50"></div>

						<div class="col-md-12 text-center">
							<h1 class="">Contact Us</h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="../../../index.php">Home</a>
								</li>

								<li class="breadcrumb-item active">
									Contact Us
								</li>
							</ol>
						</div>

						<div class="divider-50"></div>

					</div>
				</div>
			</section>


			<section class="lss-overlay s-map-light s-py-130 c-gutter-60 container-px-30">
				<div class="container ">
					<div class="row">
						<div id="alertContainer"></div>

						<div class="divider-30 d-none d-xl-block"></div>

						<div class="col-lg-8 offset-lg-2 animate" data-animation="slideDown">

							<form class="black-bg c-mb-20 c-gutter-20" method="post"
								action="../../../View/back_office/reclamations managment/add_rec_front.php">

								<div class="row">

									<div class="col-sm-12">
										<div class="ds form-group has-placeholder">
											<label for="sujet">Subject
												<span class="required">*</span>
											</label>
											<input type="text" aria-required="true" size="30" value="" name="sujet"
												id="sujet" class="form-control text-normal" placeholder="Subject">
											<div id="sujet_error" style="color: red;"></div>
										</div>
									</div>

									<!-- <div class="col-sm-6">
										<div class="ds form-group has-placeholder">
											<label for="id_user">User ID
												<span class="required">*</span>
											</label>
											<input type="text" aria-required="true" size="30" value="" name="id_user" id="id_user" class="form-control" placeholder="User id">
											<div id="description_error" style="color: red;"></div>
										</div>
									</div> -->

									<input type="hidden" value="<?php echo $user_id ?>" name="id_user" id="id_user">

								</div>


								<div class="row">
									<div class="col-sm-12">
										<div class="ds form-group has-placeholder">
											<label for="description">Description</label>
											<textarea aria-required="true" rows="6" cols="4" name="description"
												id="description" class="form-control text-normal"
												placeholder="Description"></textarea>
											<div id="id_user_error" style="color: red;"></div>
										</div>
									</div>
								</div>
								<div class="row">

									<div class="col-sm-12 text-center mt-10">

										<div class="form-group">
											<button type="submit" id="add_btn" name="add_btn" class="btn-color"
												onclick="return verif_reclamation_managemet_inputs_front()">
												Send Now
											</button>

										</div>
									</div>

								</div>

							</form>
						</div>
						<!--.col-* -->

						<div class="divider-30 d-none d-xl-block"></div>
					</div>
				</div>
			</section>


			<section class="ds section_gradient gradient-background py-50">
				<div class="container">
					<div class="row">
						<div class="col-md-4 text-center animate" data-animation="pullUp">
							<div class="info-block">
								<p>
									Call Us 24/7
								</p>
								<h3>
									+123-456-7890
								</h3>
							</div>
						</div>
						<div class="col-md-4 text-center animate" data-animation="pullUp">
							<div class="info-block">
								<p>
									Email Address
								</p>
								<h3>
									example@example.com
								</h3>
							</div>
						</div>
						<div class="col-md-4 text-center animate" data-animation="pullUp">
							<div class="info-block">
								<p>
									Open Hours
								</p>
								<h3>
									Daily 9:00-20:00
								</h3>
							</div>
						</div>
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