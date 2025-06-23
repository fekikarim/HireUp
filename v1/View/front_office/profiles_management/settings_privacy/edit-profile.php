<?php

require_once __DIR__ . '/../../../../Controller/profileController.php';
include_once __DIR__ . '/../../../../Controller/user_con.php';
include_once __DIR__ . '/../../../../Controller/faces_con.php';

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

// Check if the request method is GET and if id_emp is set in the URL
//if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['profile_id'])) {
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve the profilee information from the database
    //$id = $_GET['profile_id'];

    $userC = new userCon("user");

    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params(0, '/', '', true, true);
        session_start();
    }

    if (isset($_SESSION['user id'])) {

        $user_id = htmlspecialchars($_SESSION['user id']);

        $user_role = $userC->get_user_role_by_id($user_id);

    } else {
        $user_id = '';
    }

    $user_infos = $userC->getUser($user_id);


    // Create an instance of the controller
    $profileController = new ProfileC();
    $faceController = new FaceController();

    // Get profile ID from the URL
    $profile_id = $profileController->getProfileIdByUserId($user_id);

    // Get the profilee details by ID
    $profile = $profileController->getProfileById($profile_id);


    $current_profile_link = $current_url . "View/front_office/profiles_management/profile.php?profile_id=" . $profile_id;

    $block_call_back = 'false';
    $access_level = "else";
    include ('./../../../../View/callback.php')

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Settings & Privacy</title>
        <link rel="shortcut icon" type="image/png" href="./../../../../front office assets\images\HireUp_icon.ico" />
        <link rel="stylesheet" href="../assets/css/edit_profile.css" />
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css'>

        <link rel="stylesheet" href="../assets/css/verifNumber_form.css" />

        <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./../../../../front office assets/css/chatbot.css" />

        <style>
            #submit_error {
                color: red;
            }

            div div>span {
                color: red;
            }

            .col-md-6 {
                position: relative;
            }

            .col-md-6 span {
                position: absolute;
                bottom: 8.5px;
                right: 8.5px;
                color: red;
                pointer-events: none;
                /* Ensure the span doesn't interfere with input events */
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
            .container-ui {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                margin-bottom: 20px;
                color: #333;
            }

            #videoContainer {
                position: relative;
                width: 100%;
                height: 400px;
                overflow: hidden;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            #videoElement {
                width: 100%;
                height: auto;
            }

            #captureBtn,
            #cancelBtn {
                padding: 10px 20px;
                font-size: 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin: 0 10px;
                transition: all 0.3s ease;
            }

            #captureBtn:hover,
            #cancelBtn:hover {
                background-color: #45a049;
            }
        </style>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>


    </head>

    <body>

        <?php
        // $block_call_back = 'false';
        // $access_level = "else";
        // include ('./../../../../View/callback.php')
            ?>

        <!-- Header Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand ms-4" href="../../../../index.php">
                    <img class="logo-img" alt="HireUp">
                </a>

                <!-- Profile Dropdown -->
                <div class="dropdown ms-auto">
                    <a href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                        class="d-flex align-items-center justify-content-center mx-3" style="height: 100%;">
                        <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>"
                            alt="Profile Photo" class="rounded-circle" width="50" height="50">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <h5 class="dropdown-header">Account</h5>
                        <li><a class="dropdown-item"
                                href="../profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Profile</a></li>

                        <?php if ($user_id) {
                            if ($user_role == 'admin') {
                                ?>
                                <li><a class="dropdown-item text-success"
                                        href="../../../../View/back_office/main dashboard">Dashboard</a></li>
                                <?php
                            }
                        }
                        ?>

                        <li><a class="dropdown-item"
                                href="./../../../../View/front_office/jobs management/career_explorers.php">Career
                                Explorers</a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-header" href="../subscription/subscriptionCards.php">Try Premium for $0</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                                href="./edit-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Settings
                                & Privacy</a></li>
                        <li><a class="dropdown-item" href="#">Help</a></li>
                        <li><a class="dropdown-item"
                                href="./language_settings.php?profile_id=<?php echo $profile['profile_id'] ?>">Language</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <h5 class="dropdown-header">Manage</h5>
                        <li><a class="dropdown-item" href="#">Posts & Activity</a></li>
                        <li><a class="dropdown-item" href="../../jobs management/jobs_list.php">Jobs</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item"
                                href="../../../../View/front_office/Sign In & Sign Up/logout.php">Logout</a></li>
                    </ul>
                </div>

            </div>
        </nav>
        <!-- End Header Navbar -->

        <hr>
        <hr>
        <hr>

        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                <a class="nav-link active ms-0"
                    href="edit-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Profile</a>
                <a class="nav-link ms-0"
                    href="close_account.php?profile_id=<?php echo $profile['profile_id'] ?>">Close Account</a>
                <!-- <a class="nav-link" href="./billing-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Billing</a>
                <a class="nav-link"
                    href="./security-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Security</a>
                <a class="nav-link"
                    href="./notifications-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Notifications</a> -->
            </nav>
            <hr class="mt-0 mb-4">
            <div class="row">

                <div class="col-xl-4">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <!-- Profile picture container -->
                            <div class="profile-pic-container mb-3" id="profile_pic_display">
                                <!-- Output the profile photo with appropriate MIME type -->
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_photo']); ?>"
                                    alt="Profile Photo">
                            </div>
                            <!-- Hidden profile photo container -->
                            <div class="hidden-profile-pic-container mb-3" id="hiddenProfilePhotoContainer"
                                style="display: none;">
                                <img src="#" alt="Hidden Profile Photo" class="hidden-profile-image"
                                    id="hiddenProfilePhoto">
                            </div>
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                            <form id="profileFormPic" action="update-pic.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="profile_id" value="<?php echo $profile['profile_id']; ?>">
                                <input type="file" class="form-control" id="profile_photo" name="update_profile_photo"
                                    hidden="" onchange="handlePhotoChange(event)" accept="image/*">
                                <label class="btn btn-primary" for="profile_photo">Upload new image</label>
                                <button type="button" class="btn btn-danger" onclick="removeProfilePhoto()">Remove</button>
                                <button type="submit" id="submit_button" class="btn btn-success"
                                    style="display: none;">Save</button>
                            </form>
                        </div>
                    </div>

                    <!-- qr link -->
                    <div class="col-xl-12 mb-3 mt-3">
                        <!-- Profile picture card-->
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-header">Profile Link</div>
                            <div class="card-body text-center">
                                <?php echo $profileController->getQrCode($current_profile_link); ?>
                            </div>
                        </div>
                    </div>
                    <!-- qr link end -->

                </div>

                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <form id="profileForm" action="./update.php" method="POST">
                                <input type="hidden" class="form-control" id="profile_id" name="profile_id"
                                    value="<?php echo isset($profile['profile_id']) ? $profile['profile_id'] : ''; ?>"
                                    readonly />
                                <!-- Form Group (username)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputUsername">Username (how your name will appear to
                                        other users on the site)</label>
                                    <input class="form-control" id="inputUsername" type="text"
                                        placeholder="Enter your username"
                                        value="<?php echo isset($user_infos['user_name']) ? $user_infos['user_name'] : ''; ?>"
                                        disabled>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputFirstName">First name</label>
                                        <input type="text" class="form-control" onkeyup="validateFName()"
                                            id="profile_first_name" placeholder="Enter your first name"
                                            name="profile_first_name"
                                            value="<?php echo isset($profile['profile_first_name']) ? $profile['profile_first_name'] : ''; ?>" />
                                        <span id="fname_error"></span>
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLastName">Last name</label>
                                        <input type="text" class="form-control" onkeyup="validateLName()"
                                            id="profile_family_name" placeholder="Enter your last name"
                                            name="profile_family_name"
                                            value="<?php echo isset($profile['profile_family_name']) ? $profile['profile_family_name'] : ''; ?>" />
                                        <span id="lname_error"></span>
                                    </div>
                                </div>
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                    <input class="form-control" id="inputEmailAddress" type="email"
                                        placeholder="Enter your email address"
                                        value="<?php echo isset($user_infos['email']) ? $user_infos['email'] : ''; ?>"
                                        disabled>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Phone Number</label>
                                    <input class="form-control" id="old_profile_phone_number" type="text"
                                        value="<?php echo isset($profile['profile_phone_number']) ? $profile['profile_phone_number'] : ''; ?>"
                                        disabled>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (Gender)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Gender</label>
                                        <select class="form-select" onchange="validateGender()" id="profile_gender"
                                            name="profile_gender">
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="Male" <?php echo isset($profile['profile_gender']) && strtolower($profile['profile_gender']) === 'male' ? 'selected' : ''; ?>>Male
                                            </option>
                                            <option value="Female" <?php echo isset($profile['profile_gender']) && strtolower($profile['profile_gender']) === 'female' ? 'selected' : ''; ?>>
                                                Female</option>
                                        </select>
                                        <span id="gender_error" style="position: absolute;
                                                                            bottom: 8.5px;
                                                                            right: 35px;
                                                                            color: red;
                                                                            pointer-events: none;"></span>
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputBirthday">Birthday</label>
                                        <input type="date" class="form-control" onchange="validateBDay()" id="profile_bday"
                                            name="profile_bday"
                                            value="<?php echo isset($profile['profile_bday']) ? $profile['profile_bday'] : ''; ?>" />
                                        <span id="bday_error" style="position: absolute;
                                                                            bottom: 9px;
                                                                            right: 40px;
                                                                            color: red;                                                           
                                                                            pointer-events: none;"><b
                                                class="text-primary">We only hire up to 18yo</b></span>
                                    </div>
                                </div>
                                <!-- Form Row -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (Current position)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Current position</label>
                                        <input type="text" class="form-control" onkeyup="validatePos()"
                                            placeholder="Enter your Current position" id="profile_current_position"
                                            name="profile_current_position"
                                            value="<?php echo isset($profile['profile_current_position']) ? $profile['profile_current_position'] : ''; ?>" />
                                        <span id="pos_error"></span>
                                    </div>
                                    <!-- Form Group (Education)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLocation">Education</label>
                                        <input type="text" class="form-control" onkeyup="validateEdu()"
                                            placeholder="Enter your Education" id="profile_education"
                                            name="profile_education"
                                            value="<?php echo isset($profile['profile_education']) ? $profile['profile_education'] : ''; ?>" />
                                        <span id="edu_error"></span>
                                    </div>
                                </div>
                                <!-- Form Row -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (Country)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Country</label>
                                        <input type="text" class="form-control" onkeyup="validateRegion()"
                                            placeholder="Enter your Region/Country" id="profile_region"
                                            name="profile_region"
                                            value="<?php echo isset($profile['profile_region']) ? $profile['profile_region'] : ''; ?>" />
                                        <span id="region_error"></span>
                                    </div>
                                    <!-- Form Group (City)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLocation">City</label>
                                        <input type="text" class="form-control" onkeyup="validateCity()"
                                            placeholder="Enter your City" id="profile_city" name="profile_city"
                                            value="<?php echo isset($profile['profile_city']) ? $profile['profile_city'] : ''; ?>" />
                                        <span id="city_error"></span>
                                    </div>
                                </div>

                                <!-- Save changes button-->
                                <button class="btn btn-primary" onclick="return validateForm()">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="col-xl-4 mb-3">
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">New Phone Number</div>
                        <div class="card-body text-center">
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-2">Verify your Phone Number</div>
                            <form id="profileFormPhone" method="POST">
                                <input type="hidden" name="action" value="verif">
                                <input type="hidden" class="form-control" id="profile_id"
                                    value="<?php echo isset($profile['profile_id']) ? $profile['profile_id'] : ''; ?>">
                                <input type="hidden" name="verification_code" id="verification_code"
                                    value="<?php echo $code ?>">
                                <div class="mb-3">
                                    <input type="text" class="form-control phone_nb_to_change" id="profile_phone_number"
                                        placeholder="Enter Phone Number..." name="update_profile_phone_number">
                                </div>
                                <button type="button" id="update_number_button" class="btn btn-primary">Update New
                                    Number</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <?php $does_user_have_a_face = $faceController->faceExistsByUserId($user_id); ?>
                    <!-- Face ID card-->
                    <div class="card mb-4">
                        <div class="card-header">Face ID</div>
                        <div class="card-body text-center" id="face_id_card_btns">
                            <?php if (!$does_user_have_a_face) { ?>
                                <button type="button" class="btn btn-primary" onclick="show_pop_up()">Join Now</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary" onclick="show_pop_up_edit()">Edit</button>
                                &nbsp;
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="UnsubscribeClicked()">Unsubscribe</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div id="popup-card" class="popup-card">
                    <div class="popup-content">
                        <span id="close-popup" class="close">&times;</span>
                        <h3 id="popup-Name" class="text-capitalize">Face ID</h3>
                        <hr><br>
                        <iframe id="face_detection_iframe" src="./../../face_detection/index_ui.html"></iframe>
                    </div>
                </div>

            </div>
        </div>


        <!-- Footer -->
        <footer class="bg-dark text-center text-white py-3">
            <div class="container">
                <p>&copy; 2024 All rights reserved to <b>be.net</b></p>
            </div>
        </footer>


        <!-- Verification form -->
        <div class="verification-modal">
            <form class="form verification-form" id="verifCodeForm"
                action="./update-phone.php?profile_id=<?php echo $profile['profile_id']; ?>" method="POST">
                <span class="close">&times;</span>
                <div class="info">
                    <span class="title">Two-Factor Verification</span>
                    <p class="description">Enter the verification code sent to your new phone number</p>
                </div>
                <div class="input-fields">
                    <input placeholder="" type="tel" maxlength="1" name="digit1" class="digit-input" required>
                    <input placeholder="" type="tel" maxlength="1" name="digit2" class="digit-input" required>
                    <input placeholder="" type="tel" maxlength="1" name="digit3" class="digit-input" required>
                    <input placeholder="" type="tel" maxlength="1" name="digit4" class="digit-input" required>
                </div>
                <div class="action-btns">
                    <button type="submit" id="verifyButton" class="verify" onclick="changeActionForPhoneNbUpdate()"
                        disabled>Verify</button>
                    <button type="button" class="clear">Clear</button>
                </div>
            </form>
        </div>


        <script>
            function changeActionForPhoneNbUpdate() {
                var form = document.getElementById("verifCodeForm");
                cuurnent_phone_nb = document.getElementById("profile_phone_number").value;
                current_acction = form.getAttribute("action");
                form.setAttribute("action", current_acction + "&phone_nb=" + cuurnent_phone_nb);
                //alert(form.getAttribute("action"));

            }
        </script>


        <!-- Alert Popup 
            <div class="alert-popup" id="alertPopup">
                <div class="alert-content">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-message">
                        Profile Picture Updated Successfully
                    </div>
                </div>
                <button class="close-btn" onclick="closeAlertPopup()">Close</button>
            </div>-->

        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js'></script>

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

                    // Display the save button
                    document.getElementById('submit_button').style.display = 'inline';
                };

                reader.readAsDataURL(file);
            }


            function removeProfilePhoto() {
                // Get the profile photo display element
                var profilePhotoDisplay = document.getElementById('profile_pic_display');

                // Get the hidden profile photo container
                var hiddenProfilePhotoContainer = document.getElementById('hiddenProfilePhotoContainer');

                // Set the source of the hidden profile photo to the default profile photo
                document.getElementById('hiddenProfilePhoto').src = "../../assets/images/banner.jpg";

                // Hide the profile photo display element
                profilePhotoDisplay.style.display = 'none';

                // Show the hidden profile photo container
                hiddenProfilePhotoContainer.style.display = 'block';

                // Display the save button
                document.getElementById('submit_button').style.display = 'inline';
            }



            // Function to show the alert popup
            function showAlertPopup() {
                var alertPopup = document.getElementById('alertPopup');
                alertPopup.style.display = 'block';
            }

            // Function to close the alert popup
            function closeAlertPopup() {
                var alertPopup = document.getElementById('alertPopup');
                alertPopup.style.display = 'none';
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const digitInputs = document.querySelectorAll(".digit-input");
                const verifyButton = document.getElementById("verifyButton");

                // Function to check if all input fields are filled
                function checkInputs() {
                    let allFilled = true;
                    digitInputs.forEach(function (input) {
                        if (input.value === "") {
                            allFilled = false;
                        }
                    });
                    return allFilled;
                }

                // Function to enable/disable verify button
                function toggleVerifyButton() {
                    if (checkInputs()) {
                        verifyButton.removeAttribute("disabled");
                    } else {
                        verifyButton.setAttribute("disabled", "disabled");
                    }
                }

                // Add event listeners to input fields
                digitInputs.forEach(function (input) {
                    input.addEventListener("input", function () {
                        toggleVerifyButton();
                    });
                });
            });
        </script>

        <script src="edit_profile.js"></script>


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

        <script>

            function show_pop_up() {
                var face_detection_iframe = document.getElementById("face_detection_iframe");
                var modal = document.getElementById("popup-card");

                face_detection_iframe.src = "./../../face_detection/index_ui.html";
                modal.style.display = "block";
            }

            function show_pop_up_edit() {
                var face_detection_iframe = document.getElementById("face_detection_iframe");
                var modal = document.getElementById("popup-card");

                face_detection_iframe.src = "./../../face_detection/view_capture_ui.php";
                modal.style.display = "block";
            }

            function UnsubscribeClicked() {
                var result = window.confirm("Are you sure you want to unsubscribe from the face id service?");
                if (result) {
                    window.location.href = "./../../face_detection/delete_a_face.php";
                }
            }

            var modal = document.getElementById("popup-card");
            var closeButton = document.getElementById("close-popup");
            var face_detection_iframe = document.getElementById("face_detection_iframe");

            closeButton.onclick = function () {
                modal.style.display = "none";
                face_detection_iframe.src = "";
            };

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    face_detection_iframe.src = "";
                }
            };


        </script>

        <script>
            // Function to handle messages from the iframe
            function handleMessage(event) {
                // if (event.data === 'captureFrameCompleted') {
                //     face_detection_iframe.src = "./../../face_detection/capture_resualt_ui.php";
                // } 
                if (event.data === 'accepted') {
                    closePopupAfterSaving();
                } else if (event.data === 'tryAgain') {
                    face_detection_iframe.src = "./../../face_detection/index_ui.html";
                }
            }

            // Add event listener to listen for messages from the iframe
            window.addEventListener('message', handleMessage);

            function closePopupAfterSaving() {
                var modal = document.getElementById("popup-card");
                var face_detection_iframe = document.getElementById("face_detection_iframe");
                var face_id_card_btns = document.getElementById("face_id_card_btns");

                modal.style.display = "none";
                face_detection_iframe.src = "";
                face_id_card_btns.innerHTML = '<button type="button" class="btn btn-primary" onclick="show_pop_up_edit()">Edit</button> &nbsp; <button type="button" class="btn btn-outline-danger" onclick="UnsubscribeClicked()">Unsubscribe</button>';
            }
        </script>

        <!-- voice recognation -->
	    <script type="text/javascript" src="./../../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


        <?php
        include './../../jobs management/chatbot.php';
        ?>
        <script src="./../../../../front office assets/js/chatbot.js"></script>

    </body>

    </html>

    <?php

} else {
    // Invalid request, handle this case
    echo "Invalid request";
}
?>