<!DOCTYPE html>
<html lang="en">

<head>
  <title>HireUp</title>
  <meta charset="utf-8" />
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

  <link rel="stylesheet" href="../../../front office assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../../front office assets/css/animations.css" />
  <link rel="stylesheet" href="../../../front office assets/css/font-awesome.css" />
  <link rel="stylesheet" href="../../../front office assets/css/main.css" class="color-switcher-link" />
  <script src="../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>

  <link href="../../../front office assets/images/HireUp_icon.ico" rel="icon">

  <script src="../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>

<?php

require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


$userC = new userCon("user");
$profileController = new ProfileC();

$user_id = '';
$user_profile_id = '';

//get user_profile id
if (isset($_SESSION['user id'])) {
  $user_id = htmlspecialchars($_SESSION['user id']);
  $user_profile_id = $profileController->getProfileIdByUserId($user_id);
  $profile = $profileController->getProfileById($user_profile_id);
}

?>

<body>

  <?php
  $block_call_back = 'true';
  $access_level = "else";
  $special_case = 'user_banned';
  include ('./../../../View/callback.php')
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

      <!--eof topline-->

      <!-- header -->
      <?php
      $active_page = 'banned';
      include ('../front_header.php');
      ?>



      <section class="s-pt-75 s-pb-100 error-404 not-found page_404">
        <div class="container">
          <div class="row">
            <div class="d-none d-lg-block divider-60"></div>

            <div class="col-sm-12 text-center">
              <header class="page-header highlight">
                <h3>Banned!</h3>
                <p class="text-uppercase">Sorry, your account has been banned.</p>
                <h6>If you believe this is an error, please contact support.</h6>
              </header>

              <!-- .page-header -->

              <div class="page-content">
                <div id="search-404" class="widget widget_search">
                  <form role="search" method="get" class="search-form">
                    <p>
                      <a href="#" class="btn btn-outline-darkgrey">Contact Support</a>
                      <!-- popup feha sbab moqna3 bch nahiew el bannnnnn!!!!!! -->
                    </p>
                  </form>
                </div>
              </div>
              <!-- .page-content -->
            </div>
          </div>
        </div>
      </section>


      <!-- header -->
      <?php
      include ('../front_footer.php');
      ?>

    </div>
    <!-- eof #box_wrapper -->
  </div>
  <!-- eof #canvas -->

  <script src="../../../front office assets/js/compressed.js"></script>
  <script src="../../../front office assets/js/main.js"></script>
</body>

</html>