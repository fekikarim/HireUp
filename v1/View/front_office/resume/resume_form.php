<!DOCTYPE html>
<html class="no-js">

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

    // age calculation
    // The user's birthday as a string
    $birthday = $profile['profile_bday'];

    // Parse the birthday into a DateTime object
    $birthDate = new DateTime($birthday);

    // Get the current date
    $currentDate = new DateTime();

    // Calculate the difference between the current date and the birthday
    $age = $currentDate->diff($birthDate);

    // Extract the age in years
    $ageInYears = $age->y;


}

//fetch subscription
$subs_type = array(
    "1-ADVANCED-SUBS" => "advanced",
    "1-BASIC-SUBS" => "basic",
    "1-PREMIUM-SUBS" => "premium",
    "else" => "limited"
);

$current_profile_sub = "";
if (array_key_exists($profile['profile_subscription'], $subs_type)) {
    // If it exists, return the corresponding value
    $current_profile_sub = $subs_type[$profile['profile_subscription']];
} else {
    // If not, return 'bb'
    $current_profile_sub = $subs_type['else'];
}

if ($current_profile_sub == "limited") {

    header("Location: ./../profiles_management/subscription/subscriptionCards.php");
    exit;

}

$block_call_back = 'false';
$access_level = "else";
include ('./../../../View/callback.php');




    ?>

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

    <style>
        /* Popup modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            /* Ensure it overlays other content */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
        }

        .valid-message {
            color: #aaa;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 1000px;
            /* Limit maximum width */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add shadow for depth */
            z-index: 99999;
            /* Ensure it overlays other content */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Adjustments to the main content when modal is open */
        .modal-open {
            overflow: hidden;
            /* Prevent scrolling */
        }



        /* JOB IMAGE STYLESHEET */
        /* Style for job container */
        .job-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
        }

        /* Style for job image */
        .job-img-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .voice-icon {
            cursor: pointer;
            margin-left: 5px;
        }

        /* Style for job container */
        .hidden-job-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */

        }
    </style>

    <style>
        /* Styling for the popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        /* Styling for the overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
    </style>

    <style>
        progress {
            display: inline-block;
            position: relative;
            background: none;
            border: 0;
            border-radius: 5px;
            width: 100%;
            text-align: left;
            position: relative;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.8em;
        }

        progress::-webkit-progress-bar {
            margin: 0 auto;
            background-color: #CCC;
            border-radius: 5px;

        }

        progress::-webkit-progress-value {
            display: relative;
            margin: 0px -10px 0 0;
            background: #55bce7;
            border-radius: 5px;
        }

        progress:after {
            margin: -36px 0 0 7px;
            padding: 0;
            display: inline-block;
            float: right;
            content: attr(value) '%';
            position: relative;
        }
    </style>

    <style>
        .popup-card {
            display: none;
            position: fixed;
            z-index: 99999999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(245, 245, 245, 0.4);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            max-height: 100%;
            min-height: auto;
            min-width: auto;
            padding: 20px;
            border-radius: 5px;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .skills-list {
            list-style-type: none;
            padding: 0;
        }

        .skills-list li {
            margin-bottom: 5px;
        }

        .skills-list .found {
            color: green;
        }

        .skills-list .not-found {
            color: red;
        }

        .progress-bar-container {
            margin-top: 10px;
            padding: 2% 10%;
        }

        .progress-bar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 20px;
            background-color: #55bce7;
            width: 0;
            text-align: center;
            color: white;
            line-height: 20px;
        }
    </style>

    <style>
        .popup-card {
            display: none;
            position: fixed;
            z-index: 99999999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(245, 245, 245, 0.4);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            max-height: 100%;
            min-height: auto;
            min-width: auto;
            padding: 20px;
            border-radius: 5px;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            border: 1px solid #888;
            width: 80%;
            height: 82%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .popup-content iframe {
            width: 100%;
            height: 82%;
            /* Set the height to adjust based on content */
        }
    </style>

    <!-- Interested-btns -->
    <style>
        .Interested-btns-like:hover,
        .Interested-btns-like-active {
            color: #55bce7 !important;
        }

        .Interested-btns-dislike:hover,
        .Interested-btns-dislike-active {
            color: #ff0000 !important;
        }
    </style>

    <style>
        .progress {
            width: 100%;
            background-color: #e0e0e0;
        }

        .progress-bar {
            width: 0%;
            height: 30px;
            background-color: #76c7c0;
        }


        .form-group>span {
            color: red;
        }
    </style>

    <!-- dropdown menu -->
    <style>
        .dropdown-container {
            position: relative;
            display: inline-block;
            width: 250px;
        }

        .dropdown-toggle {
            width: 100%;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: left;
            font-size: 16px;
        }

        .dropdown_menu {
            display: none;
            position: absolute;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
            margin-top: 5px;
            overflow: hidden;
        }

        .dropdown_menu .dropdown-item {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        .dropdown_menu .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown_menu .dropdown-item:hover {
            background-color: #f1f1f1;
        }

        .dropdown_menu .dropdown-item span {
            flex-grow: 1;
        }

        .dropdown_menu .dropdown-item button {
            margin-left: 5px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: #007bff;
            font-size: 16px;
        }

        .dropdown_menu .dropdown-item button:hover {
            color: #0056b3;
        }
    </style>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

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
                        id="modal-search-input">
                </div>
                <button type="submit" class="btn">Search</button>
            </form>
        </div>
    </div>



    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">
        <div id="box_wrapper">

            <?php
            $active_page = "ResumeUp";
            include ('./../../../View/front_office/front_header.php');
            ?>

            <section class="page_title cs s-py-25 half-section">
                <div class="divider-100" style="margin-bottom: 150px;"></div>
            </section>

            <section class="page_title cs s-py-25 half-section">
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



            <section class="pt-20 pb-10 s-py-lg-130">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 contact-header heading text-center text-dark">
                            <h5>
                                Submit
                            </h5>
                            <h4>
                                Resume Info
                            </h4>
                        </div>


                        <div class="px-30 ds-form">

                            <form id="resumeSubmitFormCv" action="./resume template/make_save.php" method="POST"
                                enctype="multipart/form-data">
                                <!-- CV input start -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="text" aria-required="true" size="200" value=""
                                                    name="resume_name" onkeyup="validateName()" id="resume_name"
                                                    class="form-control" placeholder="Full Name">
                                            </div>
                                            <span id="name_error"></span> <!-- Error message placeholder -->
                                        </div>
                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="text" aria-required="true" size="200" value=""
                                                    name="resume_phone" id="resume_phone" onkeyup="validatePhone()"
                                                    class="form-control" placeholder="Phone number">
                                            </div>
                                            <span id="phone_error"></span> <!-- Error message placeholder -->
                                        </div>
                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="email" aria-required="true" size="200" value=""
                                                    name="email" id="email" onkeyup="validateEmail()"
                                                    class="form-control" placeholder="Email address">
                                            </div>
                                            <span id="email_error"></span> <!-- Error message placeholder -->
                                        </div>
                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="text" aria-required="true" size="200" value=""
                                                    name="resume_job" id="resume_job" onkeyup="validateJob()"
                                                    class="form-control" placeholder="Job sector">
                                            </div>
                                            <span id="job_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="text" aria-required="true" size="200" value=""
                                                    name="resume_adresse" onkeyup="validateAdress()" id="resume_adresse"
                                                    class="form-control" placeholder="Adress">
                                            </div>
                                            <span id="adresse_error"></span> <!-- Error message placeholder -->
                                        </div>


                                        <input type="hidden" id="resume_data" name="resume_data">
                                        <div class="col-c-mb-60 form-group">


                                            <label for="resume_picture" class="custom-file-label mt-2"
                                                style="background-color: #F2F2F2; color: black;"><b>Picture</b></label>

                                            <input type="file" class="custom-file-input button mb-2" id="resume_picture"
                                                name="resume_picture" accept="image/*" onchange="validateImg()">


                                            <span id="pic_error" class="mt-6"></span> <!-- Error message placeholder -->

                                        </div>


                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">



                                            <div class="input-group">
                                                <textarea aria-required="true" rows="6" cols="40" name="resume_about_me"
                                                    id="resume_about_me" onkeyup="validateAbout()" class="form-control"
                                                    placeholder="About Me"></textarea>
                                            </div>
                                            <span id="aboutMe_error"></span> <!-- Error message placeholder -->
                                        </div>


                                    </div>

                                </div>

                                <!-- CV input end -->

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3 mt-3">
                                            <h4>Skills</h4>
                                            <!-- 1 or +1 (>1) -->
                                            <!-- un ou plusieurs -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="input-group">



                                                <!-- 1 or +1 (>1) -->
                                                <input type="text" onkeyup="validateSkills()" aria-required="true"
                                                    size="200" value="" name="resume_skills" id="resume_skills"
                                                    class="form-control" placeholder="Skills">
                                            </div>
                                            <span id="skill_error"></span> <!-- Error message placeholder -->
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">



                                            <div class="input-group">
                                                <input type="number" onkeyup="validateProgress()" aria-required="true"
                                                    size="200" value="" name="resume_progress" id="resume_progress"
                                                    class="form-control" placeholder="Enter value between 1 and 100"
                                                    min="1" max="100" oninput="updateProgressBar()">
                                            </div>

                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" id="progress-bar"></div>
                                        </div>
                                        <span id="progress_error" class="text-danger"></span>
                                        <!-- Error message placeholder -->
                                    </div>

                                </div>


                                <script>
                                    function updateProgressBar() {
                                        const input = document.getElementById('resume_progress');
                                        const progressBar = document.getElementById('progress-bar');
                                        let value = input.value;

                                        if (value < 1) value = 1;
                                        if (value > 100) value = 100;

                                        progressBar.style.width = value + '%';
                                    }
                                </script>

                                <div class="divider-50"></div>

                                <div class="col-sm-12">



                                    <div class="form-group text-center">

                                        <button type="button" id="skills_form_submit" name="skills_form_submit"
                                            onclick="return skillSubmit()" class="btn btn-info"><i
                                                class="fa fa-plus"></i>
                                            Skill</button>



                                        <div class="dropdown-container mr-3">
                                            <a href="javascript:void()" class="btn btn-outline-secondary"
                                                id="dropdownToggle">My Skills <i class="fa fa-caret-down"></i></a>
                                            <div class="dropdown_menu" id="dropdownMenu">
                                                <!-- Items will be injected here via JavaScript -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group text-center"
                                        style="text-align: center !important; justify-content: center !important;">
                                        <span id="submit_skill_error" class="text-danger text-center"></span>
                                        <!-- Error message placeholder -->
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3 mt-3">
                                            <h4>Work Experience</h4>
                                            <!-- 1 or +1 (>1) -->
                                            <!-- un ou plusieurs -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">



                                            <div class="input-group">
                                                <!-- 1 or +1 (>1) -->
                                                <input type="text" onkeyup="validateJobExperience()"
                                                    aria-required="true" size="200" value="" name="job_exp" id="job_exp"
                                                    class="form-control" placeholder="Job Experience">
                                            </div>
                                            <span id="jobName_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">



                                            <div class="input-group">
                                                <!-- 1 or +1 (>1) -->
                                                <input type="text" onkeyup="validateCompany()" aria-required="true"
                                                    size="200" value="" name="exp_company" id="exp_company"
                                                    class="form-control" placeholder="Company">
                                            </div>
                                            <span id="company_error"></span> <!-- Error message placeholder -->
                                        </div>


                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" onkeyup="validateWorkStart()" aria-required="true"
                                                    size="200" value="" name="exp_start" id="exp_start"
                                                    class="form-control" placeholder="Start Date">
                                            </div>
                                            <span id="work_start_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" onkeyup="validateWorkEnd()" aria-required="true"
                                                    size="200" value="" name="exp_end" id="exp_end" class="form-control"
                                                    placeholder="End Date">
                                            </div>
                                            <span id="work_end_error"></span> <!-- Error message placeholder -->
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">

                                            <div class="input-group">
                                                <textarea aria-required="true" onkeyup="validateWorkDescription()"
                                                    rows="6" cols="40" name="exp_description" id="exp_description"
                                                    class="form-control" placeholder="Description"></textarea>
                                            </div>
                                            <span id="work_desc_error"></span> <!-- Error message placeholder -->
                                        </div>


                                    </div>



                                </div>

                                <div class="divider-50"></div>

                                <div class="col-sm-12">



                                    <div class="form-group text-center">
                                        <button type="button" onclick="return workSubmit();" id="work_form_submit"
                                            name="work_form_submit" class="btn btn-secondary"><i
                                                class="fa fa-plus mr-3"></i>
                                            Experience</button>



                                        <div class="dropdown-container mr-3">
                                            <a href="javascript:void()" class="btn btn-outline-secondary"
                                                id="dropdownToggle2">My
                                                Experiences <i class="fa fa-caret-down"></i></a>
                                            <div class="dropdown_menu" id="dropdownMenu2">
                                                <!-- Items will be injected here via JavaScript -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group text-center"
                                        style="text-align: center !important; justify-content: center !important;">
                                        <span id="submit_work_error" class="text-danger text-center"></span>
                                        <!-- Error message placeholder -->
                                    </div>
                                </div>

                                <div id="works-output"></div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3 mt-3">
                                            <h4>Education Experience</h4>
                                            <!-- 1 or +1 (>1) -->
                                            <!-- un ou plusieurs -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">



                                            <div class="input-group">
                                                <!-- 1 or +1 (>1) -->
                                                <input type="text" onkeyup="validateInstitution()" aria-required="true"
                                                    size="200" value="" name="edu_institution" id="edu_institution"
                                                    class="form-control" placeholder="Institution">
                                            </div>
                                            <span id="institut_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">



                                            <div class="input-group">
                                                <!-- 1 or +1 (>1) -->
                                                <input type="text" onkeyup="validateDegree()" aria-required="true"
                                                    size="200" value="" name="edu_degree" id="edu_degree"
                                                    class="form-control" placeholder="Degree">
                                            </div>
                                            <span id="degree_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" onkeyup="validateEduStart()" aria-required="true"
                                                    size="200" value="" name="edu_start" id="edu_start"
                                                    class="form-control" placeholder="Start Date">
                                            </div>
                                            <span id="edu_start_error"></span> <!-- Error message placeholder -->
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" onkeyup="validateEduEnd()" aria-required="true"
                                                    size="200" value="" name="edu_end" id="edu_end" class="form-control"
                                                    placeholder="End Date">
                                            </div>
                                            <span id="edu_end_error"></span> <!-- Error message placeholder -->
                                        </div>




                                    </div>

                                    <div class="col-sm-6">



                                        <div class="form-group">
                                            <div class="input-group"></div>
                                            <textarea aria-required="true" onkeyup="validateEduDescription()" rows="6"
                                                cols="40" name="edu_description" id="edu_description"
                                                class="form-control" placeholder="Description"></textarea>
                                        </div>
                                        <span id="edu_desc_error" class="text-danger"></span>
                                        <!-- Error message placeholder -->
                                    </div>
                                </div>





                                <div class="divider-50"></div>

                                <div class="col-sm-12">

                                    <div class="form-group text-center">
                                        <button type="button" onclick="return educationSubmit()"
                                            id="education_form_submit" name="education_form_submit"
                                            class="btn btn-info"><i class="fa fa-plus"></i>
                                            Education</button>
                                            
                                        <div class="dropdown-container mr-3">
                                            <a href="javascript:void()" class="btn btn-outline-secondary"
                                                id="dropdownToggle3">My
                                                Education <i class="fa fa-caret-down"></i></a>
                                            <div class="dropdown_menu" id="dropdownMenu3">
                                                <!-- Items will be injected here via JavaScript -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group text-center"
                                        style="text-align: center !important; justify-content: center !important;">
                                        <span id="submit_edu_error" class="text-danger text-center"></span>
                                        <!-- Error message placeholder -->
                                    </div>

                                </div>
                                

                                <!-- <div id="educations-output"></div> -->

                                <div class="divider-50"></div>

                                <div class="col-sm-12">


                                    <div class="form-group text-center">
                                        <button type="button" id="resume_form_submit" name="resume_submit"
                                            class="btn btn-primary" onclick="return resumeSubmit();">Submit
                                            CV</button>

                                    </div>

                                    <div class="form-group text-center"
                                        style="text-align: center !important; justify-content: center !important;">
                                        <span id="submit_cv_error" class="text-danger text-center"></span>
                                        <!-- Error message placeholder -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Footer -->
            <?php include ('./../../../View/front_office/front_footer.php') ?>
            <!-- End Footer -->

        </div>
        <!-- eof #box_wrapper -->
    </div>
    <!-- eof #canvas -->


    <!-- Modal skill -->
    <div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="editSkillModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="skillIdEd">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSkillModalLabel">Edit Skill</h5>
                    <button type="button" class="close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <form id="editSkillForm">
                        <div class="form-group">
                            <label for="skillName">Skill Name</label>
                            <input type="text" onkeyup="validateSkillNameEd();" class="form-control" id="skillNameEd"
                                name="skillNameEd">
                            <span id="skillName_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="skillProgress">Skill Progress</label>
                            <input type="number" onkeyup="validateSkillProgressEd();" class="form-control"
                                id="skillProgressEd" name="skillProgressEd" min="1" max="100"
                                oninput="updateProgressBar2()">

                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar" id="progress-bar2"></div>
                        </div>
                        <span id="skillProgress_error_ed" class="text-danger"></span>

                        <button type="button" class="btn btn-primary" onclick="modalSkillVerif();">Save changes</button>
                        <span id="edit_skill_error" class="text-danger"></span>
                    </form>
                </div>




            </div>
        </div>
    </div>

    <!-- Modal work -->
    <div class="modal fade" id="editWorkModal" tabindex="-1" aria-labelledby="editWorkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="job_id_ed">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWorkModalLabel">Edit Work</h5>
                    <button type="button" class="close" onclick="closeModalWork()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editWorkForm">
                        <div class="form-group">
                            <label for="job_exp">Job Experience</label>
                            <input type="text" onkeyup="validateJobExperienceEd();" placeholder="Enter Job Experience"
                                class="form-control" id="job_exp_ed" name="job_exp_ed">
                            <span id="jobName_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="exp_company">Company</label>
                            <input type="text" onkeyup="validateCompanyEd();" placeholder="Enter Company Name"
                                class="form-control" id="exp_company_ed" name="exp_company_ed">
                            <span id="company_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="exp_start">Start Date</label>
                            <input type="text" class="form-control" id="exp_start_ed" name="exp_start_ed"
                                placeholder="DD/MM/YYYY" onkeyup="validateWorkStartEd();">
                            <span id="work_start_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="exp_end">End Date</label>
                            <input type="text" class="form-control" id="exp_end_ed" name="exp_end_ed"
                                placeholder="DD/MM/YYYY" onkeyup="validateWorkEndEd();">
                            <span id="work_end_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="exp_description">Description</label>
                            <textarea class="form-control" id="exp_description_ed" name="exp_description_ed" rows="3"
                                placeholder="Enter Description" onkeyup="validateWorkDescriptionEd();"></textarea>
                            <span id="work_desc_error_ed" class="text-danger"></span>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="modalWorkVerif()">Save changes</button>
                        <span id="edit_work_error" class="text-danger"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal education -->
    <div class="modal fade" id="editEduModal" tabindex="-1" aria-labelledby="editEduModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <input type="hidden" id="edit_edu_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEduModalLabel">Edit Education</h5>
                    <button type="button" class="close" onclick="closeModalEducation()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEduForm">
                        <div class="form-group">
                            <label for="edit_edu_institution">Institution</label>
                            <input type="text" class="form-control" id="edit_edu_institution"
                                name="edit_edu_institution" placeholder="Enter Institution Name"
                                onkeyup="validateInstitutionEd();">
                            <span id="institut_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_edu_start">Start Date</label>
                            <input type="text" class="form-control" id="edit_edu_start" name="edit_edu_start"
                                placeholder="DD/MM/YYYY" onkeyup="validateEduStartEd();">
                            <span id="edu_start_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_edu_end">End Date</label>
                            <input type="text" class="form-control" id="edit_edu_end" name="edit_edu_end"
                                placeholder="DD/MM/YYYY" onkeyup="validateEduEndEd();">
                            <span id="edu_end_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_edu_degree">Degree</label>
                            <input type="text" class="form-control" id="edit_edu_degree" name="edit_edu_degree"
                                placeholder="Enter Degree" onkeyup="validateDegreeEd();">
                            <span id="degree_error_ed" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_edu_description">Description</label>
                            <textarea class="form-control" id="edit_edu_description" name="edit_edu_description"
                                rows="3" placeholder="Enter Description"
                                onkeyup="validateEduDescriptionEd();"></textarea>
                            <span id="edu_desc_error_ed" class="text-danger"></span>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="modalEducVerif()">Save changes</button>
                        <span id="edit_edu_error" class="text-danger"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>






    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/switcher.js"></script>

    <script src="./../../../front office assets/js/chatbot.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

    <!-- voice recognation -->
    <script type="text/javascript"
        src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


    <script src="./scripts/resume_master.js"></script>
    <script src="./scripts/resume_form_control.js"></script>

    <script>
        function resumeSubmit() {
            ok = validateAddCV();
            if (ok) {
                makeDataResume();

                myDict = loadResumeData()
                // Convert dictionary to JSON string
                const jsonString = JSON.stringify(myDict);
                // console.log('jsonString : ')
                // console.log(jsonString)

                // Create a hidden input field
                const hiddenInput = document.getElementById('resume_data');
                // console.log('hiddenInput : ')
                // console.log(hiddenInput)

                form = document.getElementById('resumeSubmitFormCv');
                // console.log('form : ')
                // console.log(form)
                // hiddenInput.type = 'hidden';
                // hiddenInput.name = 'myDict';  // Name for the hidden input
                hiddenInput.value = jsonString;

                // Append the hidden input to the form
                // const form = document.getElementById('resumeSubmitFormCv');
                // form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }

            return ok;
        }

        function skillSubmit() {
            ok = validateSkillSubmit();
            if (ok) {
                addSkill();
            }

            return ok;
        }

        function workSubmit() {
            ok = validateWorkSubmit();
            if (ok) {
                addWork();
            }

            return ok;
        }

        function educationSubmit() {
            ok = validateEduSubmit();
            if (ok) {
                addEducation();
            }

            return ok;
        }


        function modalEducVerif() {
            ok = validateEduSubmitEd();
            if (ok) {
                loadEducationFromModal();
            }

            return ok;
        }

        function modalWorkVerif() {
            ok = validateWorkSubmitEd();
            if (ok) {
                loadWorkFromModal();
            }

            return ok;
        }

        function modalSkillVerif() {
            ok = validateSkillSubmitEd();
            if (ok) {
                loadSkillFromModal();
            }

            return ok;
        }



    </script>

    <script>

        dropdownToggle = document.getElementById('dropdownToggle');
        dropdownMenu = document.getElementById('dropdownMenu');

        dropdownToggle2 = document.getElementById('dropdownToggle2');
        dropdownMenu2 = document.getElementById('dropdownMenu2');

        dropdownToggle3 = document.getElementById('dropdownToggle3');
        dropdownMenu3 = document.getElementById('dropdownMenu3');




        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });


        dropdownToggle2.addEventListener('click', () => {
            dropdownMenu2.style.display = dropdownMenu2.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!dropdownToggle2.contains(event.target) && !dropdownMenu2.contains(event.target)) {
                dropdownMenu2.style.display = 'none';
            }
        });

        dropdownToggle3.addEventListener('click', () => {
            dropdownMenu3.style.display = dropdownMenu3.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!dropdownToggle3.contains(event.target) && !dropdownMenu3.contains(event.target)) {
                dropdownMenu3.style.display = 'none';
            }
        });


    </script>

    <script>

        /*

        function generateSkills1(jsonData, outputElementId) {

            profileId = '1';

            // Parse JSON data
            const categories = JSON.parse(jsonData);

            // Check if data contains categories
            if (!categories || !Array.isArray(categories)) {
                console.error('Invalid JSON data');
                return;
            }

            let html = `
        <section class="ls s-py-lg-50 main_blog">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="contact-header text-center">
                            <h5>My</h5>
                            <h4>Skills</h4>
                        </div>
                        <div class="d-none d-lg-block divider-20"></div>
                        <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">
    `;

            // Loop through categories
            categories.forEach(category => {
                const categoryName = category.category_name;
                const categoryId = category.category_id;

                html += `
            <article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                <div class="item-content" style="min-height: 280px !important;">
                    <header class="blog-header ">
                        <a href="javascript:void(0)" rel="bookmark">
                            <h4>${categoryName}</h4>
                        </a>
                    </header>
                    <div class="blog-item-icons" id="blog-item-icons-catid-${categoryId}">
                        <div class="col-sm-4 pr-5" onclick="likeCategory('${categoryId}', '${profileId}')">
                            <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-${categoryId}">
                                <i class="fa-solid fa-pen-to-square" id="like-i-with-catid-${categoryId}"></i> Edit
                            </a>
                        </div>
                        <div class="col-sm-4 pr-5" onclick="dislikeCategory('${categoryId}', '${profileId}')">
                            <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-${categoryId}">
                                <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-${categoryId}"></i> Remove
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        `;
            });

            html += `
                        </div>
                    </div>
                </div>
            </div>
        </section>
    `;

            // Output HTML
            document.getElementById(outputElementId).innerHTML = html;
        }

        function generateWork(jsonData, outputElementId) {

            profileId = '1';

            // Parse JSON data
            const categories = JSON.parse(jsonData);

            // Check if data contains categories
            if (!categories || !Array.isArray(categories)) {
                console.error('Invalid JSON data');
                return;
            }

            let html = `
            <section class="ls s-py-lg-50 main_blog">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="contact-header text-center">
                            <h5>My Work</h5>
                            <h4>Experiences</h4>
                        </div>
                        <div class="d-none d-lg-block divider-20"></div>
                        <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">
            `;

            // Loop through categories
            categories.forEach(category => {
                const categoryName = category.name_category;
                const categoryId = category.id_category;

                html += `
                <article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                    <div class="item-content" style="min-height: 280px !important;">
                        <header class="blog-header ">
                            <a href="javascript:void(0)" rel="bookmark">
                                <h4>${categoryName}</h4>
                            </a>
                        </header>
                        <div class="blog-item-icons" id="blog-item-icons-catid-${categoryId}">
                            <div class="col-sm-4 pr-5" onclick="likeCategory('${categoryId}', '${profileId}')">
                                <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-${categoryId}">
                                    <i class="fa-solid fa-pen-to-square" id="like-i-with-catid-${categoryId}"></i> Edit
                                </a>
                            </div>
                            <div class="col-sm-4 pr-5" onclick="dislikeCategory('${categoryId}', '${profileId}')">
                                <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-${categoryId}">
                                    <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-${categoryId}"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                `;
            });

            html += `
                        </div>
                    </div>
                </div>
            </div>
            </section>
            `;

            // Output HTML
            document.getElementById(outputElementId).innerHTML = html;
        }

        function generateEducation(jsonData, outputElementId) {

            profileId = '1';

            // Parse JSON data
            const categories = JSON.parse(jsonData);

            // Check if data contains categories
            if (!categories || !Array.isArray(categories)) {
                console.error('Invalid JSON data');
                return;
            }

            let html = `
            <section class="ls s-py-lg-50 main_blog">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="contact-header text-center">
                            <h5>My Work</h5>
                            <h4>Experiences</h4>
                        </div>
                        <div class="d-none d-lg-block divider-20"></div>
                        <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">
            `;

            // Loop through categories
            categories.forEach(category => {
                const categoryName = category.name;
                const categoryId = category.id;

                html += `
                <article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                    <div class="item-content" style="min-height: 280px !important;">
                        <header class="blog-header ">
                            <a href="javascript:void(0)" rel="bookmark">
                                <h4>${categoryName}</h4>
                            </a>
                        </header>
                        <div class="blog-item-icons" id="blog-item-icons-catid-${categoryId}">
                            <div class="col-sm-4 pr-5" onclick="likeCategory('${categoryId}', '${profileId}')">
                                <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-${categoryId}">
                                    <i class="fa-solid fa-pen-to-square" id="like-i-with-catid-${categoryId}"></i> Edit
                                </a>
                            </div>
                            <div class="col-sm-4 pr-5" onclick="dislikeCategory('${categoryId}', '${profileId}')">
                                <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-${categoryId}">
                                    <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-${categoryId}"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                `;
            });

            html += `
                        </div>
                    </div>
                </div>
            </div>
            </section>
            `;

            // Output HTML
            document.getElementById(outputElementId).innerHTML = html;
        }

        */

        /*
        generateSkills(
            '[{"id_category":"1","name_category":"Category 1"},{"id_category":"2","name_category":"Category 2"},{"id_category":"3","name_category":"Category 3"}]',
            'skills-output'
        );

        generateWork(
            '[{"id_category":"4","name_category":"Category 4"},{"id_category":"5","name_category":"Category 5"},{"id_category":"8","name_category":"Category 3"}]',
            'works-output'
        );

        generateEducation(
            '[{"id_category":"7","name_category":"Category 7"},{"id_category":"8","name_category":"Category 7"},{"id_category":"8","name_category":"Category 3"}]',
            'educations-output'
        );
        */
    </script>












</body>

</html>