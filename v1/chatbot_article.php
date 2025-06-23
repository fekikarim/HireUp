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

            <section class="page_title cs s-py-25" style="background-color: #1B4242 !important;">
            <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #1B4242 !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>
                        <div class="col-md-12 text-center">
                            <h1 class="">Introducing HireUp Bot</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="./index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Introducing HireUp Bot
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
                                        <a href="./front office assets/images/index/9.jpg" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/9.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/index/10.jpg" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/10.png" alt="">
                                        </a>
                                        <a href="./front office assets/images/gallery/11.jpg" class="photoswipe-link" data-width="1170" data-height="780">
                                            <img src="./front office assets/images/index/11.png" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="item-content">
                                    <h2>
                                        Meet the Future of Recruitment: HireUp Bot
                                    </h2>
                                    <p>
                                        At HireUp, we are revolutionizing the way you interact with our platform through our innovative HireUp Bot. This cutting-edge chatbot is designed to enhance your user experience, making navigation and task completion simpler and more efficient.
                                    </p>
                                    <h3>Why Choose HireUp Bot?</h3>
                                    <p>
                                        The HireUp Bot offers unparalleled convenience and functionality. Here are some of the key features that make it a game-changer:
                                    </p>
                                    <ul class="list1">
                                        <li>
                                            <i class="color-main fa fa-bell"></i>
                                            <strong>Real-Time Notifications:</strong> Stay updated with the latest job postings, application statuses, and important alerts without leaving the chat interface.
                                        </li>
                                        <br>
                                        <li>
                                            <i class="color-main fa fa-microphone"></i>
                                            <strong>Voice Interaction:</strong> Communicate with the HireUp Bot using your voice. Ask questions, seek guidance, and navigate the platform hands-free.
                                        </li>
                                        <br>
                                        <li>
                                            <i class="color-main fa fa-comments"></i>
                                            <strong>Personalized Assistance:</strong> Get help with any employment or recruitment-related queries. Whether you need to generate a job description or find the perfect candidate, HireUp Bot has got you covered.
                                        </li>
                                        <br>
                                        <li>
                                            <i class="color-main fa fa-search"></i>
                                            <strong>Daily Job Updates:</strong> Receive daily updates on new job opportunities that match your profile and preferences.
                                        </li>
                                        <br>
                                        <li>
                                            <i class="color-main fa fa-check-circle"></i>
                                            <strong>Task Automation:</strong> Automate repetitive tasks and streamline your workflow, saving you time and effort.
                                        </li>
                                    </ul>
                                    <blockquote class="text-center">
                                        <p>
                                            "HireUp Bot is not just a tool; it's a partner in your career journey, making every step more intuitive and less time-consuming."
                                        </p>
                                        <span>
                                            - The HireUp Team
                                        </span>
                                    </blockquote>
                                    <p>
                                        Experience the future of recruitment with HireUp Bot. Whether you're an employer looking to streamline your hiring process or a job seeker aiming to find the perfect role, HireUp Bot is here to assist you every step of the way.
                                    </p>
                                    <div>
                                        <a href="#" class="fa fa-facebook" title="facebook"></a>
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