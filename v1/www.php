<!DOCTYPE html>
<html class="no-js">

<head>
    <title>About HireUp</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="./front office assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./front office assets/css/animations.css">
    <link rel="stylesheet" href="./front office assets/css/font-awesome.css">
    <link rel="stylesheet" href="./front office assets/css/main.css" class="color-switcher-link">
    <script src="./front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="./front office assets/images/HireUp_icon.ico" rel="icon" />

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php

include_once __DIR__ . './Controller/user_con.php';
require_once __DIR__ . '/Controller/profileController.php';

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

    <?php
    $block_call_back = 'true';
    $access_level = "none";
    include('./View/callback.php')
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
                    <input type="text" value="" name="search" class="form-control" placeholder="Search keyword" id="modal-search-input">
                </div>
                <button type="submit" class="btn">Search</button>
            </form>
        </div>
    </div>



    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">
        <div id="box_wrapper">


            <?php include('./View/front_office/front_header.php') ?>

            <section class="page_title cs s-py-25" style="background-color: #0E2954 !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #0E2954 !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>
                        <div class="col-md-12 text-center">
                            <h1 class="">Creating a Win-Win-Win Situation</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="./index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Creating a Win-Win-Win Situation
                                </li>
                            </ol>
                        </div>
                        <div class="divider-50"></div>
                    </div>
                </div>
            </section>

            <section class="ls s-py-50 s-py-lg-100">
                <div class="container">
                    <div class="row">
                        <div class="d-none d-lg-block divider-60"></div>
                        <div class="col-lg-10 offset-lg-1">
                            <div class="vertical-item content-padding bordered text-center">
                                <div class="item-media">
                                    <div class="owl-carousel" data-margin="0" data-responsive-lg="1" data-responsive-md="1" data-responsive-sm="1" data-responsive-xs="1" data-dots="true">
                                        <a href="./front office assets/images/index/15.png" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/15.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/index/16.png" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/16.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/index/17.jpg" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/17.png" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h2>
                                        Our Win-Win-Win Philosophy
                                    </h2>
                                    <p>
                                        At HireUp, we believe in the power of creating a win-win-win situation. This concept is fundamental to our mission and vision, ensuring that our platform benefits everyone involved: employers, job seekers, and society as a whole.
                                    </p>
                                    <h3>What is a Win-Win-Win Situation?</h3>
                                    <p>
                                        The win-win-win situation is a holistic approach where all parties involved gain significant advantages. Here's how it works:
                                    </p>
                                    <ul class="list1">
                                        <li>
                                            <i class="color-main fa fa-briefcase"></i>
                                            <strong>Win for Employers:</strong> Employers find the best talent efficiently, reducing hiring time and costs, while gaining employees who are the perfect fit for their organization.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-user"></i>
                                            <strong>Win for Job Seekers:</strong> Job seekers gain access to opportunities that align with their skills and career aspirations, supported by tools and resources to help them succeed.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-globe"></i>
                                            <strong>Win for Society:</strong> By facilitating better job matches, HireUp contributes to economic growth, reduces unemployment, and supports the creation of more fulfilling careers, benefiting the community at large.
                                        </li>
                                    </ul>
                                    <h3>The Impact of HireUp on the Community</h3>
                                    <p>
                                        HireUp is more than just a recruitment platform; it's a catalyst for positive change in the community. Our innovative solutions and user-friendly interface help bridge gaps in the job market, promote diversity, and encourage inclusive hiring practices. By fostering connections between employers and job seekers, we help build stronger, more resilient communities.
                                    </p>
                                    <blockquote class="text-center">
                                        <p>
                                            "HireUp is committed to making a significant impact on the recruitment industry and society. Our goal is to create opportunities that benefit everyone involved, ensuring a brighter future for all."
                                        </p>
                                        <span>
                                            - The HireUp Team
                                        </span>
                                    </blockquote>
                                    <p>
                                        We highly recommend reading "Rich Dad Poor Dad" by Robert T. Kiyosaki to understand the importance of creating win-win scenarios and how financial education can empower individuals to achieve greater success.
                                    </p>
                                    <div>
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
                                        <a href="#" class="fa fa-twitter" title="twitter"></a>
                                        <a href="#" class="fa fa-instagram" title="instagram"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-none d-lg-block divider-60"></div>
                    </div>
                </div>
            </section>



            <!-- Footer -->
            <?php include('./View/front_office/front_footer.php') ?>
            <!-- End Footer -->


        </div>
        <!-- eof #box_wrapper -->
    </div>
    <!-- eof #canvas -->


    <script src="./front office assets/js/compressed.js"></script>
    <script src="./front office assets/js/main.js"></script>
    <script src="./front office assets/js/switcher.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

    <!-- voice recognation -->
    <script type="text/javascript" src=".\View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

</body>



</html>