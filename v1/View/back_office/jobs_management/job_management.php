<?php

require_once __DIR__ . '/../../../Controller/JobC.php';

// Include the controller file
$jobController = new JobController();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add") {
        // Add new job

        $userProfileId = $_POST["jobs_profile"];
        $title = $_POST["job_title"];
        $company = $_POST["company"];
        $location = $_POST["location"];
        $description = $_POST["description"];
        $salary = $_POST["salary"];
        $category = $_POST["category"];
        $job_id = $jobController->generateJobId(7);
        $lng = $_POST["longitude"];
        $lat = $_POST["latitude"];

        if (!empty($_FILES['jobimage']['name'])) {

            // Get profile photo and cover data
            $job_image_tmp_name = $_FILES['jobimage']['tmp_name'];
            $job_image = file_get_contents($job_image_tmp_name);

            // Only echo the result if the job creation is successful
            $result = $jobController->createJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $userProfileId, $lng, $lat);
            if ($result !== false) {
                // Redirect to prevent form resubmission
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            }
        } else {
            echo "Please select a job image.";
        }
    } elseif ($_POST["action"] == "update") {
        // Update existing job
        $job_id = $_POST["job_id"];
        $title = $_POST["job_title"];
        $company = $_POST["company"];
        $location = $_POST["location"];
        $description = $_POST["description"];
        $salary = $_POST["salary"];
        $category = $_POST["category"];
        $lng = $_POST["update_lang"];
        $lat = $_POST["update_latd"];


        if (!empty($_FILES['job_image']['name']) && $_FILES['job_image']['error'] === 0) {

            // Get profile photo and cover data
            $job_image_tmp_name = $_FILES['job_image']['tmp_name'];
            $job_image = file_get_contents($job_image_tmp_name);

            // Only echo the result if the job update is successful
            $result = $jobController->updateJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $lat, $lng);

            if ($result !== false) {
                // Redirect to prevent form resubmission

                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            }
        } else {
            // Only echo the result if the job update is successful
            $result = $jobController->updateJobWithoutImage($job_id, $title, $company, $location, $description, $salary, $category, $lat, $lng);

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
        } else {
            echo "Error deleting job.";
        }
    }
}

// Fetch all jobs
$jobs = $jobController->getAllJobsWithCategory();
$id_category_options = $jobController->generateCategoryOptions();

$profile_id_options = $jobController->generateProfileOptions();

