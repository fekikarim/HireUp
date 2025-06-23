
<?php
function getUserLocation() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $api_url = "https://freegeoip.app/json/";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirections
    $response = curl_exec($ch);
    //var_dump($response);
    curl_close($ch);

    if ($response) {
        return $response;
    } else {
        return false;
    }
}
?>



<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/resume_con.php';
require_once __DIR__ . '/../../../Controller/categoryC.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


// Create an instance of JobController
$jobController = new JobController();
$profileController = new ProfileC();
$applyController = new ApplyController();
$resumeController = new ResumeController();
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
//old one by nessrine
//$jobs = $jobController->getAllJobsSortedByProfileEducation($userId);

// new one by hama (by distance)
//$jobs = $jobController->SortJobsByDistance();

// new one by hama (by category)
$desired_categories = ['Software Developer', 'Web Dev', 'Content Creator'];
$desired_categories1 = ['Content Creator', 'Software Developer', 'Web Dev'];
$jobs = $jobController->SortJobsByCategory($desired_categories1);

// var_dump($jobs);
// exit();



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
                            <h1 class="">Jobs</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">Jobs</li>
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
                            <main class="col-lg-7 col-xl-8 order-lg-2">
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

                                <!-- decline aplly modal -->

                                <form id="applyForm" action="./applyJob.php" method="post"
                                    enctype="multipart/form-data">
                                    <div id="popup" class="popup">
                                        <input type="hidden" id="jobId-popup" name="jobId" value="">
                                        <input type="hidden" id="userId-popup" name="userId" value="">
                                        <div class="text-end mx-4">
                                            <p>Discription</p>
                                            <textarea name="apply_desc" id="apply_desc"
                                                class="form-control mb-3"></textarea>
                                            <div id="desc_error" style="color: red;"></div>
                                            <p>Resume</p>
                                            <input type="file" name="resume_data" id="resume_data"
                                                accept="application/pdf" class="form-control mb-3">
                                            <div id="resume_error" style="color: red;"></div>
                                            <button type="button" onclick="togglePopup('', '')"
                                                class="btn btn-outline-danger mr-3">Decline</button>
                                            <button type="submit" onclick="return check_apply_data()"
                                                class="btn btn-success">Apply</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- end decline aplly modal -->


                                <?php foreach ($jobs as $job): ?>
                                <?php 
                                    $job_category_data = $categoryC->getCategoryById($job['id_category']);
                                    $job_category_name = $job_category_data['name_category'];
                                 ?>
                                    <!-- Display job image if exists -->
                                    <?php if (!empty($job['job_image'])): ?>
                                        <div class="item-media post-thumbnail embed-responsive-3by2">
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

                                            <div class="dropdow mr-3" style="position: absolute; top: 10px; right: 10px;">
                                                <span class="dropdown" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    style="cursor: pointer; color: #000; font-size: 35px;">...</span>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <!-- <button class="dropdown-item edit-btn" data-job-id="<?//= $job['id'] ?>"
                                                        data-job-title="<?//= $job['title'] ?>"
                                                        data-company="<?//= $job['company'] ?>"
                                                        data-location="<?//= $job['location'] ?>"
                                                        data-description="<?//= $job['description'] ?>"
                                                        data-salary="<?//= $job['salary'] ?>"
                                                        data-category="<?//= $job['name_category'] ?>"
                                                        data-jobImg="<?php //echo base64_encode($job['job_image']) ?>">Edit</button>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="job_id" value="<?//= $job['id'] ?>">
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this job?')">Delete</button> -->
                                                        <button type="button" class="dropdown-item"
                                                            onclick="window.location.href = 'myJobs_list.php#job-<?= $job['id'] ?>'">Check It</button>
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
                                                <a href="#"> <?= $job_category_name; ?> </a>
                                                <!-- Display category here -->
                                                <!-- Apply form based on status -->
                                                <?php

                                                // Assuming $applyController is already instantiated
                                                $status = $applyController->getApplyStatusFromPrfIdJobId($userId, $job['id']);
                                                $current_apply_id = $applyController->getApplyIdByJobIdAndProfileId($job['id'], $userId);
                                                $current_apply = $applyController->getApplyById($current_apply_id);
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
                                                    <?php elseif ($status == "interview"): ?>
                                                        <!-- HiredUp form -->
                                                        <div class="text-end mx-4">
                                                            <button type="submit" disabled id="hiredupButton"
                                                                class="btn btn-outline-success"
                                                                onclick="togglePopup1()">Interview</button>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Apply job form -->
                                                        <div class="text-end mx-4">
                                                            <button type="button" id="applyButton" class="btn btn-outline-info"
                                                                onclick="togglePopup('<?php echo $job['id']; ?>', '<?php echo $userId; ?>')">Apply</button>
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

                                            <?php if ($status == "pending") { ?>
                                                <div>
                                                    <p class="mt-5">Chance of Success <a href="javascript:void(0)"
                                                            onclick="show_success_data('<?php echo $current_apply['apply_id']; ?>')">View
                                                            more</a></p>
                                                    <!-- progress bar -->
                                                    <?php $value = $resumeController->getApplyRank($current_apply['apply_id']); ?>
                                                    <progress id="progressBar" max="100"
                                                        value="<?php echo $value; ?>"></progress>
                                                    <!-- end progress bar -->
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </article>
                                    <br>
                                <?php endforeach; ?>




                                <article
                                    class="cover-image ds s-overlay post type-post status-publish format-status has-post-thumbnail">
                                    <div class="post-thumbnail">
                                        <img src="./../../../front office assets/images/blog-2.jpg" alt="" />
                                    </div>
                                    <!-- .post-thumbnail -->
                                    <header class="entry-header">
                                        <img src="./../../../front office assets/images/testimonial.jpg" class="avatar"
                                            alt="" />
                                        <div class="entry-meta">
                                            <h6>Status</h6>
                                            <a class="url" href="blog-left.html">June 7, 2017</a>
                                        </div>
                                    </header>
                                    <h3 class="entry-title">Post format: Status</h3>
                                </article>
                                <!-- #post-## -->

                                <nav class="ls navigation pagination" role="navigation">
                                    <h2 class="screen-reader-text">Posts navigation</h2>
                                    <div class="nav-links">
                                        <a class="prev page-numbers" href="blog-left.html">
                                            <i class="fa fa-chevron-left"></i>
                                            <span class="screen-reader-text">Previous page</span>
                                        </a>
                                        <a class="page-numbers" href="blog-left.html">
                                            <span class="meta-nav screen-reader-text">Page </span>
                                            1
                                        </a>
                                        <span class="page-numbers current">
                                            <span class="meta-nav screen-reader-text">Page </span>
                                            2
                                        </span>
                                        <a class="page-numbers" href="blog-left.html">
                                            <span class="meta-nav screen-reader-text">Page </span>
                                            3
                                        </a>
                                        <a class="next page-numbers" href="blog-left.html">
                                            <span class="screen-reader-text">Next page</span>
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </nav>
                            </main>

                            <aside class="col-lg-5 col-xl-4 order-lg-1">
                                <div class="widget-title widget_apsc_widget">
                                    <h3>Get In Touch</h3>
                                    <div class="apsc-icons-wrapper clearfix apsc-theme-4">
                                        <div class="apsc-each-profile">
                                            <a class="apsc-facebook-icon clearfix"
                                                href="https://www.facebook.com/profile.php?id=61557532202485">
                                                <div class="apsc-inner-block">
                                                    <span class="social-icon">
                                                        <i class="fa fa-facebook apsc-facebook"></i>
                                                        <span class="media-name">Facebook</span>
                                                    </span>
                                                    <span class="apsc-count">35</span>
                                                    <span class="apsc-media-type">Fans</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="apsc-each-profile">
                                            <a class="apsc-instagram-icon clearfix"
                                                href="https://www.instagram.com/hire.up.tn/">
                                                <div class="apsc-inner-block">
                                                    <span class="social-icon">
                                                        <i class="fa fa-instagram apsc-instagram"></i>
                                                        <span class="media-name">Instagram</span>
                                                    </span>
                                                    <span class="apsc-count">0</span>
                                                    <span class="apsc-media-type">Followers</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="apsc-each-profile">
                                            <a class="apsc-google-plus-icon clearfix" href="#">
                                                <div class="apsc-inner-block">
                                                    <span class="social-icon">
                                                        <i class="apsc-google fa fa-google"></i>
                                                        <span class="media-name">google+</span>
                                                    </span>
                                                    <span class="apsc-count">0</span>
                                                    <span class="apsc-media-type">Followers</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget-title widget_mailchimp mt-50">
                                    <h3>Newsletter</h3>
                                    <form class="signup" action="http://webdesign-finder.com/html/invenir-consult/"
                                        method="get">
                                        <div class="form-group mt-0">
                                            <input name="email" type="email" class="mailchimp_email form-control"
                                                placeholder="Email Address" />
                                        </div>
                                        <p>
                                            Enter your email address here always to be updated. We
                                            promise not to spam!
                                        </p>
                                    </form>
                                </div>

                                <div class="widget widget_recent_posts mt-50">
                                    <h3>flickr widget</h3>
                                    <div class="widget widget_flickr">
                                        <ul class="flickr_ul"></ul>
                                    </div>
                                </div>

                                <div class="widget widget_recent_posts mt-50">
                                    <h3>Recent Posts</h3>
                                    <ul class="media-list darklinks">
                                        <li class="media">
                                            <a title="#" class="media-left" href="#">
                                                <img src="./../../../front office assets/images/widget_02.jpg" alt="" />
                                            </a>
                                            <div class="media-body">
                                                <h4>
                                                    <a href="#">Modernising our Talent Programmes</a>
                                                </h4>
                                                <p>
                                                    <i class="color-main fa fa-calendar"></i>
                                                    August 11, 2017
                                                </p>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <a title="#" class="media-left" href="#">
                                                <img src="./../../../front office assets/images/widget_01.jpg" alt="" />
                                            </a>
                                            <div class="media-body">
                                                <h4>
                                                    <a href="#">Franki goes to… The Philippines & Indonesia</a>
                                                </h4>
                                                <p>
                                                    <i class="color-main fa fa-calendar"></i>
                                                    August 7, 2017
                                                </p>
                                            </div>
                                        </li>

                                        <li class="media">
                                            <a title="#" class="media-left" href="#">
                                                <img src="./../../../front office assets/images/widget_03.jpg" alt="" />
                                            </a>
                                            <div class="media-body">
                                                <h4>
                                                    <a href="#">Getting More For Your Money</a>
                                                </h4>
                                                <p>
                                                    <i class="color-main fa fa-calendar"></i>
                                                    August 6, 2017
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="widget-title widget_search mt-50">
                                    <h3>Search on Website</h3>
                                    <form method="get" class="searchform"
                                        action="http://webdesign-finder.com/html/invenir-consult/">
                                        <div class="form-group">
                                            <label class="sr-only" for="widget-search">Search for:</label>
                                            <input id="widget-search" type="text" value="" name="search"
                                                class="form-control" placeholder="Search Keyword" />
                                        </div>
                                    </form>
                                </div>
                            </aside>

                            <div class="d-none d-lg-block divider-110"></div>
                        </div>
                    </div>
                </div>
            </section>

            <div id="popup-card-rank" class="popup-card">
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
        function togglePopup(jobId, userId) {

            if (jobId != '' && userId != '') {
                document.getElementById('jobId-popup').value = jobId;
                document.getElementById('userId-popup').value = userId;
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


        function check_apply_data() {
            desc = document.getElementById('apply_desc').value;
            pdf_file = document.getElementById('resume_data').value;

            descWithoutSpaces = desc.replace(/ /g, '');
            if (descWithoutSpaces == '') {
                document.getElementById('desc_error').innerHTML = 'Please enter your description';
                return false;
            } else {
                document.getElementById('desc_error').innerHTML = '';
            }

            if (pdf_file == '') {
                document.getElementById('resume_error').innerHTML = 'Please enter your resume (PDF only)';
                return false;
            } else {
                document.getElementById('resume_error').innerHTML = '';
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

                var modal = document.getElementById("popup-card-rank");
                modal.style.display = "block";
            });
        }

        var modal = document.getElementById("popup-card-rank");
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
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>