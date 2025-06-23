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
// if (!isset($_GET['profile_id'])) {
//     header('Location: ../pages/404.php');
//     exit();
// }

// Include database connection and profile controller
require_once __DIR__ . '/../../../../Controller/profileController.php';
require_once __DIR__ . '/../../../../Controller/subscriptionControls.php'; // Include SubscriptionControls
include_once __DIR__ . '/../../../../Controller/user_con.php';

// Initialize profile controller
$profileController = new ProfileC();

// Initialize subscription controller
$subscriptionController = new SubscriptionControls();

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

// Get profile ID from the URL
$profile_id = $profileController->getProfileIdByUserId($user_id);

// Fetch profile data from the database
$profile = $profileController->getProfileById($profile_id);

// Fetch all subscriptions from the database
$subscriptions = $subscriptionController->getAllSubscriptions();

// Check if profile data is retrieved successfully
// if (!$profile) {
//     header('Location: ../../pages/404.php');
//     exit();
// }

// Now you can use the fetched $subscriptions array to populate subscription cards in your HTML
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./../../../../front office assets/images/HireUp_icon.ico" />

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css'>
    <link rel="stylesheet" href="https://cdn.lineicons.com/3.0/lineicons.css">
    <link rel="stylesheet" href="../assets/css/subscriptionCards.css">

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./../../../../front office assets/css/chatbot.css" />

    <title>HireUp Subscriptions</title>

    <style>
        /* Style for the fixed-top class when scrolling */
        .navbar.fixed-top-scroll {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: top 0.3s;
        }

        /* Style for the navbar when scrolling */
        .navbar-scroll {
            top: -60px;
            /* Height of the navbar */
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

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top-scroll">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand ms-4" href="../../../../index.php">
                <img class="logo-img" alt="HireUp">
            </a>

            <!-- Profile Dropdown -->
            <div class="dropdown" style="margin-right: 70px;">
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

                    <li><a class="dropdown-item" href="./../../../../View/front_office/jobs management/career_explorers.php">Career Explorers</a></li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-header" href="./subscriptionCards.php">Try Premium for $0</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item"
                            href="../profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id'] ?>">Settings
                            & Privacy</a></li>
                    <li><a class="dropdown-item" href="#">Help</a></li>
                    <li><a class="dropdown-item" href="../settings_privacy/language_settings.php">Language</a></li>
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



    <section class="price_plan_area mt-4" id="pricing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-lg-6">
                    <!-- Section Heading-->
                    <div class="section-heading text-center wow fadeInUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <h6>Charting Your Course to Success</h6>
                        <h3>Let's find a way together</h3>
                        <p>Exploring Opportunities Hand in Hand.</p>
                        <div class="line"></div>
                    </div>
                </div>
            </div>

            <?php
            require_once __DIR__ . '/../../../../Model/subscriptionModel.php';
            require_once __DIR__ . '/../../../../Controller/subscriptionControls.php';

            // Create an instance of SubscriptionControls
            $subscriptionController = new SubscriptionControls();

            // Get all subscriptions
            $subscriptions = $subscriptionController->getAllSubscriptions();

            // Group subscriptions by card type: Basic, Advanced, and Premium
            $groupedSubscriptions = array();
            foreach ($subscriptions as $subscription) {
                $cardType = $subscription['card'];
                $groupedSubscriptions[$cardType][] = $subscription;
            }

            // Loop through grouped subscriptions for each card type
            foreach ($groupedSubscriptions as $cardType => $groupedSubscription) {
                ?>
                <div class="container">
                    <div class="row justify-content-center">
                        <?php foreach ($groupedSubscription as $subscriptionIndex => $subscription): ?>
                            <?php
                            // Access properties of SubscriptionModel object
                            $plan_name = $subscription['plan_name'];
                            $description = $subscription['description'];
                            $price = $subscription['price'];
                            $duration = $subscription['duration'];
                            $subscription_id = $subscription['subscription_id'];
                            $card = $subscription['card']; // Get the card attribute
                    
                            // Fetch features for the current subscription
                            $features = $subscriptionController->getSubscriptionFeatures($subscription_id);
                            ?>
                            <div class="col-12 col-sm-8 col-md-6 col-lg-5">
                                <div class="single_price_plan <?php echo strtolower($card); ?> wow fadeInUp"
                                    data-wow-delay="0.2s">
                                    <!-- Set the class based on the card attribute -->
                                    <?php if ($card === "Basic"): ?>
                                        <div class="side-shape"><img src="https://bootdey.com/img/popular-pricing.png" alt=""></div>
                                    <?php elseif ($card === "Advanced"): ?>
                                        <div class="side-shape"><img src="https://bootdey.com/img/popular-pricing.png" alt=""></div>
                                    <?php elseif ($card === "Premium"): ?>
                                        <div class="side-shape"><img src="https://bootdey.com/img/popular-pricing.png" alt=""></div>
                                    <?php endif; ?>
                                    <div class="title">
                                        <h3><?php echo $plan_name; ?></h3>
                                        <p style="font-size: medium;"><?php echo $description; ?></p>
                                        <div class="line"></div>
                                    </div>
                                    <div class="price">
                                        <h4><?php echo $price; ?></h4>
                                    </div>
                                    <div class="description">
                                        <p><i class="lni lni-checkmark-circle"></i>Duration: <b><?php echo $duration; ?></b></p>
                                        <p><i class="lni lni-checkmark-circle"></i><b><?php echo $subscriptionIndex % 3 === 0 ? 'Starter Kit' : ($subscriptionIndex % 3 === 1 ? 'Exclusive Access' : 'Unlimited'); ?>
                                                Features</b></p>
                                        <?php if (!empty($features)): ?>
                                            <?php foreach ($features as $feature): ?>
                                                <p><i class="lni lni-checkmark-circle"></i><?php echo $feature['feature_name']; ?></p>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <form method="post" action="checkout.php">
                                        <div><input type="hidden" name="subscription_id"
                                                value="<?php echo $subscription_id; ?>">
                                            
                                            <?php if ($profile['profile_subscription'] != $subscription_id) { ; ?>
                                            <button type="submit"
                                                class="btn btn-<?php echo $subscriptionIndex % 3 === 0 ? 'success' : ($subscriptionIndex % 3 === 1 ? 'warning' : 'info'); ?> btn-2"
                                                href="#">Get Started</button>
                                            <?php } else { ; ?>
                                            <button type="button"
                                                class="btn btn-warning btn-2"
                                                href="#" disabled>Subscribed</button>
                                            <button type="button"
                                                class="btn btn-danger btn-2"
                                                onclick="window.location.href = 'cancel_sub.php'">cancel</button>
                                            <?php } ; ?>


                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
            }
            ?>






        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-dark text-center text-white py-3 mt-4">
        <div class="container">
            <p>&copy; 2024 All rights reserved to <b>be.net</b></p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js'></script>

    <script>
        var dropdown = document.querySelector('.dropdown');
        var dropdownMenu = document.querySelector('.dropdown-menu');

        dropdown.addEventListener('click', function () {
            dropdownMenu.classList.toggle('show');
        });

        // Close the dropdown menu when clicking outside
        window.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

<?php
    include './../../jobs management/chatbot.php';
  ?>
  <script src="./../../../../front office assets/js/chatbot.js"></script>


</body>

</html>