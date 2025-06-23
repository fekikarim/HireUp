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

// Check if profile ID is provided in the URL and is a positive integer
/*if (!isset($_GET['profile_id'])) {
    // Display a friendly message or redirect the user to a proper error page
    exit("Invalid profile ID provided");
}*/

// Include database connection and profile controller
require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';


// Initialize profile controller
$profileController = new ProfileC();
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
    exit("Invalid profile ID provided");
}

// Initialize profile controller
$profileController = new ProfileC();

// Get profile ID from the URL
$profile_id = $profileController->getProfileIdByUserId($user_id);

// Fetch profile data from the database
$profile = $profileController->getProfileById($profile_id);



// Check if profile data is retrieved successfully
if (!$profile) {
    // Display a friendly message or redirect the user to a proper error page
    exit("Profile data not found");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings & Privacy</title>
    <link rel="shortcut icon" type="image/png" href="./../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" href="./../../../assets/css/styles.min.css" />

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />

    <style>
        .card-btn {
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 5px;
            width: 100%;
            text-align: left;
            font-size: 1rem;
            color: inherit;
        }

        .card-btn:hover {
            background-color: #f0f0f0;
            /* Gray color on hover */
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

</head>

<body>

    <?php
    $block_call_back = 'false';
    $access_level = "else";
    include ('./../../../View/callback.php')
        ?>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a title="#" href="../../../index.php" class="text-nowrap logo-img">
                        <img class="logo-img" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>

                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Settings</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link active"
                                href="./profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id']; ?>"
                                aria-expanded="false">
                                <span>
                                    <i class="ti ti-user"></i>
                                </span>
                                <span class="hide-menu">Account Preferences</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../../back_office/interface/job_management.html"
                                aria-expanded="false">
                                <span>
                                    <i class="ti ti-lock"></i>
                                </span>
                                <span class="hide-menu">Sign In & Security</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="profile_management.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-eye"></i>
                                </span>
                                <span class="hide-menu">Visibility</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../interface/job_management.html" aria-expanded="false">
                                <span>
                                    <i class="ti ti-shield"></i>
                                </span>
                                <span class="hide-menu">Data Privacy</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../interface/job_management.html" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-board"></i>
                                </span>
                                <span class="hide-menu">Advertising Data</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../interface/job_management.html" aria-expanded="false">
                                <span>
                                    <i class="ti ti-bell-ringing"></i>
                                </span>
                                <span class="hide-menu">Notification</span>
                            </a>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a title="#" class="nav-link nav-icon-hover" href="#" id="drop2" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['profile_photo']); ?>"
                                        alt="Profile Photo" width="35" height="35" class="rounded-circle" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="./profile.php?profile_id=<?php echo $profile['profile_id']; ?>"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>

                                        <?php if ($user_id) {
                                            if ($user_role == 'admin') {
                                                ?>
                                                <a class="d-flex align-items-center gap-2 dropdown-item"
                                                    href="../../../View/back_office/main dashboard">
                                                    <i class="ti ti-layout fs-6 text-success"></i>
                                                    <p class="mb-0 fs-3 text-success">Dashboard</p>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <a 
                                            class="dropdown-item"
                                            href="./../../../../View/front_office/jobs management/career_explorers.php">
                                            <i class="ti ti-briefcase fs-6"></i>
                                            Career Explorers
                                        </a>

                                        <hr>

                                        <h6 class="dropdown-header">Account</h6>
                                        <a href="./profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id']; ?>"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-settings fs-6"></i>
                                            <p class="mb-0 fs-3">Settings & Privacy</p>
                                        </a>
                                        <a href="#" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-help fs-6"></i>
                                            <p class="mb-0 fs-3">Help</p>
                                        </a>
                                        <a href="#" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-language fs-6"></i>
                                            <p class="mb-0 fs-3">Language</p>
                                        </a>
                                        <hr>

                                        <h6 class="dropdown-header">Manage</h6>
                                        <a href="../jobs management/jobs_list.php"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-tie fs-6"></i>
                                            <p class="mb-0 fs-3">Jobs</p>
                                        </a>
                                        <hr>

                                        <!-- Reporting Header -->
                                        <h5 class="dropdown-header">Report</h5>
                                        <a class="dropdown-item" href="../reclamation/reclamation.php">Give Feedback</a>

                                        <hr>

                                        <a href="../../../View/front_office/Sign In & Sign Up/logout.php"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-logout fs-6"></i>
                                            <p class="mb-0 fs-3">Sign Out</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

            </header>

            <div class="container-fluid">
                <div class="container-fluid">
                    <!-- Cards start here -->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Profile Information</h5>
                                    <hr>
                                    <button type="button" class="card-btn" onclick="redirectToProfileEdit()">Edit all
                                        profile details</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Display</h5>
                                    <hr>
                                    <button type="button" class="card-btn" onclick="redirectToAppearence()">Dark
                                        mode</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">General Preferences</h5>
                                    <hr>
                                    <button type="button" class="card-btn"
                                        onclick="redirectToLanguage()">Language</button>
                                    <button type="button" class="card-btn">Content language</button>
                                    <button type="button" class="card-btn">Sound effects</button>
                                    <button type="button" class="card-btn">Showing profile photos</button>
                                    <button type="button" class="card-btn">Feed preferences</button>
                                    <button type="button" class="card-btn">People you unfollowed</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Syncing Options</h5>
                                    <hr>
                                    <button type="button" class="card-btn">Sync calendar</button>
                                    <button type="button" class="card-btn">Sync contacts</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Subscriptions & Payments</h5>
                                    <hr>
                                    <button type="button" class="card-btn" onclick="redirectToSubs()">Upgrade for
                                        Free</button>
                                    <button type="button" class="card-btn" onclick="redirectToBilling()">View purchase
                                        history</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Account Management</h5>
                                    <hr>
                                    <button type="button" class="card-btn">Hibernate account</button>
                                    <button type="button" class="card-btn" onclick="redirectToProfileClose()">Close
                                        account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of cards -->
                </div>
            </div>

        </div>

    </div>

    <script src="./../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../assets/js/sidebarmenu.js"></script>
    <script src="./../../../assets/js/app.min.js"></script>
    <script src="./../../../assets/libs/simplebar/dist/simplebar.js"></script>
    <!-- <script src="./../../../View/back_office/finition.js"></script> -->


    <script>
        function redirectToProfileEdit() {
            var profileId = getProfileIdFromUrl();
            var url = "./settings_privacy/edit-profile.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function redirectToProfileClose() {
            var profileId = getProfileIdFromUrl();
            var url = "./settings_privacy/close_account.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function redirectToLanguage() {
            var profileId = getProfileIdFromUrl();
            var url = "./settings_privacy/language_settings.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function redirectToAppearence() {
            var profileId = getProfileIdFromUrl();
            var url = "./settings_privacy/appearance_settings.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function redirectToSubs() {
            var profileId = getProfileIdFromUrl();
            var url = "./subscription/subscriptionCards.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function redirectToBilling() {
            var profileId = getProfileIdFromUrl();
            var url = "./settings_privacy/billing-profile.php?profile_id=" + profileId;
            window.location.href = url;
        }

        function getProfileIdFromUrl() {
            // Get the current URL
            var url = window.location.href;
            // Extract the profile_id parameter value from the URL
            var profileId = url.split('profile_id=')[1];
            // Return the extracted profile ID
            return profileId;
        }
    </script>

    <?php
    include './../jobs management/chatbot.php';
    ?>
    <script src="./../../../front office assets/js/chatbot.js"></script>

</body>

</html>