<?php
require_once __DIR__ . '/../../../Controller/profileController.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "add") {
  // Retrieve the profile information from the form
  $first_name = isset($_POST["profile_first_name"]) ? $_POST["profile_first_name"] : "";
  $family_name = isset($_POST["profile_family_name"]) ? $_POST["profile_family_name"] : "";
  $phone_number = isset($_POST["profile_phone_number"]) ? $_POST["profile_phone_number"] : "";
  $region = isset($_POST["profile_region"]) ? $_POST["profile_region"] : "";
  $city = isset($_POST["profile_city"]) ? $_POST["profile_city"] : "";
  $bio = "";
  $current_position = isset($_POST["profile_current_position"]) ? $_POST["profile_current_position"] : "";
  $education = isset($_POST["profile_education"]) ? $_POST["profile_education"] : "";
  $subscription = "";
  $auth = "";
  $acc_verif = "";
  $bday = isset($_POST["profile_bday"]) ? $_POST["profile_bday"] : "";
  $gender = isset($_POST["profile_gender"]) ? $_POST["profile_gender"] : "";
  $profile_photo_data = "";
  $profile_cover_data = "";

  // Set default profile photo based on gender
  if ($gender == "Male") {
    $profile_photo_path = "../../../front office assets/img/default profile pics/male.jpg";
  } elseif ($gender == "Female") {
    $profile_photo_path = "../../../front office assets/img/default profile pics/female.jpg";
  } else {
    // Default profile photo if gender is not specified or invalid
    $profile_photo_path = "../../../front office assets/img/default profile pics/default.jpg";
  }

  // Set default cover image
  $profile_cover_path = "../../../front office assets/img/default cover pics/default_cover.png";

  // Read and encode profile photo
  $profile_photo_data = file_get_contents($profile_photo_path);

  // Read and encode cover image
  $profile_cover_data = file_get_contents($profile_cover_path);


  // Create an instance of the controller
  $profileController = new ProfileC();

  // Generate profile ID
  $profile_id = $profileController->generateProfileId(6); // 6 is the length of the profile ID
  //$userid = $profileController->generateProfileUserId(6); // 6 is the length of the profile ID

  if (isset($_SESSION['user id'])) {
    //MARK: important cz it checks if the user_id is set or not
    $userid = htmlspecialchars($_SESSION['user id']);

    // Add profile
    $result = $profileController->addProfile($profile_id, $first_name, $family_name, $userid, $phone_number, $region, $city, $bio, $current_position, $education, $subscription, $auth, $acc_verif, $bday, $gender, $profile_photo_data, $profile_cover_data);

  } else {
    $result = false;
  }

  if ($result !== false) {
    // Redirect to profile page with profile ID as query parameter
    header('Location: profile.php?profile_id=' . $profile_id);
    exit();
  }
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile Register</title>
  <link rel="shortcut icon" type="image/png" href="../../../assets/images/logos/HireUp_icon.ico" />
  <link rel="stylesheet" href="../../../assets/css/styles.min.css" />

  <!-- <link rel="stylesheet" href="./../../../assets/css/phone.css"> -->
  <link rel="stylesheet" href="./../../back_office/profiles_management/css/phone.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css"
    integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />

  <!-- Add the intlTelInput CSS -->
  <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

  <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />


  <style>
    .logo-img {
      margin: 0 auto;
      /* Center the image horizontally */
      display: block;
      /* Ensure the link occupies full width */
    }

    /* Style for input fields with validation errors */
    .has-error input,
    .has-error select,
    .has-error textarea {
      border-color: #ff0000;
      /* Red border color */
    }

    /* Style for error messages */
    .error-message {
      color: #ff0000;
      /* Red text color */
      font-size: 12px;
      margin-top: 4px;
    }

    .input_field {
      position: relative;
    }

    .input_field>span {
      position: absolute;
      bottom: 8.5px;
      right: 8.5px;
      color: red;
      pointer-events: none;
      /* Ensure the span doesn't interfere with input events */
    }
  </style>

</head>

