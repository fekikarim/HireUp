<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/meeting_con.php';
require_once __DIR__ . '/../../../Controller/resume_con.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


// Create an instance of JobController
$jobController = new JobController();
$profileController = new ProfileC();
$applyController = new ApplyController();
$meetingC = new MeetingCon('meetings');
$resumeController = new ResumeController();


$user_id = '';
$user_profile_id = '';


if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);

    // Get profile ID from the URL
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

    $profile = $profileController->getProfileById($user_profile_id);
}

// You need to implement this method
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "update") {
        // Update existing job
        $job_id = $_POST["job_id"];
        $title = $_POST["job_title"];
        $company = $_POST["company"];
        $location = $_POST["location"];
        $description = $_POST["description"];
        $salary = $_POST["salary"];
        $category = $_POST["category"];

        if (!empty($_FILES['job_image']['name']) && $_FILES['job_image']['error'] === 0) {

            // Get profile photo and cover data
            $job_image_tmp_name = $_FILES['job_image']['tmp_name'];
            $job_image = file_get_contents($job_image_tmp_name);

            // Only echo the result if the job update is successful
            $result = $jobController->updateJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image);

            if ($result !== false) {
                // Redirect to prevent form resubmission

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            }
        } else {
            // Only echo the result if the job update is successful
            $result = $jobController->updateJobWithoutImage($job_id, $title, $company, $location, $description, $salary, $category);

            if ($result !== false) {
                // Redirect to prevent form resubmission

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            }
        }
    } elseif ($_POST["action"] == "delete" && isset($_POST["job_id"])) {
        // Delete job
        $job_id = $_POST["job_id"];
        $deleted = $jobController->deleteJob($job_id);
        if ($deleted) {
            echo "Job deleted successfully.";
            header("Location: jobs_list.php");
            exit();
        } else {
            echo "Error deleting job.";
        }
    }
}
// Fetch all jobs
//$jobs = $jobController->getAllJobsWithCategory();

$id_category_options = $jobController->generateCategoryOptions();


$userId = $user_profile_id;

// Fetch all jobs sorted by profile education
$jobs = $applyController->applyListForProfileID($userId);



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


/*$apllies = $applyController->applyList();
foreach ($apllies as $aplly) {
    $job_id = $aplly["apply_job_id"];
    $current_job = $jobController->getJobById($job_id);
    $current_job_user_id = $current_job["jobs_profile"];
    //var_dump($current_job);
    //var_dump($userId);
    //var_dump($current_profile_id == $userId);
    
}*/

//var_dump($current_profile_id);
//var_dump($jobs);

/*
  $userId = 267126;
  // Fetch user's profile education
  $userProfileEducation = $jobController->getUserProfileEducation($userId); // Assuming you have a method to retrieve user profile education
  // Sort jobs based on relevance to user's education
  $sortedJobs = [];
  foreach ($jobs as $job) {
    // Check if the job category matches the user's education
    if ($job['category_name'] === $userProfileEducation) {
      // If the job category matches, add it to the beginning of the sorted jobs array
      array_unshift($job, $sortedJobs);
    } else {
      // If the job category doesn't match, add it to the end of the sorted jobs array
      $sortedJobs[] = $job;
    }
  }

  $userProfileId = "267126"; // Assuming the profile ID is stored in the session

  // Instantiate JobController
  $jobController = new JobController();

  // Fetch Jobs Matching Profile Attributes
  $filteredJobs = $jobController->fetchJobsByEducationLevel($userProfileId);

  // Fetch Additional Jobs from Other Categories without a limit
  $otherJobs = $jobController->fetchJobsByCategory('otherCategoryId'); // Replace 'otherCategoryId' with the ID of the category you want to fetch jobs from

  // Ensure $filteredJobs is an array
  if (!is_array($filteredJobs)) {
    $filteredJobs = [];
  }

  // Ensure $otherJobs is an array
  if (!is_array($otherJobs)) {
    $otherJobs = [];
  }

  // Merge the arrays of filtered jobs and other jobs
  $allJobs = array_merge($filteredJobs, $otherJobs);

*/


