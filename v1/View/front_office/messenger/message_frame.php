<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta http-equiv="refresh" content="2"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./../../../front office assets\images\HireUp_icon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./../profiles_management/assets/css/tailwindcss-colors.css">
    <link rel="stylesheet" href="./../profiles_management/assets/css/messaging.css">
    <title>HireUp Chat</title>
</head>

<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Include database connection and profile controller
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/friendshipsCon.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
include_once __DIR__ . '/../../../Controller/messaging_con.php';

$friendshipC = new FriendshipCon("friendships");

// Initialize profile controller
$profileController = new ProfileC();
$userC = new userCon("user");
$msgC = new MessagingCon("messages");

$user_id = "";
$user_profile_id = "";
if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);

    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

}

$profile_id = "";


if (isset($_GET['profile_id'])) {
    $profile_id = htmlspecialchars($_GET['profile_id']);
}

$profile = $profileController->getProfileById($profile_id);

if (!$profile) {
    exit();
}

?>

<body>

    <div>
        <div class="conversation-top" style="position: sticky !important;  top: 0 !important; z-index: 1000 !important; width: 100% !important;">
            <button type="button" class="conversation-back"><i class="ri-arrow-left-line"></i></button>
            <div class="conversation-user">
                <img class="conversation-user-image"
                    src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>" alt="">
                <div>
                    <div class="conversation-user-name">
                        <?php echo $profile['profile_first_name'] . " " . $profile['profile_family_name']; ?></div>
                    <!-- <div class="conversation-user-status online">online</div> -->
                </div>
            </div>
            
        </div>
        <div class="conversation-main">
            <ul class="conversation-wrapper">
                <!-- <div class="coversation-divider"><span>Today</span></div> -->
                <div class="coversation-divider"><span></span></div>

                <!-- <li class="conversation-item me">
                                <div class="conversation-item-side">
                                    <img class="conversation-item-image" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8cGVvcGxlfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" alt="">
                                </div>
                                <div class="conversation-item-content">
                                    <div class="conversation-item-wrapper">
                                        <div class="conversation-item-box">
                                            <div class="conversation-item-text">
                                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Amet natus repudiandae quisquam sequi nobis suscipit consequatur rerum alias odio repellat!</p>
                                                <div class="conversation-item-time">12:30</div>
                                            </div>
                                            <div class="conversation-item-dropdown">
                                                <button type="button" class="conversation-item-dropdown-toggle"><i class="ri-more-2-line"></i></button>
                                                <ul class="conversation-item-dropdown-list">
                                                    <li><a href="#"><i class="ri-share-forward-line"></i> Forward</a></li>
                                                    <li><a href="#"><i class="ri-delete-bin-line"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conversation-item-wrapper">
                                        <div class="conversation-item-box">
                                            <div class="conversation-item-text">
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque, tenetur!</p>
                                                <div class="conversation-item-time">12:30</div>
                                            </div>
                                            <div class="conversation-item-dropdown">
                                                <button type="button" class="conversation-item-dropdown-toggle"><i class="ri-more-2-line"></i></button>
                                                <ul class="conversation-item-dropdown-list">
                                                    <li><a href="#"><i class="ri-share-forward-line"></i> Forward</a></li>
                                                    <li><a href="#"><i class="ri-delete-bin-line"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> -->
                <?php
                /*$stringArray = array(
                    "Lorem ipsum dolor sit amet",
                    "consectetur adipiscing elit",
                    "sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
                    "i neeeeed sleeep"
                );

                 echo $msgC->generateMessageMeHTML($user_profile_id, $stringArray, "01:24 AM"); 
                 ?>

                <?php 
                $stringArray = array(
                    "Lorem ipsum dolor sit amet",
                    "consectetur adipiscing elit",
                    "sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
                    "i neeeeed sleeep"
                );

                 echo $msgC->generateMessageOtherHTML($profile_id, $stringArray, "01:24 AM"); */

                $msgC->generateConversationHTML($user_profile_id, $profile_id);

                ?>


            </ul>
        </div>
    </div>


    <script src="./../profiles_management/assets/js/messaging.js"></script>

    <script>
        // Automatically reload the page every 2 seconds
        setInterval(function () {
            location.reload();
        }, 3000);
    </script>

    <script>
        // Wait for the page to fully load
        window.onload = function () {
            // Scroll down to the bottom of the page
            window.scrollTo(0, document.body.scrollHeight);
        };
    </script>

</body>

</html>