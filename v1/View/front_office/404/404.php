<!DOCTYPE html>
<html lang="en">

<head>
  <title>HireUp</title>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="./../../../front office assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./../../../front office assets/css/animations.css">
  <link rel="stylesheet" href="./../../../front office assets/css/font-awesome.css">
  <link rel="stylesheet" href="./../../../front office assets/css/main.css" class="color-switcher-link">
  <script src="./../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
  <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon" />

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
      <form method="get" class="searchform search-form" action="http://webdesign-finder.com/">
        <div class="form-group">
          <input type="text" value="" name="search" class="form-control" placeholder="Search keyword"
            id="modal-search-input" />
        </div>
        <button type="submit" class="btn">Search</button>
      </form>
    </div>
  </div>

  <!-- wrappers for visual page editor and boxed version of template -->
  <div id="canvas">
    <div id="box_wrapper">
      <!-- template sections -->

      <!-- header -->
      <?php
      $active_page = '404';
      include ('../front_header.php');
      ?>
      <!-- end header -->

      <section class="page_title cs s-py-25" style="background-color: #282A3A !important;">
        <div class="divider-100" style="margin-bottom: 150px;"></div>
      </section>

      <section class="s-pt-75 s-pb-100 error-404 not-found page_404" style="background-color: #282A3A !important;">
				<div class="container">
					<div class="row">

						<div class="d-none d-lg-block divider-60"></div>

						<div class="col-sm-12 text-center">

							<header class="page-header highlight">
								<h3>404</h3>
								<p class="text-uppercase">
									Oops, page not found!
								</p>
								<h6>
									You can search what interested:
								</h6>
							</header>

							<!-- .page-header -->

							<div class="page-content">
								<div id="search-404" class="widget widget_search">

									<form role="search" method="get" class="search-form" action="http://webdesign-finder.com/">
										<label for="search-form-404">
											<span class="screen-reader-text">Search for:</span>
										</label>
										<input type="search" id="search-form-404" class="search-field" placeholder="Search keyword" value="" name="search ">
										<h6 class="page-header py-30">
											or
										</h6>
										<p>
											<a href="../../../index.html" class="btn btn-outline-darkgrey">Homepage</a>
										</p>
									</form>
								</div>
							</div>
							<!-- .page-content -->
						</div>
					</div>
				</div>
			</section>

      <!-- Ad -->
      <?php
      $add_type = "center";
      require __DIR__ . '/../../../View/front_office/ads/ads_containers.php'
        ?>
      <!-- End Ad -->

      <!-- Footer -->
      <?php include (__DIR__ . '/../../../View/front_office/front_footer.php') ?>
      <!-- End Footer -->

      <?php
      include 'chatbot.php';
      ?>

    </div>
    <!-- eof #box_wrapper -->
  </div>
  <!-- eof #canvas -->

  <script src="./../../../front office assets/js/compressed.js"></script>
  <script src="./../../../front office assets/js/main.js"></script>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
  
</body>

</html>