$block_call_back = 'false';
$access_level = "else";
include ('./../../../View/callback.php');


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>HireUp Jobs</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <link rel="stylesheet" href="./../../../front office assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/animations.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/font-awesome.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/main.css" class="color-switcher-link" />
    <script src="./../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


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

        .force_p {
            color: #888 !important;
        }

        .force_p:hover {
            color: #888 !important;
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


    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

    <div id="overlay" class="overlay"></div>

    <div class="preloader">
        <div class="preloader_image"></div>
    </div>


    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">

        <div id="box_wrapper">


            <!-- header -->
            <?php
            $active_page = 'jobs';
            include ('../front_header.php');
            ?>


            <section class="page_title cs s-py-25" style="background-color: #1F6E8C !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>
            </section>

            <section class="page_title cs s-py-25" style="background-color: #1F6E8C !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">Appliers</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">Appliers</li>
                            </ol>
                        </div>

                        <div class="divider-50"></div>
                    </div>
                </div>
            </section>

            <br>
            <div class="d-none d-lg-block divider-60"></div>

            <div class="text-center align-items-center justify-content-center">
                <button onclick="window.location.href = './jobs.php'" class="btn btn-outline-primary"><i
                        class="far fa-plus-square"></i> Jobs</button>
                <button onclick="window.location.href = './jobs_list.php'" class="btn btn-maincolor2 ml-3"><i
                        class="far fa-list-alt"></i> Jobs List</button>
                <button onclick="window.location.href = './applyJobs_list.php'" class="btn btn-success ml-3"><i
                        class="far fa-list-alt"></i> My Applies</button>
                <button onclick="window.location.href = './myJobs_list.php'" class="btn btn-primary ml-3"><i
                        class="far fa-list-alt"></i> My Jobs</button>
            </div>

            <section class="ls s-py-50 s-py-50">
                <div class="container">
                    <div class="d-none d-lg-block divider-20"></div>

                    <div class="row">
                        <div class="col-lg-12 blog_slider">
                            <section class="page_slider">
                                <div class="flexslider" data-dots="true" data-nav="false">
                                    <ul class="slides blog-slides">
                                        <li class="cover-image ds s-overlay">
                                            <img src="./../../../front office assets/images/img_01.jpg" alt="" />
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <div class="blog intro_layers_wrapper">
                                                            <div class="intro_layers">
                                                                <div class="intro_layer" data-animation="slideUp">
                                                                    <h4>Welcome to</h4>
                                                                    <h2 class="text-uppercase">
                                                                        HireUp
                                                                    </h2>
                                                                </div>
                                                            </div>
                                                            <!-- eof .intro_layers -->
                                                        </div>
                                                        <!-- eof .intro_layers_wrapper -->
                                                    </div>
                                                    <!-- eof .col-* -->
                                                </div>
                                                <!-- eof .row -->
                                            </div>
                                            <!-- eof .container -->
                                        </li>

                                        <li class="cover-image ds s-overlay">
                                            <img src="./../../../front office assets/images/img_04.jpg" alt="" />
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <div class="blog intro_layers_wrapper">
                                                            <div class="intro_layers">
                                                                <div class="intro_layer" data-animation="pullDown">
                                                                    <h4>Receiving</h4>
                                                                </div>
                                                                <div class="intro_layer" data-animation="pullUp">
                                                                    <h2 class="text-uppercase">A Job Offer</h2>
                                                                </div>
                                                            </div>
                                                            <!-- eof .intro_layers -->
                                                        </div>
                                                        <!-- eof .intro_layers_wrapper -->
                                                    </div>
                                                    <!-- eof .col-* -->
                                                </div>
                                                <!-- eof .row -->
                                            </div>
                                            <!-- eof .container -->
                                        </li>

                                        <li class="cover-image ds s-overlay">
                                            <img src="./../../../front office assets/images/img_03.jpg" alt="" />
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <div class="blog intro_layers_wrapper intro_text_bottom">
                                                            <div class="intro_layers">
                                                                <div class="intro_layer" data-animation="slideLeft">
                                                                    <h4>Keep in touch</h4>
                                                                </div>
                                                                <div class="intro_layer" data-animation="slideRight">
                                                                    <h2 class="text-uppercase">Stay Updated</h2>
                                                                </div>
                                                            </div>
                                                            <!-- eof .intro_layers -->
                                                        </div>
                                                        <!-- eof .intro_layers_wrapper -->
                                                    </div>
                                                    <!-- eof .col-* -->
                                                </div>
                                                <!-- eof .row -->
                                            </div>
                                            <!-- eof .container -->
                                        </li>
                                    </ul>
                                </div>
                                <!-- eof flexslider -->
                            </section>
                        </div>
                    </div>

                    <!-- Popup Form for Editing Job -->
                    <div id="updateJobModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Edit Job</h2>
                            <form id="updateJobForm" method="post" enctype="multipart/form-data">
                                <!-- Form fields for updating job details -->
                                <input type="hidden" name="action" value="update">
                                <div class="form-group">
                                    <label for="update_job_id">Job ID *</label>
                                    <input type="text" class="form-control" id="update_job_id" name="job_id" readonly>

                                </div>
                                <div class="form-group">
                                    <label for="update_job_title">Job Title:</label>
                                    <input type="text" id="update_job_title" name="job_title" class="form-control">
                                    <span id="update_job_title_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->

                                </div>
                                <div class="form-group">
                                    <label for="update_company">Company:</label>
                                    <input type="text" id="update_company" name="company" class="form-control">
                                    <span id="update_company_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="form-group">
                                    <label for="update_location">Location:</label>
                                    <input type="text" id="update_location" name="location" class="form-control">
                                    <span id="update_location_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="form-group">
                                    <label for="update_description">Description:</label>
                                    <textarea id="update_description" name="description" class="form-control"
                                        rows="4"></textarea>
                                    <span id="update_description_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="form-group">
                                    <label for="update_salary">Salary:</label>
                                    <input type="text" id="update_salary" name="salary" class="form-control">
                                    <span id="update_salary_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="form-group">
                                    <label for="update_category" class="form-label">Category *</label>
                                    <select class="form-select" id="update_category" name="category">
                                        <option value="" selected disabled>Select Category</option>
                                        <?php echo $id_category_options; ?>
                                    </select>
                                    <span id="update_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>

                                <!-- Hidden job img container -->
                                <div class="form-group hidden-job-img-container" id="hiddenJobImageContainer"
                                    style="display: none;">
                                    <img src="#" alt="Hidden Job Image" class="hidden-job-image" id="hiddenJobImage">
                                </div>

                                <!-- job image container -->
                                <div class="form-group job-img-container" id="update_job_image_display">
                                    <!-- Output the job img with appropriate MIME type -->
                                    <img src="#" id="update_job_img" alt="Job Image" class="img-fluid job-img-image">
                                </div><br>

                                <!-- Add input field for job img -->
                                <div class="form-group">
                                    <label for="update_job_image" class="form-label">Choose New Job Image</label>
                                    <input type="file" class="form-control" id="update_job_image" name="job_image"
                                        onchange="handleJobImageChange(event)" accept="image/*">
                                </div><br>

                                <button type="submit" class="btn btn-primary" id="updateJobBtn">Update Job</button>
                                <button type="button" class="btn btn-secondary cancel-btn"
                                    id="cancelUpdateBtn">Cancel</button>
                            </form>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row c-gutter-60 mt-20">
                            <main class="offset-lg-1 col-lg-10">
                                <!-- Front-end code to display dynamically fetched jobs -->

                                <!-- Bootstrap Modal for Full-Screen Image -->
                                <div class="modal fade" id="jobImageModal" tabindex="-1"
                                    aria-labelledby="jobImageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="max-height: 90vh; overflow: auto;">
                                                <img id="fullScreenImage" src="" class="img-fluid" style="width: 100%;"
                                                    alt="Job Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- interview popup -->

                                <div>
                                    <form id="editForm" action="./acceptJobAplly.php" method="post">
                                        <div id="popup" class="popup">
                                            <input type="hidden" name="jobId" id="jobId-pop-interview" value="">
                                            <input type="hidden" name="userId" id="userId-pop-interview" value="">
                                            <input type="hidden" name="mod_id" id="mod_id-pop-interview" value="">
                                            <div class="text-end mx-4">
                                                <p>Meeting Discription</p>
                                                <textarea name="meeting_desc" id="meeting_desc-popup-interview"
                                                    class="form-control mb-3"></textarea>
                                                <div id="desc_error" style="color: red;"></div>
                                                <p>Meeting At</p>
                                                <input type="datetime-local" name="meeting_at"
                                                    id="meeting_at-popup-interview" class="form-control mb-3"
                                                    style="color: black;">
                                                <div id="meeting_at_error" style="color: red;"></div>
                                                <button type="button" onclick="togglePopup('', '', '')"
                                                    class="btn btn-outline-danger mr-3">Decline</button>
                                                <button type="submit" onclick="return verify_meeting_data()"
                                                    class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- end interview popup -->

                                <?php $popup_counter = -1; ?>
                                <?php foreach ($jobs as $job_apply): ?>
                                    <?php $popup_counter++; ?>
                                    <?php
                                    if ($job_apply['status'] == 'pending' || $job_apply['status'] == 'interview') {
                                        $job = $jobController->getJobById($job_apply['apply_job_id']);
                                        if ($job['jobs_profile'] == $user_profile_id) {
                                            ?>

                                            <?php if (!empty($job['job_image'])): ?>
                                                <div class="item-media post-thumbnail embed-responsive-3by2">
                                                    <a href="#"
                                                        onclick="openFullScreenImage('<?= base64_encode($job['job_image']) ?>')">
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($job['job_image']) ?>"
                                                            alt="Job Image">
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <!-- Display job details -->
                                            <article
                                                class="text-center text-md-left vertical-item content-padding bordered post type-post status-publish format-standard has-post-thumbnail sticky position-relative">
                                                <!-- Job content -->
                                                <div class="item-content">
                                                    <header class="entry-header">
                                                        <h3 class="entry-title">
                                                            <a href="#link" rel="bookmark"><?= $job['title']; ?></a>
                                                        </h3>
                                                    </header>
                                                    <!-- Job description -->
                                                    <div class="entry-content">
                                                        <p><?= $job['description']; ?></p>
                                                    </div>
                                                    <br>
                                                    <!-- Job description -->
                                                    <div class="entry-content text-end">
                                                        <p>
                                                            <?php
                                                            $profile_id_that_applied_on_job = $job_apply['apply_profile_id'];
                                                            $profile_that_applied_on_job = $profileController->getProfileById($profile_id_that_applied_on_job);
                                                            ?>
                                                            Applied by <a
                                                                href="./../profiles_management/profile.php?profile_id=<?php echo $profile_that_applied_on_job['profile_id']; ?>"
                                                                target="_blank"><?php echo $profile_that_applied_on_job['profile_first_name'] . " " . $profile_that_applied_on_job['profile_family_name']; ?></a>
                                                            he said :
                                                        </p>
                                                        <p><em><?php echo $job_apply['apply_desc']; ?></em></p>
                                                    </div>

                                                    <?php if ($job_apply['status'] == 'pending') { ?>

                                                        <!-- Job attributes -->
                                                        <div class="entry-footer">
                                                            <!-- Display job attributes -->
                                                            <div class="text-end mx-4">
                                                                <?php
                                                                // Get the apply status for this job and profile
                                                                $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
                                                                ?>
                                                                <button type="button" id="applyButton"
                                                                    class="btn btn-outline-danger mr-3"
                                                                    onclick="window.location.href='declineJobAplly.php?jobId=<?php echo $job['id'] ?>&userId=<?php echo $job_apply['apply_profile_id'] ?>'">Decline</button>
                                                                <!-- <button type="button" id="applyButton" class="btn btn-success"
                                                                onclick="window.location.href='acceptJobAplly.php?jobId=<?php //echo $job['id'] ?>&userId=<?php //echo $job_apply['apply_profile_id'] ?>'">Accept</button> -->
                                                                <button type="button" id="applyButton" class="btn btn-success"
                                                                    onclick="togglePopup('<?php echo $job['id'] ?>', '<?php echo $job_apply['apply_profile_id'] ?>', '<?php echo $user_profile_id ?>')">Accept</button>


                                                            </div>
                                                        </div>

                                                        <?php
                                                    } else if ($job_apply['status'] == 'interview') {
                                                        $current_job_meeting_id = $meetingC->getMeetingIdByJobIdAndProfileId($job['id'], $job_apply['apply_profile_id']);
                                                        ?>

                                                            <div class="entry-footer">
                                                                <!-- Display job attributes -->
                                                                <div class="text-end mx-4">
                                                                    <button type="button" id="applyButton" class="btn btn-success"
                                                                        onclick="togglePopup1('<?php echo $popup_counter; ?>')">Interview</button>
                                                                    <!-- Popup -->
                                                                    <div id="popup1-<?php echo $popup_counter; ?>" class="popup">
                                                                        <div class="text-end mx-4">
                                                                            <p>Discription</p>
                                                                            <button type="submit"
                                                                                onclick="window.location.href='hireJobAplly.php?jobId=<?php echo $job['id'] ?>&userId=<?php echo $job_apply['apply_profile_id'] ?>&meeting_id=<?php echo $current_job_meeting_id; ?>'; togglePopup1('<?php echo $popup_counter; ?>')"
                                                                                class="btn btn-success mr-3">Hire</button>
                                                                            <button type="button"
                                                                                onclick="window.location.href='refuseJobAplly.php?jobId=<?php echo $job['id'] ?>&userId=<?php echo $job_apply['apply_profile_id'] ?>&meeting_id=<?php echo $current_job_meeting_id; ?>'; togglePopup1('<?php echo $popup_counter; ?>')"
                                                                                class="btn btn-danger mr-3">Refuse</button>
                                                                            <button type="button"
                                                                                onclick="togglePopup1('<?php echo $popup_counter; ?>')"
                                                                                class="btn btn-outline-danger mr-3">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php
                                                    }
                                                    ?>

                                                    <?php if ($job_apply['status'] == "pending" ) { ?>
                                                        <div>
                                                            <p class=".force_p mt-5">Chance of Success <a href="javascript:void(0)"
                                                                    onclick="show_success_data('<?php echo $job_apply['apply_id']; ?>')">View
                                                                    more</a></p>
                                                            <!-- progress bar -->
                                                            <?php if ($current_profile_sub == "advanced" || $current_profile_sub == "premium") { ?>
                                                            <?php $value = $resumeController->getApplyRank($job_apply['apply_id']); ?>
                                                            <progress id="progressBar" max="100"
                                                                value="<?php echo $value; ?>"></progress>
                                                            <!-- end progress bar -->
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                                <!-- .item-content -->
                                            </article>

                                            <?php
                                        }
                                    }
                                    ?>
                                    <br>
                                <?php endforeach; ?>




                                
                                <!-- #post-## -->

                                
                            </main>

                            
                            <div class="d-none d-lg-block divider-110"></div>
                        </div>
                    </div>
                </div>
            </section>


            <div id="popup-card" class="popup-card">
                <div class="popup-content">
                    <input type="hidden" id="popup-hidden-apply-id" value="">
                    <span id="close-popup" class="close">&times;</span>
                    <h3 id="popup-Name" class="text-capitalize"></h3>
                    <hr><br>
                    <p id="popup-Skills"></p>
                    <p id="popup-SkillsNbre"></p>
                    <div id="popup-SkillsList"></div>
                    <div class="progress-bar-container">
                        <p id="popup-progress-title" class="text-capitalize"></p>
                        <div class="progress-bar">
                            <div id="popup-ProgressBarFill" class="progress-bar-fill"></div>
                        </div>
                    </div>
                    <button id="view-resume-button" onclick="showPopupPdfView()"
                        class="btn btn-outline-primary mt-3">View Resume</button>
                </div>
            </div>

            <div id="popup-card-resume-pdf" class="popup-card">
                <div class="popup-content">
                    <span id="close-popup-resume-pdf" class="close">&times;</span>
                    <h3 id="popup-Name-resume-pdf" class="text-capitalize"></h3>
                    <hr><br>
                    <iframe id="pdfViewer" width="100%" height="600" frameborder="0"></iframe>
                </div>
            </div>



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
    <!-- Font Awesome library -->

    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/scripts.js"></script>
    <script src="./../../../front office assets/js/chatbot.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>


    <script>
        function openFullScreenImage(imageData) {
            var imageSrc = "data:image/jpeg;base64," + imageData;
            document.getElementById("fullScreenImage").src = imageSrc;
            $('#jobImageModal').modal('show');
        }
    </script>



    <!-- update JS -->

    <script>
        // Function to handle file input change for job image
        function handleJobImageChange(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                const jobImage = document.getElementById('update_job_image_display');
                const hiddenjobImageContainer = document.getElementById('hiddenJobImageContainer');

                // Set the source of hidden job image
                document.getElementById('hiddenJobImage').src = e.target.result;

                // Show the hidden job image container and hide the displayed cover
                jobImage.style.display = 'none';
                hiddenjobImageContainer.style.display = 'block';

                //console.log(e.target.result);
            };

            reader.readAsDataURL(file);
        }

        document.getElementById("updateJobForm").addEventListener("submit", function (event) {
            // Reset previous error messages

            document.getElementById("update_job_title_error").textContent = ""; // Reset error message for job title
            document.getElementById("update_company_error").textContent = ""; // Reset error message for company
            document.getElementById("update_location_error").textContent = ""; // Reset error message for location
            document.getElementById("update_description_error").textContent = ""; // Reset error message for description
            document.getElementById("update_salary_error").textContent = ""; // Reset error message for salary
            document.getElementById("update_category_error").textContent = "";
            // Reset other error messages for additional fields

            // Get input values

            var jobTitle = document.getElementById("update_job_title").value.trim();
            var company = document.getElementById("update_company").value.trim();
            var location = document.getElementById("update_location").value.trim();
            var description = document.getElementById("update_description").value.trim();
            var salary = document.getElementById("update_salary").value.trim();
            var category = document.getElementById("update_category").value.trim();
            // Get values for other input fields

            // Variable to store the common error message
            var errorMessage = "";



            // Validate job title (characters only)
            if (!/^[a-zA-Z\s]+$/.test(jobTitle)) {
                errorMessage = "Job title must contain only characters."; // Set common error message
                displayError("update_job_title_error", errorMessage, true); // Display error message
            }
            // Check if salary is not empty and contains only numbers
            if (!/^\d+(\.\d+)?$/.test(salary)) {
                errorMessage = "Salary must be a number."; // Set common error message
                displayError("update_salary_error", errorMessage, true); // Display error message
            }
            // Check if any input field is empty
            if (jobTitle === "") {
                errorMessage = "Job title is required."; // Set common error message
                displayError("update_job_title_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (company === "") {
                errorMessage = "Company is required."; // Set common error message
                displayError("update_company_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (location === "") {
                errorMessage = "Location is required."; // Set common error message
                displayError("update_location_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (description === "") {
                errorMessage = "Description is required."; // Set common error message
                displayError("update_description_error", errorMessage, true); // Display error message
            }
            // Check if any input field is empty
            if (salary === "") {
                errorMessage = "Salary is required."; // Set common error message
                displayError("update_salary_error", errorMessage, true); // Display error message
            }
            if (category === "") {
                errorMessage = "Category is required."; // Set common error message
                displayError("update_category_error", errorMessage, true); // Display error message
            }

            // Display error message for other fields

            // Prevent form submission if there's an error message
            if (errorMessage !== "") {
                event.preventDefault();
            }
        });



        // Listen for input event on job title field
        document.getElementById("update_job_title").addEventListener("input", function (event) {
            var jobTitle = this.value.trim(); // Get value of job title field
            var jobTitleError = document.getElementById("update_job_title_error"); // Get error message element

            // Validate job title format (characters only)
            if (jobTitle === "") {
                displayError("update_job_title_error", "Title is required.", true); // Display error message for empty job title
            } else if (/^[a-zA-Z\s]+$/.test(jobTitle)) {
                displayError("update_job_title_error", "Valid Job Title", false); // Display valid message for job title
            } else {
                displayError("update_job_title_error", "Job title must contain only characters.", true); // Display error message for invalid job title
            }
        });

        // Listen for input event on company field
        document.getElementById("update_company").addEventListener("input", function (event) {
            var company = this.value.trim(); // Get value of company field
            var companyError = document.getElementById("update_company_error"); // Get error message element

            // Validate if company is empty
            if (company === "") {
                displayError("update_company_error", "Company is required.", true); // Display error message for empty company
            } else {
                displayError("update_company_error", "Valid company", false); // Display valid message for company
            }
        });

        // Listen for input event on job salary field
        document.getElementById("update_salary").addEventListener("input", function (event) {
            var jobSalary = this.value.trim(); // Get value of job salary field
            var jobSalaryError = document.getElementById("update_salary_error"); // Get error message element

            // Validate if salary is empty
            if (jobSalary === "") {
                displayError("update_salary_error", "Salary is required.", true); // Display error message for empty salary
            } else if (/^\d+(\.\d+)?$/.test(jobSalary)) {
                displayError("update_salary_error", "Valid Job Salary", false); // Display valid message for salary
            } else {
                displayError("update_salary_error", "Salary must be a number.", true); // Display error message for invalid salary format
            }
        });

        // Listen for input event on company field
        document.getElementById("update_company").addEventListener("input", function (event) {
            var company = this.value.trim(); // Get value of company field
            var companyError = document.getElementById("update_company_error"); // Get error message element

            // Validate if company is empty
            if (company === "") {
                displayError("update_company_error", "Company is required.", true); // Display error message for empty company
            } else {
                displayError("update_company_error", "Valid company", false); // Display valid message for company
            }
        });

        // Listen for input event on location field
        document.getElementById("update_location").addEventListener("input", function (event) {
            var location = this.value.trim(); // Get value of location field
            var locationError = document.getElementById("update_location_error"); // Get error message element

            // Validate if location is empty
            if (location === "") {
                displayError("update_location_error", "Location is required.", true); // Display error message for empty location
            } else {
                displayError("update_location_error", "Valid location", false); // Display valid message for location
            }
        });

        // Listen for input event on description field
        document.getElementById("update_description").addEventListener("input", function (event) {
            var description = this.value.trim(); // Get value of description field
            var descriptionError = document.getElementById("update_description_error"); // Get error message element

            // Validate if description is empty
            if (description === "") {
                displayError("update_description_error", "Description is required.", true); // Display error message for empty description
            } else {
                displayError("update_description_error", "Valid description", false); // Display valid message for description
            }
        });

        // Listen for input event on description field
        document.getElementById("update_category").addEventListener("input", function (event) {
            var category = this.value.trim(); // Get value of description field
            var categoryError = document.getElementById("update_category_error"); // Get error message element

            // Validate if description is empty
            if (category === "") {
                displayError("update_category_error", "category is required.", true); // Display error message for empty description
            } else {
                displayError("update_category_error", "Valid category", false); // Display valid message for description
            }
        });


        // Function to display error message
        function displayError(elementId, errorMessage, isError) {
            var errorElement = document.getElementById(elementId);
            errorElement.textContent = errorMessage;
            errorElement.classList.toggle("text-danger", isError);
            errorElement.classList.toggle("text-success", !isError);
        }
    </script>


    <script>
        function togglePopup(jobId, userId, mod_id) {
            if (jobId != '' && userId != '' && mod_id != '') {
                document.getElementById('jobId-pop-interview').value = jobId;
                document.getElementById('userId-pop-interview').value = userId;
                document.getElementById('mod_id-pop-interview').value = mod_id;
            }

            var popup = document.getElementById('popup');
            var overlay = document.getElementById('overlay');

            if (popup.style.display === 'block') {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            } else {
                popup.style.display = 'block';
                overlay.style.display = 'block';
            }
        }


        function togglePopup1(popup_counter) {
            var popup = document.getElementById('popup1-' + popup_counter);
            var overlay = document.getElementById('overlay');

            if (popup.style.display === 'block') {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            } else {
                popup.style.display = 'block';
                overlay.style.display = 'block';
            }
        }

        function verify_meeting_data() {
            meeting_desc = document.getElementById('meeting_desc-popup-interview').value;
            meeting_at = document.getElementById('meeting_at-popup-interview').value;

            descWithoutSpaces = meeting_desc.replace(/ /g, '');
            if (descWithoutSpaces == '') {
                document.getElementById('desc_error').innerHTML = 'Please enter the meeting description';
                return false;
            } else {
                document.getElementById('desc_error').innerHTML = '';
            }

            // Check if meeting_at is not empty
            if (!meeting_at) {
                document.getElementById('meeting_at_error').innerHTML = 'Please enter the meeting date';
                return false;
            } else {
                document.getElementById('meeting_at_error').innerHTML = '';
            }

            // Parse meeting_at string to a Date object
            var meetingDate = new Date(meeting_at);
            if (isNaN(meetingDate.getTime())) {
                document.getElementById('meeting_at_error').innerHTML = 'Invalid date format';
                return false;
            }

            // Get current date
            var currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0); // Set current time to midnight for accurate comparison

            // Check if meeting_at is today or a future date
            if (meetingDate < currentDate) {
                document.getElementById('meeting_at_error').innerHTML = 'Meeting date must be today or a future date';
                return false;
            } else {
                document.getElementById('meeting_at_error').innerHTML = '';
            }

            return true;
        }

    </script>

    <script>

        function fetchData(apply_id, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var responseData = JSON.parse(xhr.responseText);
                        console.log(responseData);
                        // Call the callback function with the response data
                        callback(responseData);
                    } else {
                        // Handle errors
                        console.error('Request failed with status:', xhr.status);
                    }
                }
            };
            xhr.open('GET', 'get_apply_json_data.php?id=' + apply_id, true);
            xhr.send();
        }

        // function show_success_data(apply_id) {

        //     fetchData(apply_id, function (responseData) {
        //         // Do something with the fetched data
        //         console.log(responseData);
        //     });

        // }







        function show_success_data(apply_id) {
            fetchData(apply_id, function (responseData) {

                document.getElementById('popup-hidden-apply-id').value = apply_id;

                var popupName = document.getElementById("popup-Name");
                var popupSkills = document.getElementById("popup-Skills");
                var popupSkillsNbre = document.getElementById("popup-SkillsNbre");
                var popupSkillsList = document.getElementById("popup-SkillsList");
                var popupProgressBarFill = document.getElementById("popup-ProgressBarFill");
                var popupProgressBar = document.getElementById("popup-progress-title");

                popupName.innerHTML = "<b>Category:</b> " + responseData.category_name;
                popupSkills.innerHTML = "<b>Skills Required:</b> " + responseData.nb_of_all_skills_needed;
                popupSkillsNbre.innerHTML = "<b>Skills Found:</b> " + responseData.nb_of_skills_found;

                var skillsHTML = '<b>Skills:</b><ul class="skills-list">';
                responseData.skills_found.forEach(function (skill) {
                    skillsHTML += '<li class="found"><i class="fas fa-check"></i> ' + skill + '</li>';
                });
                if (responseData.skills_not_found.length) {
                    responseData.skills_not_found.forEach(function (skill) {
                        skillsHTML += '<li class="not-found"><i class="fas fa-times"></i> ' + skill + '</li>';
                    });
                }
                skillsHTML += '</ul>';
                popupSkillsList.innerHTML = skillsHTML;

                popupProgressBar.innerHTML = "<b>Chance of Success</b>"

                // Calculate the percentage of skills found
                var percentage = (responseData.nb_of_skills_found / responseData.nb_of_all_skills_needed) * 100;
                percentage = percentage.toFixed(2);

                // Update the progress bar
                popupProgressBarFill.style.width = percentage + '%';
                popupProgressBarFill.innerText = parseInt(percentage) + '%';

                var modal = document.getElementById("popup-card");
                modal.style.display = "block";
            });
        }

        function fetchData2(apply_id, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Get the response as a blob
                        var blob = xhr.response;

                        // Call the callback function with the blob data
                        callback(blob);
                    } else {
                        // Handle errors
                        console.error('Request failed with status:', xhr.status);
                    }
                }
            };
            xhr.open('GET', 'get_apply_resume_pdf.php?id=' + apply_id, true);
            xhr.responseType = 'blob'; // Set the responseType to 'blob' for binary data
            xhr.send();
        }

        function showPopupPdfView() {
            
            apply_id = document.getElementById('popup-hidden-apply-id').value
            
            //update the title
            var popupName2 = document.getElementById("popup-Name-resume-pdf");
            popupName2.innerHTML = "<b>Resume Viewer</b>";

            //update the pdf view
            fetchData2(apply_id, function (blob) {
                // Create a URL for the Blob object
                var blobUrl = URL.createObjectURL(blob);

                // Now you can use this URL to display the PDF
                // For example, if you have an <iframe> with id "pdfViewer":
                var pdfViewer = document.getElementById('pdfViewer');
                pdfViewer.src = blobUrl;
            });

            var modal2 = document.getElementById("popup-card-resume-pdf");
            modal2.style.display = "block";
        }

        var modal = document.getElementById("popup-card");
        var closeButton = document.getElementById("close-popup");

        var modal2 = document.getElementById("popup-card-resume-pdf");
        var closeButton2 = document.getElementById("close-popup-resume-pdf");

        closeButton.onclick = function () {
            modal.style.display = "none";
        };

        closeButton2.onclick = function () {
            modal2.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        window.onclick = function (event) {
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        };






    </script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>