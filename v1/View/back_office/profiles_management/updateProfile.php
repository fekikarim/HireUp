<?php
require_once __DIR__ . '/../../../Controller/profileController.php';

if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


// Check if the request method is GET and if id_emp is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['profile_id'])) {
  // Retrieve the profilee information from the database
  $id = $_GET['profile_id'];

  // Create an instance of the controller
  $profileController = new ProfileC();

  // Get the profilee details by ID
  $profile = $profileController->getProfileById($id);

  // Check if profile is set and not null
  
    // profilee details are available, proceed with displaying the form
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Update Profile</title>
      <link rel="shortcut icon" type="image/png" href="./../../../assets/images/logos/HireUp_icon.ico" />
      <link rel="stylesheet" href="./../../../assets/css/styles.min.css" />
      <link rel="stylesheet" href="./css/phone.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />

      <!-- Add the intlTelInput CSS -->
      <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

      <style>
        /* Style for profile picture container */
        .profile-picture-container {
          width: 200px;
          height: 200px;
          border-radius: 50%;
          overflow: hidden;
          border: 4px solid #fff;
          /* White border */
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          /* Shadow effect */
          margin: auto;
          /* Center the container */
        }

        /* Style for profile cover container */
        .profile-cover-container {
          width: 100%;
          height: 200px;
          /* Adjust height as needed */
          overflow: hidden;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          /* Shadow effect */
        }

        /* Style for profile cover image */
        .profile-cover-image {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        /* Style for hidden profile photo and cover container */
        .hidden-profile-pic-container {
          width: 200px;
          height: 200px;
          border-radius: 50%;
          overflow: hidden;
          border: 4px solid #fff;
          /* White border */
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          /* Shadow effect */
          margin: auto;
          /* Center the container */
        }

        /* Style for profile cover container */
        .hidden-profile-cover-container {
          width: 100%;
          height: 200px;
          /* Adjust height as needed */
          overflow: hidden;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          /* Shadow effect */
        }

        /* Style for hidden profile image */
        .hidden-profile-image {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        #profileForm .mb-3 span {
          position: absolute;
          bottom: 8.5px;
          right: 8.5px;
          color: red;
          pointer-events: none;
          /* Ensure the span doesn't interfere with input events */
        }

        #submit_error {
          color: red;
          font-size: medium;
          margin-left: 15px;
        }
      </style>

      <!-- voice recognation -->
      <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

    </head>

    <body>


    <?php 
      $block_call_back = 'false';
      $access_level = "admin";
      include('./../../../View/callback.php')  
    ?>

      <!--  Body Wrapper -->
      <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php 
            $active_page = "profile";
            $nb_adds_for_link = 3;
            include('../../../View/back_office/dashboard_side_bar.php') 
        ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
          <!--  Header Start -->
          <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <!--  login place -->
                    <?php include('../../../View/back_office/header_bar.php') ?>
            
                </nav>
            </header>
          <!--  Header End -->
          <div class="container-fluid">
            <div class="container-fluid">
              <div class="card">
                <div class="card-body">
                  <h2 class="card-title fw-semibold mb-4" style="font-size: xx-large;"><a class="ti ti-edit" style="color: #212529;"></a>Update Profile</h2>
                  <hr><br>
                  <!-- Form for adding new profile -->
                  <form id="profileForm" action="update.php" method="POST" enctype="multipart/form-data">
                    <!-- Login Information -->
                    <div class="mb-3">
                      <label for="firstName" class="form-label">Profile ID *</label>
                      <input type="text" class="form-control" id="profile_id" name="profile_id" value="<?php echo isset($profile['profile_id']) ? $profile['profile_id'] : ''; ?>" readonly />
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="firstName" class="form-label">First Name *</label>
                      <input type="text" class="form-control" onkeyup="validateFName()" id="profile_first_name" name="profile_first_name" value="<?php echo isset($profile['profile_first_name']) ? $profile['profile_first_name'] : ''; ?>" />
                      <span id="fname_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="familyName" class="form-label">Family Name *</label>
                      <input type="text" class="form-control" onkeyup="validateLName()" id="profile_family_name" name="profile_family_name" value="<?php echo isset($profile['profile_family_name']) ? $profile['profile_family_name'] : ''; ?>" />
                      <span id="lname_error"></span>
                    </div>

                    <div class="mb-3">
                      <div class="select-box">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <div class="selected-option">
                          <div>
                            <span class="iconify" data-icon="flag:tn-4x3"></span>
                            <strong>+216</strong>
                          </div>
                          <input type="tel" class="form-control" id="profile_phone_number" name="profile_phone_number" value="<?php echo isset($profile['profile_phone_number']) ? $profile['profile_phone_number'] : ''; ?>" />
                          
                        </div>
                        <div class="options">
                          <input type="text" id="search-box" name="search-box" class="form-control search-box" placeholder="Search Country Name">
                          <ol></ol>
                        </div>
                      </div>
                    </div>

                    <div class="mb-3" style="position: relative;">
                      <label for="country" class="form-label">Country/Region</label>
                      <input type="text" class="form-control" onkeyup="validateRegion()" id="profile_region" name="profile_region" value="<?php echo isset($profile['profile_region']) ? $profile['profile_region'] : ''; ?>" />
                      <span id="region_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="city" class="form-label">City</label>
                      <input type="text" class="form-control" onkeyup="validateCity()" id="profile_city" name="profile_city" value="<?php echo isset($profile['profile_city']) ? $profile['profile_city'] : ''; ?>" />
                      <span id="city_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="bio" class="form-label">Bio</label>
                      <textarea class="form-control" onkeyup="validateBio()" rows="3" id="profile_bio" name="profile_bio"><?php echo isset($profile['profile_bio']) ? $profile['profile_bio'] : ''; ?></textarea>
                      <span id="bio_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="currentPosition" class="form-label">Current Position</label>
                      <input type="text" class="form-control" onkeyup="validatePos()" id="profile_current_position" name="profile_current_position" value="<?php echo isset($profile['profile_current_position']) ? $profile['profile_current_position'] : ''; ?>" />
                      <span id="pos_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="education" class="form-label">Education</label>
                      <input type="text" class="form-control" onkeyup="validateEdu()" id="profile_education" name="profile_education" value="<?php echo isset($profile['profile_education']) ? $profile['profile_education'] : ''; ?>" />
                      <span id="edu_error"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="bday" class="form-label">Birthday</label>
                      <input type="date" class="form-control" onchange="validateBDay()" id="profile_bday" name="profile_bday" value="<?php echo isset($profile['profile_bday']) ? $profile['profile_bday'] : ''; ?>" />
                      <span id="bday_error" style="position: absolute;
                                                  bottom: 9px;
                                                  right: 40px;
                                                  color: red;                                                           
                                                  pointer-events: none;"><b class="text-primary">We only hire up to 18yo</b></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="gender" class="form-label">Gender</label>
                      <select class="form-select" onchange="validateGender()" id="profile_gender" name="profile_gender">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="Male" <?php echo isset($profile['profile_gender']) && strtolower($profile['profile_gender']) === 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo isset($profile['profile_gender']) && strtolower($profile['profile_gender']) === 'female' ? 'selected' : ''; ?>>Female</option>
                      </select>
                      <span id="gender_error" style="position: absolute;
                                                    bottom: 8.5px;
                                                    right: 35px;
                                                    color: red;
                                                    pointer-events: none;"></span>
                    </div>
                    <div class="mb-3" style="position: relative;">
                      <label for="plan_name" class="form-label">Subscription</label>
                      <select class="form-select" id="plan_name" name="plan_name" required>
                        <option value="" selected disabled>Select Subscription</option>
                        <?php // Get subscription options and set selected option
                        echo $profileController->generateSubsOptionsUpdate(isset($profile['profile_subscription']) ? $profile['profile_subscription'] : '');
                        ?>
                      </select>
                      <span id="subs_error"></span>
                    </div>

                    <div class="mb-3" style="position: relative;">
                      <label for="authType" class="form-label">Authentication Type *</label>
                      <select id="profile_auth" name="profile_auth" class="form-select">
                        <option value="" selected disabled>Select verification Type</option>
                        <option value="Login Credentials" <?php echo isset($profile['profile_auth']) && strtolower($profile['profile_auth']) === 'login credentials' ? 'selected' : ''; ?>>Login Credentials</option>
                        <option value="Social Login" <?php echo isset($profile['profile_auth']) && strtolower($profile['profile_auth']) === 'social login' ? 'selected' : ''; ?>>Social Login</option>
                        <option value="Multi-Factor Authentication (MFA)" <?php echo isset($profile['profile_auth']) && strtolower(str_replace(['-', '(', ')'], '', $profile['profile_auth'])) === 'multifactor authentication mfa' ? 'selected' : ''; ?>>Multi-Factor Authentication (MFA)</option>

                      </select>
                      <span id="auth_error"></span>
                    </div>

                    <div class="mb-3" style="position: relative;">
                      <label for="accountVerification" class="form-label">Account Verification *</label>
                      <select class="form-select" id="profile_acc_verif" name="profile_acc_verif">
                        <option value="" selected disabled>Select verification status</option>
                        <option value="Verified" <?php echo isset($profile['profile_acc_verif']) && strtolower($profile['profile_acc_verif']) === 'verified' ? 'selected' : ''; ?>>Verified</option>
                        <option value="Pending" <?php echo isset($profile['profile_acc_verif']) && strtolower($profile['profile_acc_verif']) === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Rejected" <?php echo isset($profile['profile_acc_verif']) && strtolower($profile['profile_acc_verif']) === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                      </select>
                      <span id="verif_error"></span>
                    </div><br>
                    <hr>


                    <!-- Profile picture container -->
                    <div class="profile-picture-container" id="profile_pic_display">
                      <!-- Output the profile photo with appropriate MIME type -->
                      <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_photo']); ?>" alt="Profile Photo" class="img-fluid">
                    </div>

                    <!-- Hidden profile photo container -->
                    <div class="hidden-profile-pic-container" id="hiddenProfilePhotoContainer" style="display: none;">
                      <img src="#" alt="Hidden Profile Photo" class="hidden-profile-image" id="hiddenProfilePhoto">
                    </div>

                    <!-- Add input field for profile photo -->
                    <div class="mb-3">
                      <label for="profile photo" class="form-label">Choose New Profile Photo</label>
                      <input type="file" class="form-control" id="profile_photo" name="profile_photo" onchange="handlePhotoChange(event)" accept="image/*">
                    </div><br>
                    <hr>

                    <!-- Hidden profile cover container -->
                    <div class="hidden-profile-cover-container" id="hiddenProfileCoverContainer" style="display: none;">
                      <img src="#" alt="Hidden Profile Cover" class="hidden-profile-image" id="hiddenProfileCover">
                    </div>

                    <!-- Profile cover container -->
                    <div class="profile-cover-container" id="profile_cover_display">
                      <!-- Output the profile cover with appropriate MIME type -->
                      <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_cover']); ?>" alt="Profile Cover" class="img-fluid profile-cover-image">
                    </div><br>

                    <!-- Add input field for profile cover -->
                    <div class="mb-3">
                      <label for="profile cover" class="form-label">Choose New Profile Cover</label>
                      <input type="file" class="form-control" id="profile_cover" name="profile_cover" onchange="handleCoverChange(event)" accept="image/*">
                    </div><br>


                    <!-- Submit Button -->
                    <button onclick="return validateForm()" id="submit_button" class="btn btn-primary">
                      Update Profile
                    </button>
                    <span id="submit_error"></span>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="./../../../assets/libs/jquery/dist/jquery.min.js"></script>
      <script src="./../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script src="./../../../assets/js/sidebarmenu.js"></script>
      <script src="./../../../assets/js/app.min.js"></script>
      <script src="./../../../assets/libs/simplebar/dist/simplebar.js"></script>
      
      
      <script src="./js/finition.js"></script>
      <script src="./js/phone.js"></script>

      <!-- JavaScript to handle file input change events -->
      <script>
        // Function to handle file input change for profile photo
        function handlePhotoChange(event) {
          const file = event.target.files[0];
          const reader = new FileReader();

          reader.onload = function(e) {
            const profilePhoto = document.getElementById('profile_pic_display');
            const hiddenProfilePhotoContainer = document.getElementById('hiddenProfilePhotoContainer');

            // Set the source of hidden profile photo
            document.getElementById('hiddenProfilePhoto').src = e.target.result;

            // Show the hidden profile photo container and hide the displayed photo
            profilePhoto.style.display = 'none';
            hiddenProfilePhotoContainer.style.display = 'block';
          };

          reader.readAsDataURL(file);
        }

        // Function to handle file input change for profile cover
        function handleCoverChange(event) {
          const file = event.target.files[0];
          const reader = new FileReader();

          reader.onload = function(e) {
            const profileCover = document.getElementById('profile_cover_display');
            const hiddenProfileCoverContainer = document.getElementById('hiddenProfileCoverContainer');

            // Set the source of hidden profile cover
            document.getElementById('hiddenProfileCover').src = e.target.result;

            // Show the hidden profile cover container and hide the displayed cover
            profileCover.style.display = 'none';
            hiddenProfileCoverContainer.style.display = 'block';
          };

          reader.readAsDataURL(file);
        }

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
        var bioError = document.getElementById("bio_error");

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

        function validateBio() {
          var bio = document.getElementById("profile_bio").value;
          var maxLength = 150;
          var charactersRemaining = maxLength - bio.length;

          if (bio.length == 0) {
            bioError.innerHTML = "Bio is required.";
            return false;
          }
          if (bio.length < 2) {
            bioError.innerHTML = "Bio must be at least 2 characters.";
            return false;
          }
          if (charactersRemaining >= 0 && bio.length >= 2) {
            bioError.innerHTML = "<b class='text-primary'>" + charactersRemaining + " characters remaining</b>";
            return true;
          } else {
            bioError.innerHTML = "Profile Bio should not exceed 150 characters";
            return false;
          }
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
            !validateFName() ||
            !validateLName() ||
            !validatePos() ||
            !validateEdu() ||
            !validateRegion() ||
            !validateCity() ||
            !validateBio() ||
            !validateBDay() || // corrected function name here
            !validateGender()
          ) {
            submitError.innerHTML = "Please fix errors to submit.";
            return false;
          }
        }
      </script>

      <!-- voice recognation -->
	    <script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

      <!-- end -->


    </body>

    </html>

<?php

} else {
  // Invalid request, handle this case
  echo "Invalid request";
}
?>