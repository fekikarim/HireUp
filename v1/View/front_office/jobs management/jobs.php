<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


// Create an instance of JobController
$jobController = new JobController();
$profileController = new ProfileC();


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

        header("Location: ./myJobs_list.php");
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
$jobs = $jobController->getAllJobsSortedByProfileEducation($userId);

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

  <?php
  // $block_call_back = 'false';
  // $access_level = "else";
  // include ('./../../../View/callback.php');
  ?>


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


      <section class="page_title cs s-py-25" style="background-color: #321E1E !important;">
        <div class="divider-100" style="margin-bottom: 150px;"></div>

      </section>

      <section class="page_title cs s-py-25" style="background-color: #321E1E !important;">
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


      <section class="ls s-py-50 s-py-50">
        <div class="container">
          <div class="d-none d-lg-block divider-110"></div>

          <div class="container mb-5">
            <div class="row">
              <div class="col-md-12">
                <h2>Add New Job</h2>
                <form id="createJobForm" method="post" action="addJob.php" enctype="multipart/form-data">
                  <!-- Input fields for job details -->

                  <input type="hidden" value="<?php echo $userId ?>" name="jobs_profile" id="jobs_profile">

                  <div class="form-group">
                    <label for="job_title">Job Title</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="job_title" name="job_title">
                      <!-- <i class="fa fa-microphone voice-icon" onclick="startSpeechRecognition('job_title')"></i> -->
                    </div>
                    <span id="job_title_error" class="text-danger"></span> <!-- Error message placeholder -->
                  </div>

                  <div class="form-group">
                    <label for="company">Company</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="company" name="company">
                      <!-- <i class="fa fa-microphone voice-icon" onclick="startSpeechRecognition('company')"></i> -->
                    </div>
                    <span id="job_company_error" class="text-danger"></span> <!-- Error message placeholder -->
                  </div>

                  <div class="form-group">
                    <label for="location">Location</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="location" name="location">
                      <i class="fa-solid fa-map-location-dot" onclick="mapSelectionPopUp()"></i>
                      <!-- Hidden inputs to store longitude and latitude -->
                      <input type="hidden" id="latitude" name="latitude" value="">
                      <input type="hidden" id="longitude" name="longitude" value="">
                      <input type="hidden" id="place-name" name="place-name" value="Unknown place">
                    </div>
                    <span id="job_location_error" class="text-danger"></span> <!-- Error message placeholder -->
                  </div>

                  <div class="form-group">
                    <label for="description">Description</label>
                    <div class="input-group">
                      <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                      <!-- <i class="fa fa-microphone voice-icon" onclick="startSpeechRecognition('description')"></i> -->
                    </div>
                    <span id="job_desc_error" class="text-danger"></span> <!-- Error message placeholder -->
                  </div>

                  <div class="form-group">
                    <label for="salary">Salary</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="salary" name="salary">
                      <!-- <i class="fa fa-microphone voice-icon" onclick="startSpeechRecognition('salary')"></i> -->
                    </div>
                    <span id="job_salary_error" class="text-danger"></span> <!-- Error message placeholder -->
                  </div>

                  <div class="form-group">
                    <label for="category" class="form-label">Category *</label>
                    <div class="input-group">
                      <select class="form-select" id="category" name="category" required>
                        <option value="" selected disabled>Select Category</option>
                        <?php echo $id_category_options; ?>
                      </select>

                    </div>
                    <span id="job_category_error" class="text-danger"></span>
                  </div>

                  <div class="form-group">
                    <label for="job_image" class="custom-file-label"
                      style="background-color: #F2F2F2; color: black;"><b>Job Image</b></label>
                    <input type="file" class="custom-file-input button" id="job_image" name="job_image"
                      accept="image/*" />
                  </div>

                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>


        </div>
      </section>

      <div id="popup-card" class="popup-card">
        <div class="popup-content">
          <span id="close-popup" class="close">&times;</span>
          <h3 id="popup-Name" class="text-capitalize">Map</h3>
          <iframe id="face_detection_iframe" src="./../map/map_interective.php"></iframe>
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
  <script src="./../../../front office assets/js/chatbot.js"></script>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

  <script>
    function startSpeechRecognition(inputId) {
      if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.interimResults = true;

        recognition.addEventListener('result', e => {
          const transcript = Array.from(e.results)
            .map(result => result[0])
            .map(result => result.transcript)
            .join('');

          document.getElementById(inputId).value = transcript;
        });

        recognition.start();
      } else {
        alert("Speech recognition not supported in this browser.");
      }
    }
  </script>

  <script>
    function openFullScreenImage(imageData) {
      var imageSrc = "data:image/jpeg;base64," + imageData;
      document.getElementById("fullScreenImage").src = imageSrc;
      $('#jobImageModal').modal('show');
    }
  </script>
  <!-- add JS -->

  <script>
    document.getElementById("createJobForm").addEventListener("submit", function (event) {
      // Reset previous error messages
      document.getElementById("job_title_error").textContent = ""; // Reset error message for job title
      document.getElementById("job_company_error").textContent = ""; // Reset error message for company
      document.getElementById("job_location_error").textContent = ""; // Reset error message for location
      document.getElementById("job_desc_error").textContent = ""; // Reset error message for description
      document.getElementById("job_salary_error").textContent = ""; // Reset error message for salary
      document.getElementById("job_category_error").textContent = "";
      // Get input values
      var jobTitle = document.getElementById("job_title").value.trim();
      var company = document.getElementById("company").value.trim();
      var location = document.getElementById("location").value.trim();
      var description = document.getElementById("description").value.trim();
      var salary = document.getElementById("salary").value.trim();
      var category = document.getElementById("category").value.trim();
      var mapLng = document.getElementById("longitude").value.trim();
      var mapLat = document.getElementById("latitude").value.trim();
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

      if (mapLat == "" || mapLng == "") {
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
      if (category === "") {
        errorMessage = "Category is required."; // Set common error message
        displayError("job_category_error", errorMessage, true); // Display error message
      }

      // Prevent form submission if there's an error message
      if (errorMessage !== "") {
        event.preventDefault();
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

    document.getElementById("category").addEventListener("input", function (event) {
      var category = this.value.trim(); // Get value of description field
      var categoryError = document.getElementById("job_category_error"); // Get error message element

      // Validate if description is empty
      if (category === "") {
        displayError("job_category_error", "category is required.", true); // Display error message for empty description
      } else {
        displayError("job_category_error", "Valid category", false); // Display valid message for description
      }
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
        var inputs = document.querySelectorAll("#createJobForm input ,#description ,#category");
        var allPopulated = true;
        inputs.forEach(function (input) {
          if (input.value.trim() === "") {
            //console.log(input);
            allPopulated = false;
          }
        });
        //console.log("===================================================================================================");
        return allPopulated;
      }

      // Function to enable/disable submit button based on input fields
      function toggleSubmitButton() {
        var submitButton = document.querySelector("#createJobForm button[type='submit']");
        submitButton.disabled = !checkInputFields();
      }

      // Listen for input event on each input field
      var inputs = document.querySelectorAll("#createJobForm input ,#description ,#category");
      inputs.forEach(function (input) {
        input.addEventListener("input", function () {
          toggleSubmitButton();
        });
      });

      // Initial call to toggleSubmitButton to set initial state
      toggleSubmitButton();
    });
  </script>

  <!-- Map Selection Popup Modal -->
  <script>
    function mapSelectionPopUp() {
      var modal = document.getElementById("popup-card");
      modal.style.display = "block";
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

          function checkInputFields() {
            var inputs = document.querySelectorAll("#createJobForm input ,#description ,#category");
            var allPopulated = true;
            inputs.forEach(function (input) {
              if (input.value.trim() === "") {
                console.log(input);
                allPopulated = false;
              }
            });
            console.log("===================================================================================================");
            return allPopulated;
          }

          // Function to enable/disable submit button based on input fields
          function toggleSubmitButton() {
            var submitButton = document.querySelector("#createJobForm button[type='submit']");
            submitButton.disabled = !checkInputFields();
          }

          toggleSubmitButton();


        }
      }
    }
  </script>

  <!-- voice recognation -->
  <script type="text/javascript"
    src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


</body>

</html>