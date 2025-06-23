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

            <section class="page_title cs s-py-25" style="background-color: #5C5470 !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #5C5470 !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>
                        <div class="col-md-12 text-center">
                            <h1 class="">Pioneering Future Improvements</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="./index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Pioneering Future Improvements
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
                                        <a href="./front office assets/images/index/12.png" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/12.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/index/13.png" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/13.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/index/14.png" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/14.png" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h2>
                                        Our Vision for the Future of Recruitment
                                    </h2>
                                    <p>
                                        At HireUp, we are not just thinking about today—we are envisioning the future of recruitment and employment. Our goal is to create the most innovative, efficient, and user-friendly platform in the industry, transforming how employers and job seekers connect.
                                    </p>
                                    <h3>Why Choose HireUp for the Future?</h3>
                                    <p>
                                        As we look forward, we are committed to continuous improvement and groundbreaking innovations. Here are some of the exciting advancements we are working on:
                                    </p>
                                    <ul class="list1">
                                        <li>
                                            <i class="color-main fa fa-rocket"></i>
                                            <strong>Advanced AI Integration:</strong> Our future plans include more sophisticated AI tools to enhance candidate matching, job recommendations, and automated interview scheduling, ensuring the perfect fit for every role.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-globe"></i>
                                            <strong>Global Reach:</strong> We aim to expand our services to a global audience, making HireUp the go-to platform for recruitment and employment worldwide.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-heartbeat"></i>
                                            <strong>User-Centric Design:</strong> Our future upgrades will focus on improving user experience with intuitive interfaces, personalized dashboards, and seamless navigation.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-line-chart"></i>
                                            <strong>Data-Driven Insights:</strong> We plan to provide deeper insights and analytics to help employers make informed hiring decisions and job seekers understand market trends.
                                        </li>
                                        <li>
                                            <i class="color-main fa fa-users"></i>
                                            <strong>Community Building:</strong> Our vision includes creating a vibrant community where professionals can network, share knowledge, and grow together.
                                        </li>
                                    </ul>
                                    <blockquote class="text-center">
                                        <p>
                                            "HireUp is committed to making a positive impact on the recruitment industry. Our vision is a win-win-win scenario: a win for employers, a win for job seekers, and a win for society."
                                        </p>
                                        <span>
                                            - The HireUp Team
                                        </span>
                                    </blockquote>
                                    <p>
                                        Join us on our journey to revolutionize the recruitment and employment landscape. Together, we can create a platform that not only meets today's needs but anticipates tomorrow's challenges and opportunities.
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