$block_call_back = 'false';
$access_level = "admin";
include ('./../../../View/callback.php')

    ?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HireUp Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="./../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" href="./../../../assets/css/styles.min.css" />

    <style>
        /*
        .currency-input {
            position: relative;
            display: inline-block;
        }
        
        #currencySelect {
            position: absolute;
            top: 100%;
            left: 0;
            display: none;
            min-width: 150px;
            padding: 5px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
        }
        
        #currencySelect.active {
            display: block;
        }
        */
        .logo-img {
            margin: 0 auto;
            /* Center the image horizontally */
            display: block;
            /* Ensure the link occupies full width */
            padding-top: 5%;
        }

        /* CSS for the popup form */
        .modal {
            display: none;
            /* Hide the modal by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Ensure the modal appears above other elements */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scrolling if needed */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black with opacity */
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            max-width: 80%;
            /* Set a maximum width */
        }

        /* Media query for smaller screens */
        @media only screen and (max-width: 768px) {
            .modal-content {
                max-width: 90%;
                /* Adjust maximum width for smaller screens */
            }
        }


        /* Close button style */
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

        /* Ensure the modal appears above the header */
        .app-header {
            z-index: 999;
            /* Ensure the header appears above the modal */
        }

        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
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

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

    <!-- <?php
    // $block_call_back = 'false';
    // $access_level = "admin";
    // include ('./../../../View/callback.php')
    ?> -->


    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php
        $active_page = "jobs";
        $nb_adds_for_link = 3;
        include ('../../../View/back_office/dashboard_side_bar.php')
            ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <?php include ('../../../View/back_office/header_bar.php') ?>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">



                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h1>Job Management</h1>
                            <hr> <br>
                            <h2>Add Job</h2><br>
                            <!-- Form for adding new job -->
                            <form id="addJobForm" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add">
                                <div class="mb-3">
                                    <label for="job_title" class="form-label">Job Title *</label>
                                    <input type="text" class="form-control" id="job_title" name="job_title"
                                        placeholder="Enter Job Title">
                                    <span id="job_title_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="company" class="form-label">Company *</label>
                                    <input type="text" class="form-control" id="company" name="company"
                                        placeholder="Enter company">
                                    <span id="job_company_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" id="latitude" name="latitude" value="">
                                    <input type="hidden" id="longitude" name="longitude" value="">
                                    <input type="hidden" id="place-name" name="place-name" value="Unknown place">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        placeholder="Enter location">
                                    <i class="fa-solid fa-map-location-dot" onclick="mapSelectionPopUp()"></i>
                                    <span id="job_location_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Enter description">
                                    <span id="job_desc_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>

                                <div class="currency-input mb-3">
                                    <label for="salary" class="form-label">Salary *</label>
                                    <input type="text" class="form-control" id="salary" name="salary"
                                        placeholder="Enter salary">
                                    <!--<select name="currency" id="currencySelect"></select>-->
                                    <span id="job_salary_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>

                                <div class="currency-input mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <?php echo $id_category_options; ?>
                                    </select>


                                    <span id="job_category_error" class="text-danger"></span>
                                </div>

                                <div class="currency-input mb-3">
                                    <label for="jobs_profile" class="form-label">Job Main Profile *</label>
                                    <select class="form-select" id="jobs_profile" name="jobs_profile">
                                        <option value="" selected disabled>Select Profile</option>
                                        <?php echo $profile_id_options; ?>
                                    </select>
                                    <span id="job_profile_error" class="text-danger"></span>
                                </div>

                                <!-- Profile Photo -->
                                <div class="mb-3">
                                    <label for="job_image" class="form-label">Job Image</label>
                                    <input type="file" class="form-control" id="job_image" name="jobimage"
                                        accept="image/*" />
                                </div>

                                <button type="submit" class="btn btn-primary">Add Job</button>
                            </form>
                        </div>
                    </div>

                    <!-- Popup Form for Updating Job -->
                    <div id="updateJobModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><a class="ti ti-edit" style="color: white;"></a> Update Job</h2>
                            <hr><br>
                            <form id="updateJobForm" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <div class="mb-3">
                                    <label for="update_job_id" class="form-label">Job ID *</label>
                                    <input type="text" class="form-control" id="update_job_id" name="job_id" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="update_job_title" class="form-label">Job Title *</label>
                                    <input type="text" class="form-control" id="update_job_title" name="job_title"
                                        placeholder="Enter Job Title">

                                    <span id="update_job_title_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_company" class="form-label">Company *</label>
                                    <input type="text" class="form-control" id="update_company" name="company"
                                        placeholder="Enter company">
                                    <span id="update_company_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->

                                </div>
                                <div class="mb-3">
                                    <label for="update_location" class="form-label">Location *</label>
                                    <input type="hidden" id="update_latd" name="update_latd" value="">
                                    <input type="hidden" id="update_lang" name="update_lang" value="">
                                    <input type="hidden" id="place-name" name="place-name" value="Unknown place">
                                    <input type="text" class="form-control" id="update_location" name="location"
                                        placeholder="Enter location">
                                    <i class="fa-solid fa-map-location-dot" onclick="mapSelectionPopUpUpdate()"></i>
                                    <span id="update_location_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_description" class="form-label">Description *</label>
                                    <input type="text" class="form-control" id="update_description" name="description"
                                        placeholder="Enter description">
                                    <span id="update_description_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->

                                </div>
                                <div class="mb-3">
                                    <label for="update_salary" class="form-label">Salary *</label>
                                    <input type="text" class="form-control" id="update_salary" name="salary"
                                        placeholder="Enter salary">
                                    <span id="update_salary_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="currency-input mb-3">
                                    <label for="update_category" class="form-label">Category *</label>
                                    <select class="form-select" id="update_category" name="category">
                                        <option value="" selected disabled>Select Category</option>
                                        <?php echo $id_category_options; ?>
                                    </select>
                                    <span id="update_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->

                                </div>
                                <!-- Hidden job img container -->
                                <div class="hidden-job-img-container" id="hiddenJobImageContainer"
                                    style="display: none;">
                                    <img src="#" alt="Hidden Job Image" class="hidden-job-image" id="hiddenJobImage">
                                </div>

                                <!-- job image container -->
                                <div class="job-img-container" id="update_job_image_display">
                                    <!-- Output the job img with appropriate MIME type -->
                                    <img src="#" id="update_job_img" alt="Job Image" class="img-fluid job-img-image">
                                </div><br>

                                <!-- Add input field for job img -->
                                <div class="mb-3">
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

                </div>
            </div>

            <button type="button" class="btn btn-success btn-sm me-2" id="scrollToTopBtn" style="font-size: large;"><a
                    class="ti ti-arrow-up text-white"></a></button>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 ">
                                <a class="btn btn-primary" href="./categorie.php"><i
                                        class="ti ti-pin text-white"></i>Category Management</a>
                            </div>
                            <div class="col-md-6">
                                <form class="d-flex">
                                    <input class="form-control me-2" type="search" placeholder="Search Job Title"
                                        aria-label="Search" id="searchInput">
                                    <select class="form-select" id="categoryFilter">
                                        <option value="" selected>All Categories</option>
                                        <!-- Populate dropdown menu with categories -->
                                        <?php foreach ($jobs as $category): ?>
                                            <option value="<?= $category['category_name'] ?>">
                                                <?= $category['category_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <!-- Table for displaying existing jobs -->
                        <div class="table-responsive">
                            <!-- Table for displaying existing jobs -->
                            <table class="table text-nowrap mb-0 align-middle" id="jobs-table">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Actions</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">ID</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Job Title</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Company</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Location</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Salary</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Description </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Date Posted </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">category </h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <button class="btn btn-primary btn-sm edit-btn me-2"
                                                    style="font-size: medium;" data-job-id="<?= $job['id'] ?>"
                                                    data-job-title="<?= $job['title'] ?>"
                                                    data-company="<?= $job['company'] ?>"
                                                    data-location="<?= $job['location'] ?>"
                                                    data-description="<?= $job['description'] ?>"
                                                    data-salary="<?= $job['salary'] ?>"
                                                    data-category="<?= $job['category_name'] ?>"
                                                    data-jobImg="<?php echo base64_encode($job['job_image']) ?>"
                                                    data-lang="<?php echo $job['lng'] ?>"
                                                    data-latd="<?php echo $job['lat'] ?>">
                                                    <a class="ti ti-edit text-white"></a></button>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                                    <button class="btn btn-danger btn-sm" style="font-size: medium;"
                                                        onclick="return confirm('Are you sure you want to delete this job?')"><a
                                                            class="ti ti-x text-white"></a></button>
                                                </form>
                                                <button type="buton" id="map_view_btn" class="btn btn-primary btn-sm me-2 m-2"
                                                    style="font-size: medium;" data-job-id="<?= $job['id'] ?>"
                                                    data-location="<?= $job['location'] ?>"
                                                    data-lang="<?php echo $job['lng'] ?>"
                                                    data-latd="<?php echo $job['lat'] ?>"
                                                    onclick="mapSelectionPopUpViewOnly()">
                                                    <a class="fa-solid fa-map-location-dot text-white"></a>
                                                </button>
                                            </td>
                                            <td><?= $job['id'] ?></td>
                                            <td><?= $job['title'] ?></td>
                                            <td><?= $job['company'] ?></td>
                                            <td><?= $job['location'] ?></td>
                                            <td><?= $job['salary'] ?></td>
                                            <td><?= $job['description'] ?></td>
                                            <td><?= $job['date_posted'] ?></td>
                                            <td><?= $job['category_name'] ?></td>
                                            <td><?= $job['jobs_profile'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="popup-card-map" class="popup-card">
        <div class="popup-content">
            <span id="close-popup-map" class="close">&times;</span>
            <h3 id="popup-Name" class="text-capitalize">Map</h3>
            <iframe id="face_detection_iframe" src="./../../front_office/map/map_interective.php"></iframe>
        </div>
    </div>



    <script src="./../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../assets/js/sidebarmenu.js"></script>
    <script src="./../../../assets/js/app.min.js"></script>
    <script src="./../../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="./../finition.js"></script>

    <script>
        // Get the category filter dropdown
        var categoryFilter = document.getElementById("categoryFilter");

        // Add event listener to detect filter selections
        categoryFilter.addEventListener("change", filterJobsByCategory);

        function filterJobsByCategory() {
            var category, table, tr, td, i, txtValue;
            category = categoryFilter.value.toUpperCase();
            table = document.getElementById("jobs-table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those that don't match the category filter
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[7]; // Change the index if the column for category is different
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(category) > -1 || category === 'ALL CATEGORIES') {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        console.log('fin');
    </script>


    <script>
        // Get the input field
        var input = document.getElementById("searchInput");

        // Add an event listener to detect input changes
        input.addEventListener("input", function () {
            var filter, table, tr, td, i, txtValue;
            filter = input.value.toUpperCase();
            table = document.getElementById("jobs-table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change the index if the column for job title is different
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
    <!-- pop up JS -->
    <script>
        // Get the modal
        var modal = document.getElementById("updateJobModal");

        // Get the button that opens the modal
        var editButtons = document.querySelectorAll(".edit-btn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // JavaScript to handle edit button click event
        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    // Get job details from data attributes
                    const id = this.getAttribute("data-job-id");
                    const title = this.getAttribute("data-job-title");
                    const company = this.getAttribute("data-company");
                    const location = this.getAttribute("data-location");
                    const description = this.getAttribute("data-description");
                    const salary = this.getAttribute("data-salary");
                    const category = this.getAttribute("data-category");
                    const job_image = this.getAttribute("data-jobImg");
                    const lang = this.getAttribute("data-lang");
                    const latd = this.getAttribute("data-latd");
                    // Populate update form inputs with job details
                    console.log(category);
                    console.log("hello");
                    populateUpdateForm(id, title, company, location, description, salary, category, job_image, lang, latd);
                    // Show the update form modal
                    document.getElementById("updateModal").style.display = "block";
                });
            });
        });



        // Function to populate the update form with job details
        function populateUpdateForm(id, title, company, location, description, salary, category, job_image, lang, latd) {
            console.log(category);
            console.log("hiiiiiii");

            document.getElementById("update_job_id").value = id;
            document.getElementById("update_job_title").value = title;
            document.getElementById("update_company").value = company;
            document.getElementById("update_location").value = location;
            document.getElementById("update_description").value = description;
            document.getElementById("update_salary").value = salary;
            document.getElementById("update_category").value = category;
            // console.log("data:image/jpeg;base64," + job_image);
            document.getElementById("update_job_img").src = "data:image/jpeg;base64," + job_image;
            document.getElementById("update_lang").value = lang;
            document.getElementById("update_latd").value = latd;
        }

        // When the user clicks on the edit button, open the modal
        editButtons.forEach(function (button) {
            button.onclick = function () {
                modal.style.display = "block";
                modal.style.display = "flex";
                // Populate form fields with job details here using JavaScript
            };
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };

        // When the user clicks on cancel button, close the modal
        document.querySelector(".cancel-btn").onclick = function () {
            modal.style.display = "none";
            document.getElementById("updateJobForm").reset();
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
    <!-- add JS -->

    <script>
        document.getElementById("addJobForm").addEventListener("submit", function (event) {
            // Reset previous error messages
            document.getElementById("job_title_error").textContent = ""; // Reset error message for job title
            document.getElementById("job_company_error").textContent = ""; // Reset error message for company
            document.getElementById("job_location_error").textContent = ""; // Reset error message for location
            document.getElementById("job_desc_error").textContent = ""; // Reset error message for description
            document.getElementById("job_salary_error").textContent = ""; // Reset error message for salary
            document.getElementById("job_category_error").textContent = ""; // Reset error message for salary
            document.getElementById("job_profile_error").textContent = ""; // Reset error message for job_profile

            // Get input values
            var jobTitle = document.getElementById("job_title").value.trim();
            var company = document.getElementById("company").value.trim();
            var location = document.getElementById("location").value.trim();
            var description = document.getElementById("description").value.trim();
            var salary = document.getElementById("salary").value.trim();
            var category = document.getElementById("category").value.trim();
            var profile = document.getElementById("jobs_profile").value.trim();
            var lang = document.getElementById("longitude").value.trim();
            var latd = document.getElementById("latitude").value.trim();
            // Variable to store the common error message
            var errorMessage = "";



            // Validate job title (characters only)
            if (!/^[a-zA-Z\s]+$/.test(jobTitle)) {
                errorMessage = "Job title must contain only characters."; // Set common error message
                displayError("job_title_error", errorMessage, true); // Display error message
            }

            // Check if salary is not empty and contains only numbers
            if (!/^\d+(\.\d+)?$/.test(salary)) {
                errorMessage = "Salary must be a number."; // Set common error message
                displayError("job_salary_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (jobId === "") {
                errorMessage = "Job ID is required."; // Set common error message
                displayError("job_id_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (jobTitle === "") {
                errorMessage = "Job title is required."; // Set common error message
                displayError("job_title_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (company === "") {
                errorMessage = "Company is required."; // Set common error message
                displayError("job_company_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (location === "") {
                errorMessage = "Location is required."; // Set common error message
                displayError("job_location_error", errorMessage, true); // Display error message
            }

            if (latd === "" || lang === "") {
                errorMessage = "Please selecte your location on the map."; // Set common error message
                displayError("job_location_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (description === "") {
                errorMessage = "Description is required."; // Set common error message
                displayError("job_desc_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (salary === "") {
                errorMessage = "Salary is required."; // Set common error message
                displayError("job_salary_error", errorMessage, true); // Display error message
            }

            // If there are no errors, submit the form
            if (errorMessage === "") {
                this.submit(); // Submit the form
            }
        });



        // Listen for input event on job title field
        document.getElementById("job_title").addEventListener("input", function (event) {
            var jobTitle = this.value.trim(); // Get value of job title field

            // Validate job title format (characters only)
            if (jobTitle === "") {
                displayError("job_title_error", "Title is required.", true); // Display error message for empty job title
            } else if (/^[a-zA-Z\s]+$/.test(jobTitle)) {
                displayError("job_title_error", "Valid Job Title", false); // Display valid message for job title
            } else {
                displayError("job_title_error", "Job title must contain only characters.", true); // Display error message for invalid job title
            }
        });

        // Listen for input event on job salary field
        document.getElementById("salary").addEventListener("input", function (event) {
            var jobSalary = this.value.trim(); // Get value of job salary field

            // Validate if salary is empty
            if (jobSalary === "") {
                displayError("job_salary_error", "Salary is required.", true); // Display error message for empty salary
            } else if (/^\d+(\.\d+)?$/.test(jobSalary)) {
                displayError("job_salary_error", "Valid Job Salary", false); // Display valid message for salary
            } else {
                displayError("job_salary_error", "Salary must be a number.", true); // Display error message for invalid salary format
            }
        });

        // Listen for input event on company field
        document.getElementById("company").addEventListener("input", function (event) {
            var company = this.value.trim(); // Get value of company field

            // Validate if company is empty
            if (company === "") {
                displayError("job_company_error", "Company is required.", true); // Display error message for empty company
            } else {
                displayError("job_company_error", "Valid company", false); // Display valid message for company
            }
        });

        // Listen for input event on location field
        document.getElementById("location").addEventListener("input", function (event) {
            var location = this.value.trim(); // Get value of location field
            var lang = document.getElementById("longitude").value.trim();
            var latd = document.getElementById("latitude").value.trim();

            // Validate if location is empty
            if (location === "" || lang === "" || latd === "") {
                if (location === "") {
                    displayError("job_location_error", "Location is required.", true); // Display error message for empty location
                } else {
                    displayError("job_location_error", "Please selecte your location on the map.", true); // Display error message for empty location
                }
            } else {
                displayError("job_location_error", "Valid location", false); // Display valid message for location
            }

        });

        // Listen for input event on description field
        document.getElementById("description").addEventListener("input", function (event) {
            var description = this.value.trim(); // Get value of description field

            // Validate if description is empty
            if (description === "") {
                displayError("job_desc_error", "Description is required.", true); // Display error message for empty description
            } else {
                displayError("job_desc_error", "Valid description", false); // Display valid message for description
            }
        });

        // Listen for input event on category field
        document.getElementById("category").addEventListener("change", function (event) {
            var category = this.value.trim(); // Get value of category field

            // Validate if category is selected
            if (category === "") {
                displayError("job_category_error", "Category is required.", true); // Display error message for empty category
            } else {
                displayError("job_category_error", "Valid category", false); // Display valid message for category
            }
        });


        // Listen for input event on Profile field
        document.getElementById("jobs_profile").addEventListener("change", function (event) {
            var profile = this.value.trim(); // Get value of category field

            // Validate if job_profile is selected
            if (profile === "") {
                displayError("job_profile_error", "Profile is required.", true); // Display error message for empty Profile
            } else {
                displayError("job_profile_error", "Valid Profile", false); // Display valid message for Profile
            }
        });


        // Listen for input event on each input field and category field
        var inputs = document.querySelectorAll("#addJobForm input, #category,#description");
        inputs.forEach(function (input) {
            input.addEventListener("input", function () {
                toggleSubmitButton();
            });
        });




        // Function to display error message
        function displayError(elementId, errorMessage, isError) {
            var errorElement = document.getElementById(elementId);
            errorElement.textContent = errorMessage;
            errorElement.classList.toggle("text-danger", isError);
            errorElement.classList.toggle("text-success", !isError);
        }


        document.addEventListener("DOMContentLoaded", function () {
            // Function to check if all input fields are populated
            function checkInputFields() {
                var inputs = document.querySelectorAll("#addJobForm input, #category, #description? #jobs_profile");
                var allPopulated = true;
                inputs.forEach(function (input) {
                    if (input.tagName.toLowerCase() === "input" && input.value.trim() === "") {
                        allPopulated = false;
                    }
                    if (input.tagName.toLowerCase() === "select" && input.value === "") {
                        allPopulated = false;
                    }
                });
                return allPopulated;
            }

            // Function to enable/disable submit button based on input fields
            function toggleSubmitButton() {
                var submitButton = document.querySelector("#addJobForm button[type='submit']");
                submitButton.disabled = !checkInputFields();
            }

            // Listen for input event on each input field
            var inputs = document.querySelectorAll("#addJobForm input, #category, #description, #jobs_profile");
            inputs.forEach(function (input) {
                input.addEventListener("input", function () {
                    toggleSubmitButton();
                });
            });

            // Initial call to toggleSubmitButton to set initial state
            toggleSubmitButton();
        });
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
            document.getElementById("update_category_error").textContent = ""; // Reset error message for salary
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

        // Listen for input event on category field
        document.getElementById("update_category").addEventListener("change", function (event) {
            var category = this.value.trim(); // Get value of category field

            // Validate if category is selected
            if (category === "") {
                displayError("update_category_error", "Category is required.", true); // Display error message for empty category
            } else {
                displayError("update_category_error", "Valid category", false); // Display valid message for category
            }
        });


        // Listen for input event on each input field and category field
        var inputs = document.querySelectorAll("#updateJobForm input, #update_category ,#update_description");
        inputs.forEach(function (input) {
            input.addEventListener("input", function () {
                toggleSubmitButton();
            });
        });

        // Function to display error message
        function displayError(elementId, errorMessage, isError) {
            var errorElement = document.getElementById(elementId);
            errorElement.textContent = errorMessage;
            errorElement.classList.toggle("text-danger", isError);
            errorElement.classList.toggle("text-success", !isError);
        }
    </script>

    <!-- scroll to the top -->
    <script>
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");

        function handleScroll() {
            if (window.scrollY > 200) {
                // Adjust this value as needed
                scrollToTopBtn.style.display = "block";
                scrollToTopBtn.style.opacity = "1";
            } else {
                scrollToTopBtn.style.opacity = "0";
                setTimeout(() => {
                    scrollToTopBtn.style.display = "none";
                }, 300);
            }
        }

        function scrollToTop() {
            var currentPosition = window.pageYOffset;
            var targetPosition = 0;
            var animationInterval = 5;
            var scrollStep = currentPosition > targetPosition ? -50 : 50; // Adjust the step size as needed

            var scrollInterval = setInterval(function () {
                if (currentPosition === targetPosition) {
                    clearInterval(scrollInterval);
                } else {
                    currentPosition += scrollStep;
                    if (Math.abs(currentPosition - targetPosition) < Math.abs(scrollStep)) {
                        currentPosition = targetPosition;
                    }
                    window.scrollTo(0, currentPosition);
                }
            }, animationInterval);
        }

        window.addEventListener("scroll", handleScroll);
        scrollToTopBtn.addEventListener("click", scrollToTop);
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
            map_iframe.src = `./../../front_office/map/map_interective.php`;
        }

        function mapSelectionPopUpUpdate() {
            console.log("Map selection popup opened");
            lng = document.getElementById("update_lang").value;
            lat = document.getElementById("update_latd").value;
            place = document.getElementById("update_location").value;
            var modal = document.getElementById("popup-card-map");
            var map_iframe = document.getElementById("face_detection_iframe");
            modal.style.display = "block";
            map_iframe.src = `./../../front_office/map/map_interective_special_case_update.php?lng=${lng}&lat=${lat}&place=${place}`;
        }

        function mapSelectionPopUpViewOnly() {
            console.log("Map selection popup opened");
            var btn = document.getElementById("map_view_btn");
            var place = btn.getAttribute("data-location");
            var lng = btn.getAttribute("data-lang");
            var lat = btn.getAttribute("data-latd");
            var modal = document.getElementById("popup-card-map");
            var map_iframe = document.getElementById("face_detection_iframe");
            modal.style.display = "block";
            map_iframe.src = `./../../front_office/map/map_static.php?lng=${lng}&lat=${lat}&place=${place}`;
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

                    var location = document.getElementById('location').value.trim(); // Get value of location field
                    var mapLat = document.getElementById("latitude").value.trim();
                    var mapLng = document.getElementById("longitude").value.trim();

                    // Validate if location is empty
                    if (location === "" || mapLat == "" || mapLng == "") {
                        if (location === "") {
                            displayError("job_location_error", "Location is required.", true); // Display error message for empty location
                        } else {
                            displayError("job_location_error", "Please selecte your location on the map.", true); // Display error message for empty map selection
                        }
                    } else {
                        displayError("job_location_error", "Valid location", false); // Display valid message for location
                    };




                }
            
                if (event.data.message == "the update location is :") {
                    // Parse JSON data received from the iframe

                    // Access properties of the JSON object
                    //console.log('Message:', jsonData.message);
                    //console.log('Data:', jsonData.data);
                    document.getElementById('update_latd').value = event.data.data.lat;
                    document.getElementById('update_lang').value = event.data.data.lng;

                    // Listen for input event on location field

                    var location = document.getElementById('location').value.trim(); // Get value of location field



                }
            }
        }
    </script>

    <!-- voice recognation -->
    <script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>


</body>

</html>