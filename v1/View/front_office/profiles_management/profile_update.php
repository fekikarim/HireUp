<?php
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

/*
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}*/

// Check if profile ID is provided in the URL
if (!isset($_GET['profile_id'])) {
    header('Location: ../pages/404.php');
    exit();
}

// Include database connection and profile controller
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/user_con.php';
//include_once __DIR__ . '/../../../Controller/user_con.php';


//$userC = new userCon("user");


// Initialize profile controller
$profileController = new ProfileC();
$userC = new userCon('user');

// Get profile ID from the URL
$profile_id = $_GET['profile_id'];

// Fetch profile data from the database
$profile = $profileController->getProfileById($profile_id);
$user_profile = $profile;


if (isset($_SESSION['user id'])) {

    //MARK: important cz it checks if the user_id is set or not
    $user_id = htmlspecialchars($_SESSION['user id']);

    $user_role = $userC->get_user_role_by_id($user_id);

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./../../../front office assets/images/HireUp_icon.ico" />
    <title>Profile Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css"
        integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css'>
    <link rel="stylesheet" href="./assets/css/profile_update_style.css">
    <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />
    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>

</head>

<body>

    <?php
    $block_call_back = 'false';
    $access_level = "else";
    include ('./../../../View/callback.php')
        ?>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand ms-4" href="./../../../index.php">
                <img class="logo-img" alt="HireUp">
            </a>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <!-- Profile Photo -->
                <a href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                    class="d-flex align-items-center justify-content-center mx-3" style="height: 100%;">
                    <img src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>"
                        alt="Profile Photo" class="rounded-circle" width="50" height="50">
                    <span class="iconify ml-0 mb-5" data-icon="flag:<?php echo $country_code; ?>-4x3"></span>
                </a>


                <!-- Profile Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <!-- Dropdown Header -->
                    <h5 class="dropdown-header">Account</h5>
                    <!-- Profile Link -->
                    <li><a class="dropdown-item" href="./profile.php"><i class="fas fa-id-card-alt"></i> Profile</a>
                    </li>
                    <?php
                    if ($user_role == 'admin') {
                        ?>
                        <li><a class="dropdown-item text-success" href="./../../../View/back_office/main dashboard"><i
                                    class="fas fa-calculator"></i> Dashboard</a>
                        </li>
                        <?php
                    }
                    ?>

                    <li><a class="dropdown-item"
                            href="./../../../View/front_office/jobs management/career_explorers.php">
                            <i class="fas fa-user-tie"></i> Career Explorers</a></li>
                    <!-- Divider -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Try Premium -->
                    <li><a class="dropdown-header text-primary"
                            href="./subscription/subscriptionCards.php?profile_id=<?php echo $profile['profile_id'] ?>">Try
                            Premium
                            for $0</a></li>
                    <!-- Divider -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Settings & Privacy -->
                    <li><a class="dropdown-item"
                            href="./profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id'] ?>">
                            <i class="fas fa-cogs"></i> Settings & Privacy</a></li>
                    <!-- Help Link -->
                    <li><a class="dropdown-item" href="./../../../about.php"><i class="fas fa-question-circle"></i>
                            Help</a>
                    </li>
                    <!-- Language Link -->
                    <li><a class="dropdown-item" href="./settings_privacy/language_settings.php"><i
                                class="fas fa-language"></i>
                            Language</a></li>
                    <!-- Divider -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Manage Header -->
                    <h5 class="dropdown-header">Manage</h5>
                    <!-- Jobs Link -->
                    <li><a class="dropdown-item" href="./../jobs management/jobs_list.php"><i
                                class="fas fa-briefcase"></i>
                            Jobs</a></li>
                    <!-- Divider -->
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Reporting Header -->
                    <h5 class="dropdown-header">Report</h5>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="openPopup()"><i
                                class="fas fa-exclamation-circle"></i> Give Feedback</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Logout Link -->
                    <li><a class="dropdown-item" href="./../Sign In & Sign Up/logout.php"><i
                                class="fas fa-sign-out-alt"></i>
                            Logout</a></li>
                </ul>
            </div>

        </div>
    </nav>
    <!-- End Header Navbar -->

    <hr>
    <hr>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Page title -->
                <div class="my-5">
                    <h3>My Profile</h3>
                    <hr>
                </div>
                <!-- Form START -->
                <form class="file-upload" id="profileForm" action="./update.php" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="profile_id" name="profile_id"
                        value="<?php echo isset($profile['profile_id']) ? $profile['profile_id'] : ''; ?>" readonly />
                    <div class="row mb-5 gx-5">
                        <!-- Contact detail -->
                        <div class="col-xxl-8 mb-5 mb-xxl-0">
                            <div class="bg-secondary-soft px-4 py-5 rounded">
                                <div class="row g-3">
                                    <h4 class="mb-4 mt-0">Contact detail</h4>
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <label for="profile_first_name" class="form-label">First Name *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validateFName()"
                                                id="profile_first_name" name="profile_first_name"
                                                value="<?php echo isset($profile['profile_first_name']) ? $profile['profile_first_name'] : ''; ?>" />
                                            <span id="fname_error"></span>
                                        </div>
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <label for="profile_family_name" class="form-label">Family Name *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validateLName()"
                                                id="profile_family_name" name="profile_family_name"
                                                value="<?php echo isset($profile['profile_family_name']) ? $profile['profile_family_name'] : ''; ?>" />
                                            <span id="lname_error"></span>
                                        </div>
                                    </div>
                                    <!-- Current position -->
                                    <div class="col-md-6">
                                        <label class="form-label">Current position *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validatePos()"
                                                id="profile_current_position" name="profile_current_position"
                                                value="<?php echo isset($profile['profile_current_position']) ? $profile['profile_current_position'] : ''; ?>" />
                                            <span id="pos_error"></span>
                                        </div>
                                    </div>
                                    <!-- Education -->
                                    <div class="col-md-6">
                                        <label class="form-label">Education *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validateEdu()"
                                                id="profile_education" name="profile_education"
                                                value="<?php echo isset($profile['profile_education']) ? $profile['profile_education'] : ''; ?>" />
                                            <span id="edu_error"></span>
                                        </div>
                                    </div>
                                    <!-- Region -->
                                    <div class="col-md-6">
                                        <label for="profile_region" class="form-label">Region *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validateRegion()"
                                                id="profile_region" name="profile_region"
                                                value="<?php echo isset($profile['profile_region']) ? $profile['profile_region'] : ''; ?>" />
                                            <span id="region_error"></span>
                                        </div>
                                    </div>
                                    <!-- Ville -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile_city">City *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" onkeyup="validateCity()"
                                                id="profile_city" name="profile_city"
                                                value="<?php echo isset($profile['profile_city']) ? $profile['profile_city'] : ''; ?>" />
                                            <span id="city_error"></span>
                                        </div>
                                    </div>

                                    <!-- Birdhday -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile_bday">Birdhday *</label>
                                        <div class="position-relative">
                                            <input type="date" class="form-control" onchange="validateBDay()"
                                                id="profile_bday" name="profile_bday"
                                                value="<?php echo isset($profile['profile_bday']) ? $profile['profile_bday'] : ''; ?>" />
                                            <span id="bday_error" style="position: absolute;
                                                                            bottom: 9px;
                                                                            right: 40px;
                                                                            color: red;                                                           
                                                                            pointer-events: none;"><b
                                                    class="text-primary">We only hire up to 18yo</b></span>
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile_gender">Gender *</label>
                                        <div style="position: relative;">
                                            <select class="form-select" onchange="validateGender()" id="profile_gender"
                                                name="profile_gender">
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
                                    </div>

                                    <!-- Bio -->
                                    <div class="col-md-6 w-100">
                                        <label class="form-label">Bio *</label>
                                        <div class="position-relative">
                                            <textarea class="form-control" rows="3" onkeyup="validateBio()"
                                                id="profile_bio"
                                                name="profile_bio"><?php echo isset($profile['profile_bio']) ? $profile['profile_bio'] : ''; ?></textarea>
                                            <span id="bio_error"></span>
                                        </div>
                                    </div>
                                </div> <!-- Row END -->
                            </div>
                        </div>
                        <!-- Upload profile -->
                        <div class="col-xxl-4">
                            <div class="bg-secondary-soft px-4 py-5 rounded">
                                <div class="row g-3">
                                    <h4 class="mb-4 mt-0">Upload your profile photo</h4>
                                    <div class="text-center">
                                        <!-- Profile picture container -->
                                        <div class="profile-pic-container mb-3" id="profile_pic_display">
                                            <!-- Output the profile photo with appropriate MIME type -->
                                            <div class="profile-photo-wrapper">
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_photo']); ?>"
                                                    alt="Profile Photo">
                                            </div>
                                        </div>

                                        <!-- Hidden profile photo container -->
                                        <div class="hidden-profile-pic-container mb-3" id="hiddenProfilePhotoContainer"
                                            style="display: none;">
                                            <img src="#" alt="Hidden Profile Photo" class="hidden-profile-image"
                                                id="hiddenProfilePhoto">
                                        </div>
                                        <!-- Button -->
                                        <input type="file" class="form-control" id="profile_photo"
                                            name="update_profile_photo" hidden="" onchange="handlePhotoChange(event)"
                                            accept="image/*">
                                        <label class="btn btn-success-soft btn-block" for="profile_photo">Upload</label>
                                        <button type="button" class="btn btn-danger-soft"
                                            onclick="removeProfilePhoto()">Remove</button>
                                        <!-- Content -->
                                        <p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size
                                            300px x 300px</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Row END -->
                    <!-- Social media detail
                            <div class="row mb-5 gx-5">
                                <div class="col-xxl-6 mb-5 mb-xxl-0">
                                    <div class="bg-secondary-soft px-4 py-5 rounded">
                                        <div class="row g-3">
                                            <h4 class="mb-4 mt-0">Social media detail</h4>
                                            Facebook 
                                            <div class="col-md-6">
                                                <label class="form-label"><i class="fab fa-fw fa-facebook me-2 text-facebook"></i>Facebook *</label>
                                                <input type="text" class="form-control" placeholder="" aria-label="Facebook" value="">
                                            </div>
                                            Instragram 
                                            <div class="col-md-6">
                                                <label class="form-label"><i class="fab fa-fw fa-instagram text-instagram me-2"></i>Instagram *</label>
                                                <input type="text" class="form-control" placeholder="" aria-label="Instragram" value="">
                                            </div>
                                            Dribble 
                                            <div class="col-md-6">
                                                <label class="form-label"><i class="fas fa-fw fa-basketball-ball text-dribbble me-2"></i>Dribble *</label>
                                                <input type="text" class="form-control" placeholder="" aria-label="Dribble" value="">
                                            </div>
                                            Pinterest 
                                            <div class="col-md-6">
                                                <label class="form-label"><i class="fab fa-fw fa-pinterest text-pinterest"></i>Pinterest *</label>
                                                <input type="text" class="form-control" placeholder="" aria-label="Pinterest" value="">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                         Row END 
                    -->

                    <!-- Upload your profile cover -->
                    <div class="col mb-5">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <div class="row g-3">
                                <h4 class="my-4">Upload your profile cover</h4>
                                <div class="text-center">
                                    <!-- Image upload -->
                                    <div class="profile-cover-container w-100 mb-3" id="profile_cover_display">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_cover']); ?>"
                                            alt="Profile Cover" class="profile-cover-image">
                                    </div>
                                    <!-- Hidden profile cover container -->
                                    <div class="hidden-profile-cover-container w-100 mb-3"
                                        id="hiddenProfileCoverContainer" style="display: none;">
                                        <img src="#" alt="Hidden Profile Cover" class="hidden-profile-cover"
                                            id="hiddenProfileCover">
                                    </div>
                                    <!-- Button -->
                                    <input type="file" class="form-control" id="profile_cover"
                                        name="update_profile_cover" onchange="handleCoverChange(event)" accept="image/*"
                                        hidden="">
                                    <label class="btn btn-success-soft btn-block" for="profile_cover">Upload</label>
                                    <button type="button" class="btn btn-danger-soft"
                                        onclick="removeProfileCover()">Remove</button>
                                    <!-- Content -->
                                    <p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size 1352px x
                                        300px</p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div> <!-- Row END -->
            <br>
            <hr>
            <br>
            <!-- button -->
            <div class="gap-3 d-md-flex justify-content-center text-center">
                <a class="btn btn-info btn-lg" href="javascript:history.go(-1);">Cancel</a>
                <button onclick="return validateForm()" id="submit_button" class="btn btn-primary btn-lg">Update
                    profile</button>
            </div>
            <span id="submit_error" class="text-center mt-4"></span>
            </form> <!-- Form END -->
        </div>
    </div>
    </div>

    <!-- Popup Container -->
    <div id="reportPopup" class="popup-container" style="display: none;">
        <div class="popup-content">
            <!-- Popup Header -->
            <div class="popup-header">
                <span class="popup-close" onclick="closePopup()">&times;</span>
                <h2>Report to HireUp</h2>
            </div>
            <!-- Popup Body -->
            <div class="popup-body">
                <!-- View My Reports Link -->
                <a href="../reclamation/rec_list.php" class="popup-link">
                    <i class="fas fa-clipboard-list"></i> View my reports
                </a>
                <p><?php echo generateSubtitle(); ?></p>
                <hr>
                <!-- Help Us Improve Link -->
                <a href="../reclamation/reclamation.php" class="popup-link">
                    <i class="fas fa-comments"></i> Help us improve HireUp
                </a>
                <!-- Help Us Improve Subtitle -->
                <p><?php echo generateFeedbackSubtitle(); ?></p>
            </div>
        </div>
    </div>


    <?php
    function generateSubtitle()
    {
        return "Here you can report issues or inappropriate content to the HireUp team.";
    }

    function generateFeedbackSubtitle()
    {
        return "We appreciate your feedback! Share your thoughts and suggestions to help us enhance your HireUp experience.";
    }
    ?>


    <script>
        // Function to open the popup
        function openPopup() {
            document.getElementById('reportPopup').style.display = 'block';
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById('reportPopup').style.display = 'none';
        }
    </script>

    <!-- Footer -->
    <footer class="bg-dark text-center text-white py-3 mt-4">
        <div class="container">
            <p>&copy; 2024 All rights reserved to <b>be.net</b></p>
        </div>
    </footer>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

    <script>
        // Function to handle file input change for profile photo
        function handlePhotoChange(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
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

            reader.onload = function (e) {
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


        function removeProfilePhoto() {
            // Get the profile photo display element
            var profilePhotoDisplay = document.getElementById('profile_pic_display');

            // Get the hidden profile photo container
            var hiddenProfilePhotoContainer = document.getElementById('hiddenProfilePhotoContainer');

            // Set the source of the hidden profile photo to the default profile photo
            document.getElementById('hiddenProfilePhoto').src = "./../../../front office assets/images/banner.jpg";

            // Hide the profile photo display element
            profilePhotoDisplay.style.display = 'none';

            // Show the hidden profile photo container
            hiddenProfilePhotoContainer.style.display = 'block';
        }


        function removeProfileCover() {
            // Get the profile cover display element
            var profileCoverDisplay = document.getElementById('profile_cover_display');

            // Get the hidden profile cover container
            var hiddenProfileCoverContainer = document.getElementById('hiddenProfileCoverContainer');

            // Set the source of the hidden profile cover to the default cover photo
            document.getElementById('hiddenProfileCover').src = "./assets/img/default_cover.png";

            // Hide the profile cover display element
            profileCoverDisplay.style.display = 'none';

            // Show the hidden profile cover container
            hiddenProfileCoverContainer.style.display = 'block';
        }
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
                bioError.innerHTML = charactersRemaining + " characters remaining";
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
                !validateBDay() || // corrected function name here
                !validateGender()
            ) {
                submitError.innerHTML = "Please fix errors to submit.";
                return false;
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