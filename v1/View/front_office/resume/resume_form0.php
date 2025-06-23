<!DOCTYPE html>
<html class="no-js">

<head>
    <title>ResumeUp</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="./../../../front office assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../../../front office assets/css/animations.css">
    <link rel="stylesheet" href="./../../../front office assets/css/font-awesome.css">
    <link rel="stylesheet" href="./../../../front office assets/css/main.css" class="color-switcher-link">
    <script src="./../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon" />

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>


    <style>
        .button-container {
            display: flex;
        }

        .primary-button {
            background-color: #40A2D8;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .transparent-button {
            background-color: transparent;
            color: black;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>

</head>

<?php

include_once __DIR__ . './../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/JobC.php';

$userC = new userCon("user");
$profileController = new ProfileC();
$jobC = new JobController();

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


$users_list = $userC->listUsers();
$usersArray = $users_list->fetchAll(PDO::FETCH_ASSOC);
$users_nb = count($usersArray);

$jobsArray = $jobC->getAllJobs();
$jobs_nb = count($jobsArray);


?>

<body>

    <?php
    $block_call_back = 'true';
    $access_level = "none";
    include('./../../../View/callback.php')
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

            <?php
            $active_page = "profile";
            include('./../../../View/front_office/front_header.php');
            ?>

            <section class="page_title cs s-py-25 half-section" >
                <div class="divider-100" style="margin-bottom: 150px;"></div>
            </section>

            <section class="page_title cs s-py-25 half-section" >
                <div class="container">
                    <div class="row">

                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">ResumeUp</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="./../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">
                                    ResumeUp
                                </li>
                            </ol>
                        </div>

                        <div class="divider-50"></div>

                    </div>
                </div>
            </section>


            <section class="pt-20 pb-10 s-py-lg-130 main_contact_form" style="background-repeat: no-repeat !important;">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 contact-header heading text-center">
                            <h5>
                                Submit
                            </h5>
                            <h4>
                                Resume Info
                            </h4>
                        </div>
                        <div class="px-30 ds-form">
                            <form id="" action="" method="" enctype="multipart/form-data" class="ds contact-form c-mb-20">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="resume_name">Full Name
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" aria-required="true" size="200" value="" name="resume_name" id="resume_name" class="form-control" placeholder="Full Name">
                                        </div>
                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="resume_phone">Phone number
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" aria-required="true" size="200" value="" name="resume_phone" id="resume_phone" class="form-control" placeholder="Phone number">
                                        </div>
                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="email">Email address
                                                <span class="required">*</span>
                                            </label>
                                            <input type="email" aria-required="true" size="200" value="" name="your-email" id="email" class="form-control" placeholder="Email address">
                                        </div>
                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="resume_job">Job sector
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" aria-required="true" size="200" value="" name="resume_job" id="resume_job" class="form-control" placeholder="Job sector">
                                        </div>
                                        <div class="col-c-mb-60 form-group">
                                            <input type="file" class="custom-file-input button" id="resume_picture" accept="image/*">
                                            <label class="custom-file-label" for="resume_picture">Picture</label>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group has-placeholder">
                                            <label for="resume_about_me">About Me</label>
                                            <textarea aria-required="true" rows="6" cols="40" name="resume_about_me" id="resume_about_me" class="form-control" placeholder="About Me"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <h4>Work Experience</h4>
                                            <!-- 1 or +1 (>1) -->
                                            <!-- un ou plusieurs -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="job_exp">Job Experience
                                                <span class="required">*</span>
                                            </label>
                                            <!-- 1 or +1 (>1) -->
                                            <input type="text" aria-required="true" size="200" value="" name="job_exp" id="job_exp" class="form-control" placeholder="Job Experience">
                                        </div>

                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="exp_start">Start Date
                                                <span class="required">*</span>
                                            </label>
                                            <!-- required format ../../.... -->
                                            <input type="text" aria-required="true" size="200" value="" name="exp_start" id="exp_start" class="form-control" placeholder="Start Date">
                                        </div>

                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="exp_description">Description
                                                <span class="required">*</span>
                                            </label>
                                            <!-- 1 or +1 (>1) -->
                                            <input type="text" aria-required="true" size="200" value="" name="exp_description" id="exp_description" class="form-control" placeholder="Description">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="exp_company">Company
                                                <span class="required">*</span>
                                            </label>
                                            <!-- 1 or +1 (>1) -->
                                            <input type="text" aria-required="true" size="200" value="" name="exp_company" id="exp_company" class="form-control" placeholder="Company">
                                        </div>

                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="exp_end">End Date
                                                <span class="required">*</span>
                                            </label>
                                            <!-- required format ../../.... -->
                                            <input type="text" aria-required="true" size="200" value="" name="exp_end" id="exp_end" class="form-control" placeholder="End Date">
                                        </div>

                                        <div class="col-c-mb-60 form-group has-placeholder">
                                            <label for="resume_links">Social Links
                                                <span class="required">*</span>
                                            </label>
                                            <!-- 1 or +1 (>1) -->
                                            <input type="text" aria-required="true" size="200" value="" name="resume_links" id="resume_links" class="form-control" placeholder="Social Links">
                                        </div>


                                    </div>



                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group text-center">
                                        <button type="submit" id="resume_form_submit" name="resume_submit" class="button">Submit CV</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Footer -->
            <?php include('./../../../View/front_office/front_footer.php') ?>
            <!-- End Footer -->

        </div>
        <!-- eof #box_wrapper -->
    </div>
    <!-- eof #canvas -->


    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/switcher.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>


</body>

</html>