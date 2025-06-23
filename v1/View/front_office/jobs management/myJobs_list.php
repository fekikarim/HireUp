<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/categoryC.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


// Create an instance of JobController
$jobController = new JobController();
$profileController = new ProfileC();
$applyController = new ApplyController();
$categoryC = new categoryController();


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
        $map_lat = $_POST["longitude"];
        $map_lng = $_POST["latitude"];

        if (!empty($_FILES['job_image']['name']) && $_FILES['job_image']['error'] === 0) {

            // Get profile photo and cover data
            $job_image_tmp_name = $_FILES['job_image']['tmp_name'];
            $job_image = file_get_contents($job_image_tmp_name);

            // Only echo the result if the job update is successful
            $result = $jobController->updateJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $map_lng, $map_lat);

            if ($result !== false) {
                // Redirect to prevent form resubmission

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            }
        } else {
            // Only echo the result if the job update is successful
            $result = $jobController->updateJobWithoutImage($job_id, $title, $company, $location, $description, $salary, $category, $map_lng, $map_lat);

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
            header("Location: myJobs_list.php");
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
$jobs = $jobController->getAllJobsWhereProfileId($userId);

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

    <style>
        .profile-post-pic {
            width: 55px;
            /* Adjust as needed */
            height: 55px;
            /* Adjust as needed */
            border-radius: 50%;
            /* To make the image circular */
            margin-right: 10px;
            /* Adjust spacing between profile picture and message */
        }

        #job_profile_id {
            background-color: #f5f5f5;
            padding: 1.5%;
        }
    </style>



    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

    <!-- Overlay to cover the background -->
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


            <section class="page_title cs s-py-25" style="background-color: #116D6E !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #116D6E !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">My Jobs</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">My Jobs</li>
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
                <button onclick="window.location.href = './applyJobs_list.php'" class="btn btn-success ml-3"><i
                        class="far fa-list-alt"></i> My Applies</button>
                <button onclick="window.location.href = './jobs_list.php'" class="btn btn-maincolor2 ml-3"><i
                        class="far fa-list-alt"></i> Jobs List</button>
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
                                    <input type="hidden" id="latitude" name="latitude" value="">
                                    <input type="hidden" id="longitude" name="longitude" value="">
                                    <input type="hidden" id="place-name" name="place-name" value="Unknown place">
                                    <input type="text" id="update_location" name="location" class="form-control">
                                    <!-- <i class="fa-solid fa-map-location-dot" onclick="mapSelectionPopUp()"></i> -->
                                    <i class="fa-solid fa-map-location-dot" onclick="mapSelectionPopUp()"></i>

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

                                <?php foreach ($jobs as $job): ?>
                                    <?php $job_profile = $profileController->getProfileById($job['jobs_profile']); ?>

                                    <div class="flex-fill ps-2 mb-2 mt-2" id="job_profile_id">
                                        <a
                                            href="./../profiles_management/profile.php?profile_id=<?php echo $job_profile ?>"><img
                                                src="data:image/jpeg;base64,<?= base64_encode($job_profile['profile_photo']) ?>"
                                                alt="Profile picture" class="profile-post-pic"></a>
                                        <a href="profile.php"
                                            class="text-decoration-none fw-bold"><?= $job_profile['profile_first_name'] . ' ' . $job_profile['profile_family_name'] ?></a>
                                        <!-- Dropdown menu -->

                                    </div>
                                    <!-- Display job image if exists -->
                                    <?php if (!empty($job['job_image'])): ?>
                                        <div class="item-media post-thumbnail embed-responsive-3by2">
                                            <div id="job-<?= $job['id'] ?>"></div>
                                            <a href="#"
                                                onclick="openFullScreenImage('<?= base64_encode($job['job_image']) ?>')">
                                                <img src="data:image/jpeg;base64,<?= base64_encode($job['job_image']) ?>"
                                                    alt="Job Image">
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <article
                                        class="text-center text-md-left vertical-item content-padding bordered post type-post status-publish format-standard has-post-thumbnail sticky position-relative">
                                        <!-- Dropdown menu -->
                                        <?php if ($user_profile_id == $job['jobs_profile']) { ?>

                                            <?php
                                            $cat_data = $categoryC->getCategoryById($job['id_category']);
                                            $category_name = $cat_data['name_category'];
                                            ?>

                                            <div class="dropdow mr-3" style="position: absolute; top: 10px; right: 10px;">
                                                <span class="dropdown" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    style="cursor: pointer; color: #000; font-size: 35px;">...</span>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <button class="dropdown-item edit-btn" data-job-id="<?= $job['id'] ?>"
                                                        data-job-title="<?= $job['title'] ?>"
                                                        data-company="<?= $job['company'] ?>"
                                                        data-location="<?= $job['location'] ?>"
                                                        data-description="<?= $job['description'] ?>"
                                                        data-salary="<?= $job['salary'] ?>"
                                                        data-category="<?= $category_name ?>"
                                                        data-jobImg="<?php echo base64_encode($job['job_image']) ?>"
                                                        data-lng="<?php echo $job['lng']; ?>"
                                                        data-lat="<?php echo $job['lat']; ?>">Edit</button>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this job?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <!-- Job content -->
                                        <div class="item-content">
                                            <header class="entry-header">
                                                <h3 class="entry-title">
                                                    <a href="#link" rel="bookmark">
                                                        <?= $job['title']; ?>
                                                    </a>
                                                </h3>
                                            </header>
                                            <!-- Job description -->
                                            <div class="entry-content">
                                                <p>
                                                    <?= $job['description']; ?>
                                                </p>
                                            </div>
                                            <!-- Job attributes -->
                                            <?php
                                            $cat_data = $categoryC->getCategoryById($job['id_category']);
                                            $job_name_category = $cat_data['name_category'];

                                            ?>
                                            <div class="entry-footer">
                                                <i class="color-main fa fa-user"></i>
                                                <a href="#"> <?= $job['company']; ?> </a>
                                                <i class="color-main fa fa-calendar"></i>
                                                <a href="#"> <?= $job['date_posted']; ?> </a>
                                                <i class="color-main fa fa-map"></i>
                                                <a href="#"
                                                    onclick="mapStaticMapPopUp('<?= $job['lng']; ?>', '<?= $job['lat']; ?>', '<?= $job['location']; ?>')">
                                                    <?= $job['location']; ?> </a>
                                                <i class="color-main fa fa-money"></i>
                                                <a href="#"> <?= $job['salary']; ?> </a>
                                                <i class="color-main fa fa-tag"></i>
                                                <a href="#"> <?= $job_name_category; ?> </a>
                                                <!-- Display category here -->
                                                <!-- Apply form based on status -->
                                                <?php

                                                // Assuming $applyController is already instantiated
                                                $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
                                                ?>
                                                <?php if ($user_profile_id != $job['jobs_profile']) { ?>
                                                    <?php if ($status == "pending"): ?>
                                                        <!-- Pending form -->
                                                        <div class="text-end mx-4">
                                                            <form id="pendingForm" action="./pendingJob.php" method="post">
                                                                <input type="hidden" id="jobId" name="jobId"
                                                                    value="<?php echo $job['id']; ?>">
                                                                <input type="hidden" id="userId" name="userId"
                                                                    value="<?php echo $userId; ?>">
                                                                <button type="submit" id="pendingButton"
                                                                    class="btn btn-outline-secondary">Pending</button>
                                                            </form>
                                                        </div>
                                                    <?php elseif ($status == "HiredUp"): ?>
                                                        <!-- HiredUp form -->
                                                        <div class="text-end mx-4">
                                                            <button type="submit" disabled id="hiredupButton"
                                                                class="btn btn-outline-success">HiredUp</button>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Apply job form -->
                                                        <div class="text-end mx-4">
                                                            <form id="applyForm" action="./applyJob.php" method="post">
                                                                <input type="hidden" id="jobId" name="jobId"
                                                                    value="<?php echo $job['id']; ?>">
                                                                <input type="hidden" id="userId" name="userId"
                                                                    value="<?php echo $userId; ?>">
                                                                <button type="button" id="applyButton" class="btn btn-outline-info"
                                                                    onclick="togglePopup()">Apply</button>

                                                                <!-- Popup -->
                                                                <div id="popup" class="popup">
                                                                    <div class="text-end mx-4">
                                                                        <p>Discription</p>
                                                                        <textarea name="apply_desc" id="apply_desc"
                                                                            class="form-control mb-3"></textarea>
                                                                        <button type="button" onclick="togglePopup()"
                                                                            class="btn btn-outline-danger mr-3">Decline</button>
                                                                        <button type="submit" onclick="togglePopup()"
                                                                            class="btn btn-success">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php } else { ?>

                                                    <div class="text-end mx-4">

                                                        <button type="submit" id="applyButton" class="btn btn-outline-info"
                                                            onclick="window.location.href='career_explorers.php'">Check
                                                            Appliers</button>
                                                    </div>

                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- .item-content -->
                                    </article>
                                    <br>
                                <?php endforeach; ?>





                                <!-- #post-## -->


                            </main>



                            <div class="d-none d-lg-block divider-110"></div>
                        </div>
                    </div>
                </div>
            </section>

            <div id="popup-card-map" class="popup-card">
                <div class="popup-content">
                    <span id="close-popup-map" class="close">&times;</span>
                    <h3 id="popup-Name" class="text-capitalize">Map</h3>
                    <iframe id="face_detection_iframe" src="./../map/map_interective.php"></iframe>
                </div>
            </div>

            <div id="popup-card-map-static" class="popup-card">
                <div class="popup-content">
                    <span id="close-popup-map-static" class="close">&times;</span>
                    <h3 id="popup-Name" class="text-capitalize">Map</h3>
                    <iframe id="popup-card-map-static-iframe" src="./../map/map_static.php"></iframe>
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
        function togglePopup() {
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
    </script>

    <!-- Map Selection Popup Modal -->
    <script>
        function mapSelectionPopUp() {
            console.log("Map selection popup opened");
            lat = document.getElementById("longitude").value;
            lng = document.getElementById("latitude").value;
            place = document.getElementById("update_location").value;
            var modal = document.getElementById("popup-card-map");
            var map_iframe = document.getElementById("face_detection_iframe");
            modal.style.display = "block";
            map_iframe.src = `./../map/map_interective.php?lng=${lng}&lat=${lat}&place=${place}`;
        }

        var modal_map = document.getElementById("popup-card-map");
        var closeButton_map = document.getElementById("close-popup-map");

        closeButton_map.onclick = function () {
            modal_map.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal_map) {
                modal_map.style.display = "none";
            }
        };
    </script>

    <script>
        window.addEventListener('message', receiveMessageFromIframe, false);

        function receiveMessageFromIframe(event) {
            console.log('Message received from iframe:', event.data);
            if (event.data) {
                console.log(event.data);
                if (event.data.message == "the location is :") {
                    // Parse JSON data received from the iframe

                    // Access properties of the JSON object
                    //console.log('Message:', jsonData.message);
                    //console.log('Data:', jsonData.data);
                    document.getElementById('latitude').value = event.data.data.lat;
                    document.getElementById('longitude').value = event.data.data.lng;

                    // Listen for input event on location field

                    var location = document.getElementById('update_location').value.trim(); // Get value of location field
                    var mapLat = document.getElementById("latitude").value.trim();
                    var mapLng = document.getElementById("longitude").value.trim();

                    // Validate if location is empty
                    if (location === "" || mapLat == "" || mapLng == "") {
                        if (location === "") {
                            displayError("update_location_error", "Location is required.", true); // Display error message for empty location
                        } else {
                            displayError("update_location_error", "Please selecte your location on the map.", true); // Display error message for empty map selection
                        }
                    } else {
                        displayError("update_location_error", "Valid location", false); // Display valid message for location
                    };




                }
            }
        }
    </script>

    <!-- Map Static Popup Modal -->
    <script>
        function mapStaticMapPopUp(lng, lat, place) {
            console.log("Map selection popup opened");
            var modal = document.getElementById("popup-card-map-static");
            var map_iframe = document.getElementById("popup-card-map-static-iframe");
            map_iframe.src = `./../map/map_static.php?lng=${lng}&lat=${lat}&place=${place}`;
            modal.style.display = "block";

        }

        var modal_map2 = document.getElementById("popup-card-map-static");
        var closeButton_map2 = document.getElementById("close-popup-map-static");

        closeButton_map2.onclick = function () {
            modal_map2.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal_map2) {
                modal_map2.style.display = "none";
            }
        };
    </script>

    <!-- voice recognation -->
    <script type="text/javascript"
        src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>