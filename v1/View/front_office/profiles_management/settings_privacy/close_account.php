<?php
require_once __DIR__ . '/../../../../Controller/profileController.php';
include_once __DIR__ . '/../../../../Controller/user_con.php';

// Check if the request method is GET and if id_emp is set in the URL
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

    // Create an instance of the controller
    $profileController = new ProfileC();

    // Get profile ID from the URL
    $profile_id = $profileController->getProfileIdByUserId($user_id);

    // Get the profilee details by ID
    $profile = $profileController->getProfileById($profile_id);

    // Check if profile is set and not null

    // profilee details are available, proceed with displaying the form
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Settings & Privacy</title>
        <link rel="shortcut icon" type="image/png" href="../../../../assets/images/logos/HireUp_icon.ico" />
        <link rel="stylesheet" href="./../../../../assets/css/styles.min.css" />

        <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./../../../../front office assets/css/chatbot.css" />

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

            #subtitle,
            #paragraph {
                font-size: large;
            }
        </style>

        <!-- voice recognation -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

    </head>

    <body>

        <?php
        $block_call_back = 'false';
        $access_level = "else";
        include ('./../../../../View/callback.php')
            ?>


        <!--  Body Wrapper -->
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <!--  Main wrapper -->
            <div class="body-wrapper">

                <!-- Header Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
                    <div class="container-fluid">
                        <!-- Logo -->
                        <a class="navbar-brand ms-4" href="../../../../index.php">
                            <img  alt="HireUp" src="./../../../../assets/images/logos/HireUp_lightMode.png" style="width: 115px; height: 51px">
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
                                        href="../profile.php?profile_id=<?php echo $profile['profile_id'] ?>">Profile</a>
                                </li>

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
                                <li><a class="dropdown-header" href="../subscription/subscriptionCards.php">Try Premium for
                                        $0</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item"
                                        href="../profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id'] ?>">Settings
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


                <div class="container-fluid">
                    <div class="container-fluid">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Close Account</h5>
                                    </div>
                                    <div>
                                        <a href="javascript:history.go(-1);" class="btn btn-secondary"><i
                                                class="bi bi-arrow-left"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-4" id="subtitle"><b><?php echo $profile['profile_first_name']; ?></b>, we’re
                                    sorry to see you go</p>
                                <p class="mb-4" id="paragraph">Are you sure you want to close your account? You’ll lose your
                                    connections, messages, endorsements, and recommendations.</p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmationModal">Continue</button>
                            </div>
                        </div>

                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="confirmationModal" tabindex="-1"
                            aria-labelledby="confirmationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Before deleting your account:</p>
                                        <p>Your account will be archived for 30 days in the database before it is
                                            permanently deleted.</p>
                                        <p>Are you sure you want to proceed?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <a type="button" id="deleteAccountBtn" class="btn btn-danger">Delete Account</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../../../assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="../../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../../../../assets/js/sidebarmenu.js"></script>
        <script src="../../../../assets/js/app.min.js"></script>
        <script src="../../../../assets/libs/simplebar/dist/simplebar.js"></script>
        <script src="../../../../assets/js/finition.js"></script>

        <script>
            document.getElementById("deleteAccountBtn").addEventListener("click", function () {
                window.location.href = "./delete_account.php?profile_id=<?php echo $profile['profile_id']; ?>";                    
            });
        </script>

        <!-- voice recognation -->
        <script type="text/javascript"
            src="./../../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

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