<body>
  <?php
  $block_call_back = 'true';
  $access_level = "else";
  $special_case = 'profile_creation';
  include ('./../../../View/callback.php')
    ?>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-4">
            <div class="card mb-0">
              <a title="#" href="../../index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="../../../assets/images/logos/HireUp_lightMode.png" alt="" width="175" height="73">
              </a>
              <form id="profileForm" method="post">
                <div class="card-body" id="step1">
                  <p class="text-center"><b>Step 1 of 2</b>: Personal Information</p>
                  <input type="hidden" name="action" value="add">
                  <div class="mb-3">
                    <label for="profile_first_name" class="form-label">First Name *</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validateFName()" class="form-control" id="profile_first_name"
                        name="profile_first_name" placeholder="Enter first name" required />
                      <span id="fname_error"></span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="profile_family_name" class="form-label">Family Name *</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validateLName()" class="form-control" id="profile_family_name"
                        name="profile_family_name" placeholder="Enter family name" required />
                      <span id="lname_error"></span>
                    </div>
                  </div>
                  <!-- Profile Information -->
                  <!-- <div class="mb-3">
                    <div class="select-box">
                      <label for="phoneNumber" class="form-label">Phone Number</label>
                      <div class="selected-option">
                        <div>
                          <span class="iconify" data-icon="flag:tn-4x3"></span>
                          <strong>+216</strong>
                        </div>
                        <input type="tel" class="form-control position-relative" id="profile_phone_number" name="profile_phone_number" value="<?php //echo isset($profile['profile_phone_number']) ? $profile['profile_phone_number'] : ''; ?>" />
                      </div>
                      <div class="options">
                        <input type="text" id="search-box" name="search-box" class="form-control search-box" placeholder="Search Country Name">
                        <ol></ol>
                      </div>
                    </div>
                    <span id="phone_error" style="position: absolute;
                                                  bottom: 296px;
                                                  right: 41px;
                                                  color: red;                                                           
                                                  pointer-events: none;">sss</span>
                  </div> -->
                  <div class="mb-3">
                    <div class="select-box">
                      <label for="phoneNumber" class="form-label">Phone Number</label>
                      <div class="selected-option">
                        <div>
                          <span class="iconify" data-icon="flag:tn-4x3"></span>
                          <strong>+216</strong>
                        </div>
                        <input type="tel" class="form-control position-relative" id="profile_phone_number"
                          name="profile_phone_number"
                          value="<?php echo isset($profile['profile_phone_number']) ? $profile['profile_phone_number'] : ''; ?>" />
                      </div>
                      <div class="options">
                        <input type="text" id="search-box" name="search-box" class="form-control search-box"
                          placeholder="Search Country Name">
                        <ol></ol>
                      </div>
                    </div>
                    <span id="phone_error" style="position: absolute;
                                                  bottom: 296px;
                                                  right: 41px;
                                                  color: red;                                                           
                                                  pointer-events: none;"></span>
                  </div>

                  <div class="mb-3">
                    <label for="profile_bday" class="form-label">Birthday</label>
                    <div class="position-relative">
                      <input type="date" onchange="validateBDay()" class="form-control" id="profile_bday"
                        name="profile_bday" />
                      <span id="bday_error" style="position: absolute;
                                                    bottom: 9px;
                                                    right: 40px;
                                                    color: red;                                                           
                                                    pointer-events: none;"><b class="text-primary">We only hire up to
                          18yo</b></span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="profile_gender" class="form-label">Gender</label>
                    <div class="position-relative">
                      <select class="form-select" onchange="validateGender()" id="profile_gender" name="profile_gender">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                      <span id="gender_error" style="position: absolute;
                                                    bottom: 8.5px;
                                                    right: 35px;
                                                    color: red;
                                                    pointer-events: none;"></span>
                    </div>
                  </div>
                  <button onclick="validateStep1(); return false;"
                    class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Continue</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <a class="text-primary fw-bold ms-2" href="./../Sign In & Sign Up/logout.php">Logout?</a>
                  </div>
                </div>
                <div class="card-body d-none" id="step2">
                  <p class="text-center"><b>Step 2 of 2</b>: Additional Information</p>
                  <input type="hidden" name="action" value="add">
                  <div class="mb-3">
                    <label for="profile_region" class="form-label">Country/Region</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validateRegion()" class="form-control" id="profile_region"
                        name="profile_region" placeholder="Enter country/region" />
                      <span id="region_error"></span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="profile_city" class="form-label">City</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validateCity()" class="form-control" id="profile_city"
                        name="profile_city" placeholder="Enter city" />
                      <span id="city_error"></span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="profile_current_position" class="form-label">Current Position</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validatePos()" class="form-control" id="profile_current_position"
                        name="profile_current_position" placeholder="Enter current position" />
                      <span id="pos_error"></span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="profile_education" class="form-label">Education</label>
                    <div class="input_field">
                      <input type="text" onkeyup="validateEdu()" class="form-control" id="profile_education"
                        name="profile_education" placeholder="Enter education" />
                      <span id="edu_error"></span>
                    </div>
                  </div>

                  <button onclick="return validateForm()" id="submit_button"
                    class="btn btn-primary w-100 py-2 fs-4 mb-4 rounded-2">Confirm</button>
                  <span id="submit_error"></span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src=".../../../assets/js/phone.js"></script> -->
  <script src="./../../back_office/profiles_management/js/phone.js"></script>

  <script>


    // Function to enforce the format of phone number input
    function formatPhoneNumber(event) {
      // Get the input value and remove any non-digit characters
      var input = event.target;
      var phoneNumber = input.value.replace(/\D/g, '');

      // Format the phone number with the desired structure
      var formattedNumber = phoneNumber.match(/^(\d{3})(\d{2})(\d{3})$/);
      if (formattedNumber) {
        input.value = formattedNumber[1] + ' ' + formattedNumber[2] + ' ' + formattedNumber[3];
      }
    }

    // Add event listener to enforce the phone number format
    var phoneNumberInput = document.getElementById('profile_phone_number');
    phoneNumberInput.addEventListener('input', formatPhoneNumber);
  </script>


  <!-- input control part -->
  <script>
    var fnameError = document.getElementById("fname_error");
    var lnameError = document.getElementById("lname_error");
    var posError = document.getElementById("pos_error");
    var eduError = document.getElementById("edu_error");
    var regionError = document.getElementById("region_error");
    var cityError = document.getElementById("city_error");
    var genderError = document.getElementById("gender_error");
    var bdayError = document.getElementById("bday_error");

    var submitError = document.getElementById("submit_error");

    function validateFName() {
      var fname = document.getElementById("profile_first_name").value;

      if (fname.length == 0) {
        fnameError.innerHTML = "First Name is required.";
        return false;
      }
      if (fname.length < 2) {
        fnameError.innerHTML = "First Name must be at least 2 characters.";
        return false;
      }
      if (!/^[a-zA-Z ]+$/.test(fname)) {
        fnameError.innerHTML = "First Name contain only alphabets.";
        return false;
      }
      fnameError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateLName() {
      var lname = document.getElementById("profile_family_name").value;

      if (lname.length == 0) {
        lnameError.innerHTML = "Family Name is required.";
        return false;
      }
      if (lname.length < 2) {
        lnameError.innerHTML = "Family Name must be at least 2 characters.";
        return false;
      }
      if (!/^[a-zA-Z ]+$/.test(lname)) {
        lnameError.innerHTML = "Family Name contain only alphabets.";
        return false;
      }
      lnameError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validatePos() {
      var pos = document.getElementById("profile_current_position").value;

      if (pos.length == 0) {
        posError.innerHTML = "Current Position is required.";
        return false;
      }
      if (pos.length < 2) {
        posError.innerHTML = "Current Position must be at least 2 characters.";
        return false;
      }

      posError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateEdu() {
      var edu = document.getElementById("profile_education").value;

      if (edu.length == 0) {
        eduError.innerHTML = "Education is required.";
        return false;
      }
      if (edu.length < 2) {
        eduError.innerHTML = "Education must be at least 2 characters.";
        return false;
      }
      eduError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateRegion() {
      var region = document.getElementById("profile_region").value;

      if (region.length == 0) {
        regionError.innerHTML = "Region is required.";
        return false;
      }
      if (region.length < 2) {
        regionError.innerHTML = "Region must be at least 2 characters.";
        return false;
      }
      if (!/^[a-zA-Z ]+$/.test(region)) {
        regionError.innerHTML = "Region contain only alphabets.";
        return false;
      }
      regionError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateCity() {
      var city = document.getElementById("profile_city").value;

      if (city.length == 0) {
        cityError.innerHTML = "City is required.";
        return false;
      }
      if (city.length < 2) {
        cityError.innerHTML = "City must be at least 2 characters.";
        return false;
      }
      cityError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateBDay() {
      var bday = document.getElementById("profile_bday").value;

      // Check if a date is selected
      if (!bday || bday === 'mm/dd/yyyy') {
        bdayError.innerHTML = "Please select a Date of Birth.";
        return false;
      }

      // Validate the date format
      var dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      if (!dateRegex.test(bday)) {
        bdayError.innerHTML =
          "Invalid date format. (mm/dd/yyyy).";
        return false;
      }

      // Validate the date values
      var selectedDate = new Date(bday);
      var currentDate = new Date();
      var minDate = new Date("1900-01-01"); // Minimum allowed date
      var maxDate = new Date(currentDate.getFullYear() - 18, currentDate.getMonth(), currentDate.getDate()); // 18 years ago from the current date

      if (selectedDate < minDate || selectedDate > maxDate) {
        bdayError.innerHTML =
          "Please enter a date +18yo.";
        return false;
      }

      bdayError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateGender() {
      var gender = document.getElementById('profile_gender').value;

      // Check if gender is selected and not equal to "Select Gender"
      if (!gender || gender === "Select Gender") {
        genderError.innerHTML = 'Please select a gender.';
        return false;
      }

      genderError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
      return true;
    }

    function validateForm() {
      if (
        !validatePos() ||
        !validateEdu() ||
        !validateRegion() ||
        !validateCity()
      ) {
        submitError.innerHTML = "Please fix errors to submit.";
        console.log("Form is not valid.");
        return false;
      }
      else {
        console.log("Form is valid.");
      }
    }

    function validateStep1() {
      if (
        !validateFName() ||
        !validateLName() ||
        !validateBDay() || // corrected function name here
        !validateGender()
      ) {
        submitError.innerHTML = "Please fix errors to continue.";
        console.log("Form is not valid.");
        return false;

      }
      else {
        document.getElementById('step1').classList.add('d-none'); // Hide step 1
        document.getElementById('step2').classList.remove('d-none'); // Show step 2
      }
    }
  </script>
  <!-- end -->

  <?php
  include './../jobs management/chatbot.php';
  ?>
  <script src="./../../../front office assets/js/chatbot.js"></script>


</body>

</html>