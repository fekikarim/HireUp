<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/resume_con.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


// Create an instance of JobController
$jobController = new JobController();
$profileController = new ProfileC();
$applyController = new ApplyController();
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
            header("Location: jobs.php");
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
$jobs = $applyController->getAppliedJobsByProfileId($userId);


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


$block_call_back = 'false';
$access_level = "else";
include ('./../../../View/callback.php');


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



    <div id="overlay" class="overlay"></div>


    <div class="preloader">
        <div class="preloader_image"></div>
    </div>


    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">

        <div id="box_wrapper">


            <!-- header -->
            <?php include ('../front_header.php') ?>


            <section class="page_title cs s-py-25" style="background-color: #27374D !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>
            </section>

            <section class="page_title cs s-py-25" style="background-color: #27374D !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">My Applies</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">My Applies</li>
                            </ol>
                        </div>

                        <div class="divider-50"></div>
                    </div>
                </div>
            </section>

            <div class="d-none d-lg-block divider-60"></div>

            <div class="text-center align-items-center justify-content-center">
                <button onclick="window.location.href = './jobs.php'" class="btn btn-outline-primary"><i
                        class="far fa-plus-square"></i> Jobs</button>
                <button onclick="window.location.href = './jobs_list.php'" class="btn btn-maincolor2 ml-3"><i
                        class="far fa-list-alt"></i> Jobs List</button>
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

                                <!-- Edit Popup -->

                                <div class="text-end mx-4">
                                    <form id="editForm" action="./editApplyDesc.php" method="post">
                                        <div id="popup" class="popup">
                                            <div class="text-end mx-4">
                                                <input type="hidden" name="apply_id" id="apply_id-popup" value="">
                                                <p>Discription</p>
                                                <textarea name="apply_desc" id="apply_desc-popup"
                                                    class="form-control mb-3"></textarea>
                                                <button type="button" onclick="togglePopup('')"
                                                    class="btn btn-outline-danger mr-3">Decline</button>
                                                <button type="submit" onclick="togglePopup('')"
                                                    class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- End Edit Popup -->

                                <?php foreach ($jobs as $job): ?>
                                    <?php $job_profile = $profileController->getProfileById($job['jobs_profile']); ?>
                                    <!-- Display job image if exists -->

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
                                    <?php $apply_data = $applyController->getApplyFromPrfIdJobId($userId, $job['id']); ?>
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
                                                <p><em><?= $apply_data['apply_desc']; ?></em></p>
                                            </div>
                                            <!-- Job attributes -->
                                            <div class="entry-footer">
                                                <!-- Display job attributes -->
                                                <div class="text-end mx-4">
                                                    <?php
                                                    // Get the apply status for this job and profile
                                                    $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
                                                    ?>
                                                    <?php if ($status == "pending"): ?>
                                                        <!-- Pending form -->
                                                        <form id="pendingForm" action="./pendingJob.php" method="post">
                                                            <input type="hidden" id="jobId" name="jobId"
                                                                value="<?php echo $job['id']; ?>">
                                                            <input type="hidden" id="userId" name="userId"
                                                                value="<?php echo $userId; ?>">
                                                            <button type="submit" id="pendingButton"
                                                                class="btn btn-outline-secondary">Pending</button>
                                                            <button type="button"
                                                                onclick="togglePopup('<?php echo $apply_data['apply_desc']; ?>', '<?php echo $apply_data['apply_id']; ?>')"
                                                                id="pendingButton"
                                                                class="btn btn-outline-secondary ml-3">Edit</button>
                                                        </form>
                                                    <?php elseif ($status == "interview"): ?>
                                                        <!-- HiredUp form -->
                                                        <button type="submit" id="hiredupButton"
                                                            class="btn btn-outline-success">Interview</button>
                                                    <?php elseif ($status == "HiredUp"): ?>
                                                        <!-- HiredUp form -->
                                                        <button type="submit" disabled id="hiredupButton"
                                                            class="btn btn-outline-success">HiredUp</button>
                                                    <?php else: ?>
                                                        <!-- Apply job form -->
                                                        <form id="applyForm" action="./applyJob.php" method="post">
                                                            <input type="hidden" id="jobId" name="jobId"
                                                                value="<?php echo $job['id']; ?>">
                                                            <input type="hidden" id="userId" name="userId"
                                                                value="<?php echo $userId; ?>">
                                                            <button type="submit" id="applyButton"
                                                                class="btn btn-outline-info">Apply</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <?php if ($status == "pending" && ($current_profile_sub == "advanced" && $current_profile_sub == "premium")) { ?>
                                                <div>
                                                    <p class=".force_p mt-5">Chance of Success <a href="javascript:void(0)"
                                                            onclick="show_success_data('<?php echo $apply_data['apply_id']; ?>')">View
                                                            more</a></p>
                                                    <!-- progress bar -->
                                                    <?php $value = $resumeController->getApplyRank($apply_data['apply_id']); ?>
                                                    <progress id="progressBar" max="100"
                                                        value="<?php echo $value; ?>"></progress>
                                                    <!-- end progress bar -->
                                                </div>
                                            <?php } ?>
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


            <div id="popup-card" class="popup-card">
                <div class="popup-content">
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

    <script>
        function togglePopup(desc, apply_id) {
            if (desc != '' && apply_id != '') {
                document.getElementById('apply_desc-popup').value = desc;
                document.getElementById('apply_id-popup').value = apply_id;
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
    </script>

    <script>

        function fetchData(apply_id, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var responseData = JSON.parse(xhr.responseText);
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

        var modal = document.getElementById("popup-card");
        var closeButton = document.getElementById("close-popup");

        closeButton.onclick = function () {
            modal.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };


    </script>

    <!-- voice recognation -->
    <script type="text/javascript"
        src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>