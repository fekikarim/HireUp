<?php

require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/friendshipsCon.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
include_once __DIR__ . '/../../../Controller/articleC.php';
include_once __DIR__ . '/../../../Controller/commentaireC.php';
include_once __DIR__ . '/../../../Controller/post_openion_con.php';
include_once __DIR__ . '/../../../Controller/meeting_con.php';
include_once __DIR__ . '/../../../Controller/JobC.php';
include_once __DIR__ . '/../../../Controller/messaging_con.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


$userC = new userCon("user");
$friendshipC = new FriendshipCon("friendships");
$NotificationCon = new NotificationCon("notifications");
$post_openion_con = new PostOpinionCon("post_openion");
$profileController = new ProfileC();
$articleC = new ArticleC();
$commentaireC = new CommentaireC();
$meetingC = new MeetingCon("meetings");
$jobC = new JobController();
$messageC = new MessagingCon('messages');


$guesst = false;
$are_freinds = "";
$inv_sender = "";
$friendship_id = "";
$friend_requests = array();
$user_friends = array();
$profile_friends = array();
$user_notifications = array();
$user_meetings = array();

$user_friends_nb = 0;
$profile_friends_nb = 0;
$user_notifications_nb = 0;
$user_unseen_notifications_nb = 0;
$user_meetings_nb = 0;

$user_id = '';
$current_profile_id = '';
$user_profile_id = '';

//get user_profile id
if (isset($_SESSION['user id'])) {
  $user_id = htmlspecialchars($_SESSION['user id']);
  $user_profile_id = $profileController->getProfileIdByUserId($user_id);
}

//get current profile id
if (isset($_GET['profile_id']) && (!empty($_GET['profile_id']))) {
  $current_profile_id = htmlspecialchars($_GET['profile_id']);
}

//if the profile_id at the link is empty
if ((!isset($_GET['profile_id'])) || (empty($_GET['profile_id'])) || (htmlspecialchars($_GET['profile_id']) == "")) {
  $current_profile_id = $user_profile_id;
}

//if the profile_id at the link is wrong
if ($profileController->profileExists($current_profile_id) == false && (isset($_SESSION['user id']))) {
  header('Location: ./../404/404.php');
  exit();
}

$block_call_back = 'false';
$access_level = "profile";
include ('./../../../View/callback.php');



//get user role
$user_role = $userC->get_user_role_by_id($user_id);

//get the user profile friends
$friend_requests = $friendshipC->getFriendRequests($user_profile_id);
$friend_requests_nb = count($friend_requests);
$user_friends = $friendshipC->getFriends($user_profile_id);
$user_friends_nb = count($user_friends);

//get the current profile friends
$profile_friends = $friendshipC->getFriends($current_profile_id);
$profile_friends_nb = count($profile_friends);

//Notifications
$user_notifications = $NotificationCon->listNotificationsByReceiverIdOrderedByDateTime($user_profile_id);
$user_notifications_nb = count($user_notifications);
$user_unseen_notifications_nb = $NotificationCon->countUnseenNotifications($user_notifications);

//meetings
$user_meetings = $meetingC->getProfileMeetings($user_profile_id);
$user_meetings_nb = count($user_meetings);
$user_unfinished_meetings_nb = $meetingC->countUnfinishedMeetings($user_meetings);

//friends
$profile_friends = $friendshipC->getFriends($current_profile_id);
$profile_friends_nb = count($profile_friends);

//messages
$user_messages = $messageC->getLastMessages($user_profile_id);
$user_messages_nb = $messageC->countUnseenMessages($user_messages, $user_profile_id);

if ($current_profile_id != $user_profile_id) {
  $guesst = true;

  $are_freinds = $friendshipC->getFriendshipStatus($current_profile_id, $user_profile_id);
  if ($friendshipC->getFriendshipSender($current_profile_id, $user_profile_id) == $user_profile_id) {
    $inv_sender = 'you';
  }

  if ($are_freinds == 'pending' || $are_freinds == 'friends') {
    $friendship_id = $friendshipC->getFriendshipId($current_profile_id, $user_profile_id);
  }
}

//fetch the profiles data
$profile = $profileController->getProfileById($current_profile_id);
$user_profile = $profileController->getProfileById($user_profile_id);

//fetch posts
$posts = $articleC->listArticlesByProfileId($current_profile_id);

//fetch subscription
$subs_type = array(
  "1-ADVANCED-SUBS" => "advanced",
  "1-BASIC-SUBS" => "basic",
  "1-PREMIUM-SUBS" => "premium",
  "else" => "limited"
);

$current_profile_sub = "";
if (array_key_exists($profile['profile_subscription'], $subs_type)) {
  // If it exists, return the corresponding value
  $current_profile_sub = $subs_type[$profile['profile_subscription']];
} else {
  // If not, return 'bb'
  $current_profile_sub = $subs_type['else'];
}



?>

<?php

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

include_once __DIR__ . '/../../../Controller/JobC.php';
$jobController = new JobController();
$user_infos_string = $jobController->getUserLocation();
$user_infos = json_decode($user_infos_string, true);
$country_code = strtolower($user_infos['countryCode']);

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" type="image/png" href="./../../../front office assets\images\HireUp_icon.ico" />
  <title>HireUp Profile</title>

  <link rel="stylesheet" href="./assets/css/style.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/profile_style.css">
  <link rel="stylesheet" href="./assets/css/search_input.css">
  <link rel="stylesheet" href="./assets/css/action_sections.css">

  <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />


  <style>
    /* Update Post Popup */
    #updatePostModal,
    #editCommentModal {
      display: none;
      /* Hide modal by default */
      position: fixed;
      /* Position modal fixed to the viewport */
      z-index: 9999;
      /* Set a high z-index value to ensure it's displayed on top */
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
      /* Semi-transparent background */
      padding-top: 60px;
      /* Add top padding to center modal vertically */
    }

    .comment-conteiner-receiver {
      display: flex;
      align-items: flex-start;
      margin-bottom: 10px;
    }

    .profile-post-pic {
      width: 50px;
      /* Adjust as needed */
      height: 50px;
      /* Adjust as needed */
      border-radius: 50%;
      /* To make the image circular */
      margin-right: 10px;
      /* Adjust spacing between profile picture and message */
    }

    .message {
      background-color: #e1e1e1;
      padding: 10px;
      border-radius: 5px;
      max-width: 600px;
      min-width: 200px;
    }

    .time-right {
      margin-left: 60px;
      /* Adjust to align the date with the message content */
      display: block;
      /* Ensure the date is displayed below the message content */
    }

    .comment-section .comments-container .comment-conteiner-receiver .comment-menu {
      padding-left: 3.5px;
      padding-right: 3.5px;
    }

    .comment-section .comments-container .comment-conteiner-receiver .comment-menu:hover {
      background-color: rgba(216, 217, 218, 0.75);
      border-radius: 50%;
      padding-left: 3.5px;
      padding-right: 3.5px;
    }



    .popup-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
      z-index: 9999;
      padding: 20px;
      max-width: 400px;
    }

    .popup-content {
      text-align: center;
    }

    .popup-header {
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #ccc;
    }

    .popup-header h2 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    .popup-close {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      font-size: 24px;
      color: #999;
    }

    .popup-body {
      padding: 20px 0;
    }

    .popup-link {
      display: block;
      margin-bottom: 10px;
      color: #007bff;
      text-decoration: none;
      font-size: 18px;
    }

    .popup-link i {
      margin-right: 10px;
    }

    .popup-link:hover {
      text-decoration: underline;
    }

    .popup-body p {
      margin: 10px 0;
      font-size: 16px;
      color: #666;
    }
  </style>

  <style>
    .post-image-container {
      text-align: center;
    }

    .post-image-container img {
      max-width: 300px;
      height: auto;
    }

    .hidden {
      display: none;
    }


    #notificationDropdown .badge,
    #followRequestsDropdown .badge,
    #calendarsDropdown .badge {
      position: absolute;
      top: -4px;
      right: 15px;
      padding: 4px 7px;
      border-radius: 50%;
      background-color: red;
      color: white;
      font-size: 14px;
    }
  </style>


  <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

  <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

</head>

<body style="min-width: 735px">

  <!-- Header Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand ms-4" href="../../../index.php">
        <img class="logo-img" alt="HireUp">
      </a>

      <div class="search-container">
        <form class="form me-auto">
          <label for="search">
            <input class="input" type="text" required="" placeholder="HireUp Search..." id="search"
              onkeyup="searchProfiles()">
            <div class="search">
              <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                <g>
                  <path
                    d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                  </path>
                </g>
              </svg>
            </div>
            <button class="close-btn" type="reset" onclick="closeSearchProfiles()">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
            </button>
          </label>
        </form>

        <div id="profiles-container" class="profiles-container">

          <!-- <div class="profile-card">
            <img src="${profile.imageUrl}" alt="${profile.name}">
            <div class="profile-info">
              <div class="profile-name">${profile.name}</div>
              <div class="profile-job">${profile.job}</div>
            </div>

            <img src="${profile.imageUrl}" alt="${profile.name}">
            <div class="profile-info">
              <div class="profile-name">${profile.name}</div>
              <div class="profile-job">${profile.job}</div>
            </div>

            <img src="${profile.imageUrl}" alt="${profile.name}">
            <div class="profile-info">
              <div class="profile-name">${profile.name}</div>
              <div class="profile-job">${profile.job}</div>
            </div>
          </div> -->

        </div>
      </div>

      <!-- Profile Dropdown and Buttons Bar -->
      <div class="dropdown d-flex align-items-center">
        <!-- Buttons Bar -->
        <div class="d-flex">

          <!-- To Do List Dropdown -->
          <?php if ($current_profile_sub != "limited") { ?>
            <div class="dropdown">
              <button class="btn rounded_button_bar me-3" id="TodolistDropdown" data-bs-toggle="dropdown"
                onclick="window.open('./../todo list/index.php', '_blank')">
                <i class="fa-solid fa-list-check"></i>
              </button>
            </div>
          <?php } ?>

          <!-- calendar Dropdown -->
          <div class="dropdown">
            <button class="btn rounded_button_bar me-3" id="calendarsDropdown" data-bs-toggle="dropdown">
              <i class="fa fa-calendar"></i>

              <?php if ($user_unfinished_meetings_nb > 0) { ?>
                <span id="cal_nb" class="badge"><?php echo $user_unfinished_meetings_nb; ?></span>
              <?php } ?>

            </button>

            <!-- Follow Requests Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="calendarsDropdown" id="calendarDropdown">
              <!-- Dropdown Header -->
              <h5 class="dropdown-header">Calendar</h5>
              <hr>

              <?php
              $max_items = 3;
              $count = 0;
              ?>

              <?php
              foreach ($user_meetings as $user_meeting) {
                $meeting = $meetingC->getMeeting($user_meeting['meeting_id']);
                $current_meeting_job = $jobC->getJobById($meeting['meeting_job_id']);
                if ($count < $max_items) {
                  $meeting_user_role = $user_meeting['profile_role'];
                  if ($meeting_user_role == 'moderator') {
                    $meeting_link = './../meeting/make_moderator_meeting.php?meeting_id=' . $user_meeting['meeting_id'] . '&profile_id=' . $user_meeting['profile_id'];
                  } else {
                    $meeting_link = './../meeting/make_non_moderator_meeting.php?meeting_id=' . $user_meeting['meeting_id'] . '&profile_id=' . $user_meeting['profile_id'];
                  }
                  ?>
                  <li>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo $meeting_link; ?>" target="_blank">
                      <img src="data:image/jpeg;base64,<?= base64_encode($current_meeting_job['job_image']) ?>"
                        alt="Job Photo" class="rounded-circle me-2" width="30" height="30">
                      <div>
                        <p>Meeting at <strong><?php echo $meeting['meeting_at']; ?></strong></p>
                      </div>
                    </a>
                  </li>

                  <?php
                }
                $count++;
              }
              ?>

              <?php
              if ($count > 0) {
                ?>
                <li>
                  <a class="dropdown-item text-center" href="../calendar/calendar.php" target="_blank">
                    Show Calendar
                  </a>
                </li>

                <?php
              } else {
                ?>

                <li>
                  <p class="dropdown-item text-center">
                    You don't have any meetings.
                  </p>
                  <a class="dropdown-item text-center" href="../calendar/calendar.php" target="_blank">
                    Show Calendar
                  </a>
                </li>

                <?php
              }
              ?>


            </ul>

          </div>

          <!-- Follow Requests Dropdown -->
          <div class="dropdown">
            <button class="btn rounded_button_bar me-3" id="followRequestsDropdown" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="fa fa-user-plus"></i>
              <?php if ($friend_requests_nb > 0) { ?>
                <span id="follow_nb" class="badge"><?php echo $friend_requests_nb; ?></span>
              <?php } ?>


            </button>
            <!-- Follow Requests Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="followRequestsDropdown"
              id="followRequestDropdown">
              <!-- Dropdown Header -->
              <h5 class="dropdown-header">Follow Requests</h5>
              <hr>

              <!-- Example Follow Request -->
              <?php if (count($friend_requests) > 0) { ?>
                <?php foreach ($friend_requests as $follow_request) {
                  $current_profile = $profileController->getProfileById($follow_request['profile_id']);
                  ?>
                  <li>
                    <div class="dropdown-item d-flex align-items-center">

                      <a class="dropdown-item d-flex align-items-center"
                        href="profile.php?profile_id=<?php echo $current_profile['profile_id']; ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($current_profile['profile_photo']) ?>"
                          alt="Profile Photo" class="rounded-circle me-2" width="30" height="30">
                        <div>
                          <strong><?php echo $current_profile['profile_first_name'] . ' ' . $current_profile['profile_family_name']; ?></strong>
                          wants to follow you. &nbsp;&nbsp;
                        </div>
                      </a>

                      <div class="ms-auto">
                        <button class="btn btn-sm btn-primary rounded-3 me-1"
                          onclick="window.location.href = './../friendships/accept_friendship.php?profile_id=<?php echo $current_profile['profile_id']; ?>&id=<?php echo $follow_request['friendship_id']; ?>';"><i
                            class="fas">&#xF055;</i></button>
                        <button class="btn btn-sm btn-danger rounded-3"
                          onclick="window.location.href = './../friendships/remove_friendship.php?profile_id=<?php echo $current_profile['profile_id']; ?>&id=<?php echo $follow_request['friendship_id']; ?>';"><i
                            class="fas">&#xf057;</i></button>
                      </div>

                    </div>

                  </li>

                <?php } ?>
              <?php } else { ?>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div>
                      You currently have no pending follow requests.
                    </div>
                  </a>
                </li>
              <?php } ?>
              <!-- Add more follow requests here -->
            </ul>
          </div>

          <!-- Messaging Button -->
          <button class="btn rounded_button_bar me-3" id="messaging_container"
            onclick="window.location.href = './../messenger/messaging.php';">

            <i class="fa-solid fa-comment"></i>

            <?php if ($user_messages_nb > 0) { ?>
              <span id="msg_nb" class="badge" style="position: absolute;
                          top: -4px;
                          right: 160px;
                          padding: 4px 6px;
                          border-radius: 50%;
                          background-color: red;
                          color: white;
                          font-size: 14px;"><?php echo $user_messages_nb; ?>
              </span>

            <?php } ?>

          </button>

          <!-- Notification Dropdown -->
          <div class="dropdown">
            <button class="btn rounded_button_bar me-3" id="notificationDropdown" data-bs-toggle="dropdown"
              aria-expanded="false" onclick="updateNotifications('<?php echo $user_profile_id; ?>', 'seen')">
              <i class="fa-solid fa-bell"></i>
              <?php if ($user_unseen_notifications_nb > 0) { ?>
                <span id="notif_nb" class="badge"><?php echo $user_unseen_notifications_nb; ?></span>
              <?php } ?>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
              <h5 class="dropdown-header">Notifications</h5>
              <hr>
              <!-- Follow Requests Items (Populate dynamically with PHP or JavaScript) -->
              <?php if ($user_notifications_nb > 0) { ?>
                <?php foreach ($user_notifications as $notification) {
                  $current_profile = $profileController->getProfileById($notification['sender_id']);
                  ?>
                  <?php if ($notification['from_hire_up'] != 'true') { ?>

                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="<?php echo $notification['link']; ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($current_profile['profile_photo']) ?>"
                          alt="Profile Photo" class="rounded-circle me-2" width="30" height="30">
                        <div>
                          <strong><?php echo $current_profile['profile_first_name'] . ' ' . $current_profile['profile_family_name']; ?></strong>
                          <?php echo $notification['content']; ?>. &nbsp;&nbsp;
                        </div>
                      </a>
                    </li>

                  <?php } else { ?>

                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="<?php echo $notification['link']; ?>">
                        <img src="<?= './../../../front office assets/images/HireUp_icon.png' ?>" alt="Profile Photo"
                          class="rounded-circle me-2" width="30" height="30">
                        <div>
                          <strong><?php echo $notification['sender_id']; ?></strong>
                          <?php echo $notification['content']; ?>. &nbsp;&nbsp;
                        </div>
                      </a>
                    </li>

                  <?php } ?>
                <?php } ?>
              <?php } else { ?>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <div>
                      You currently have no notifications.
                    </div>
                  </a>
                </li>
              <?php } ?>
              <!-- <li><a class="dropdown-item" href="#">Jane Smith</a></li> -->
              <!-- Add more follow requests items here -->
            </ul>

          </div>

        </div>


        <div class="dropdown">
          <!-- Profile Photo -->
          <a href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false"
            class="d-flex align-items-center justify-content-center mx-3" style="height: 100%;">
            <img src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>" alt="Profile Photo"
              class="rounded-circle" width="50" height="50">
            <span class="iconify ml-0 mb-5" data-icon="flag:<?php echo $country_code; ?>-4x3"></span>
          </a>


          <!-- Profile Dropdown Menu -->
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <!-- Dropdown Header -->
            <h5 class="dropdown-header">Account</h5>
            <!-- Profile Link -->
            <li><a class="dropdown-item" href="./profile.php"><i class="fas fa-id-card-alt"></i> Profile</a></li>
            <?php
            if ($user_role == 'admin') {
              ?>
              <li><a class="dropdown-item text-success" href="./../../../View/back_office/main dashboard"><i
                    class="fas fa-calculator"></i> Dashboard</a>
              </li>
              <?php
            }
            ?>

            <li><a class="dropdown-item" href="./../../../View/front_office/jobs management/career_explorers.php">
                <i class="fas fa-user-tie"></i> Career Explorers</a></li>
            <!-- Divider -->
            <li>
              <hr class="dropdown-divider">
            </li>
            <!-- Try Premium -->
            <?php if ($current_profile_sub == "limited") { ?>
            <li><a class="dropdown-header text-primary"
                href="./subscription/subscriptionCards.php?profile_id=<?php echo $profile['profile_id'] ?>">Try Premium
                for $0</a></li>
            <?php } else {?>
              <li><a class="dropdown-header text-primary"
                href="./subscription/subscriptionCards.php?profile_id=<?php echo $profile['profile_id'] ?>">Upgrade Plan</a></li>
            <?php } ?>
            <!-- Divider -->
            <li>
              <hr class="dropdown-divider">
            </li>
            <!-- Settings & Privacy -->
            <li><a class="dropdown-item"
                href="./settings_privacy/edit-profile.php?profile_id=<?php echo $profile['profile_id'] ?>">
                <i class="fas fa-cogs"></i> Settings & Privacy</a></li>
            <!-- Help Link -->
            <li><a class="dropdown-item" href="./../../../about.php"><i class="fas fa-question-circle"></i> Help</a>
            </li>
            <!-- Language Link -->
            <li><a class="dropdown-item" href="./settings_privacy/language_settings.php"><i class="fas fa-language"></i>
                Language</a></li>
            <!-- Divider -->
            <li>
              <hr class="dropdown-divider">
            </li>
            <!-- Manage Header -->
            <h5 class="dropdown-header">Manage</h5>
            <!-- Jobs Link -->
            <li><a class="dropdown-item" href="./../jobs management/jobs_list.php"><i class="fas fa-briefcase"></i>
                Jobs</a></li>
            <li><a class="dropdown-item" href="./../interests/interests.php"><i class="fa fa-heart"></i>
                Interests</a></li>
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
            <li><a class="dropdown-item" href="./../Sign In & Sign Up/logout.php"><i class="fas fa-sign-out-alt"></i>
                Logout</a></li>
          </ul>
        </div>
      </div>

    </div>
  </nav>
  <!-- End Header Navbar -->



  <div class="container">
    <div class="card overflow-hidden">
      <div class="card-body p-0">
        <!-- Profile cover photo -->
        <div class="profile-cover">
          <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_cover']) ?>" alt="Profile Cover">
        </div>
        <!-- -->

        <!-- Profile content -->
        <div class="container">
          <div class="row align-items-center">
            <!-- Profile social links -->
            <!-- Profile links and Add Story button -->
            <div class="col-lg-4 order-last d-flex align-items-center justify-content-center">
              <ul
                class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-end my-3 gap-3">
                <!-- Profile links -->
                <li class="position-relative">
                  <a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle"
                    href="https://www.facebook.com/profile.php?id=61557532202485"
                    style="width: 48px; height: 48px; text-decoration: none;" target="_blanck">
                    <i class="fab fa-facebook"></i>
                  </a>
                </li>
                <li class="position-relative">
                  <a class="text-white bg-secondary d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle"
                    href="https://www.instagram.com/hire.up.tn/"
                    style="width: 48px; height: 48px; text-decoration: none;" target="_blanck">
                    <i class="fab fa-instagram"></i>
                  </a>
                </li>
                <li class="position-relative">
                  <a class="text-white bg-danger d-flex align-items-center justify-content-center p-2 fs-4 rounded-circle"
                    href="https://youtu.be/VMWyU_d40Jo" style="width: 48px; height: 48px; text-decoration: none;"
                    target="_blanck">
                    <i class="fab fa-youtube"></i>
                  </a>
                </li>

              </ul>
            </div>

            <div class="col-lg-4 mt-n5 order-lg-2 order-1 d-flex flex-column align-items-center justify-content-center">
              <!-- Profile photo -->
              <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle mb-3"
                style="width: 150px; height: 150px;">
                <div
                  class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                  style="width: 140px; height: 140px;">
                  <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>" alt="Profile Photo"
                    class="w-100 h-100">
                </div>
              </div>
              <!-- Profile name -->
              <div class="text-center">
                <!-- Profile first name + family name -->
                <h5 class="fs-5 mb-0 fw-bold"><?= $profile['profile_first_name'] ?>
                  <?= $profile['profile_family_name'] ?>

                  <img src="./../../../front office assets/img/sub imgs/<?php echo $current_profile_sub; ?>.png"
                    alt="Profile Subscription" class="me-3" width="20" height="20" />

                </h5>
                <!-- -->
                <!-- Profile current position -->
                <div>
                  <p class="mb-0 fs-4"><?= $profile['profile_current_position'] ?></p>
                </div>
                <!-- -->
              </div>
            </div>


            <!-- Add Story button -->
            <?php
            if ($guesst) {
              ?>
              <div class="col-lg-4 order-last">
                <div class="text-center mt-3 mt-lg-0">
                  <?php
                  if ($are_freinds == 'friends') {
                    ?>
                    <button class="btn btn-secondary btn-sm rounded-pill px-4 py-2"
                      onclick="window.location.href = './../friendships/remove_friendship.php?profile_id=<?php echo $current_profile_id; ?>&id=<?php echo $friendship_id; ?>';"><i
                        class="fa fa-check me-2"></i><b>Following</b></button>
                    <?php
                  } else if ($are_freinds == 'pending') {
                    ?>
                      <?php
                      if ($inv_sender == 'you') {
                        ?>
                        <button class="btn btn-primary btn-sm rounded-pill px-4 py-2"
                          onclick="window.location.href = './../friendships/remove_friendship.php?profile_id=<?php echo $current_profile_id; ?>&id=<?php echo $friendship_id; ?>';"><i
                            class="fas me-2" style="font-size: 15px">&#xf110;</i><b>Pendding</b></button>
                      <?php
                      } else {
                        ?>
                        <button class="btn btn-accept-user btn-sm rounded-pill px-4 py-2"
                          onclick="window.location.href = './../friendships/accept_friendship.php?profile_id=<?php echo $current_profile_id; ?>&id=<?php echo $friendship_id; ?>';"><i
                            class="fas me-2">&#xF055;</i><b>Accept</b></button>
                        <button class="btn btn-refuse-user btn-sm rounded-pill px-4 py-2"
                          onclick="window.location.href = './../friendships/remove_friendship.php?profile_id=<?php echo $current_profile_id; ?>&id=<?php echo $friendship_id; ?>';"><i
                            class="fas me-2">&#xf057;</i><b>Refuse</b></button>
                      <?php
                      }
                      ?>
                    <?php
                  } else {
                    ?>
                      <button class="btn btn-primary btn-sm rounded-pill px-4 py-2"
                        onclick="window.location.href = './../friendships/add_friendship.php?profile_id=<?php echo $current_profile_id; ?>';"><i
                          class="fa fa-plus me-2"></i><b>Follow</b></button>
                    <?php
                  }
                  ?>
                </div>
              </div>
              <?php
            }
            ?>
          </div>
        </div>


        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <ul class="nav nav-pills user-profile-tab justify-content-center mt-2 bg-light-info rounded-2"
                id="pills-tab" role="tablist">
                <!-- <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                    role="tab" aria-controls="pills-profile" aria-selected="true">
                    <i class="fas fa-user"></i>
                    <span class="d-none d-md-block ms-3">Profile</span>
                  </button>
                </li> -->
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-followers-tab" data-bs-toggle="pill" data-bs-target="#pills-followers" type="button"
                    role="tab" aria-controls="pills-followers" aria-selected="false" tabindex="-1"
                    onclick="window.location.href = './../jobs management/jobs_list.php' ;">
                    <i class="fas fa-user-tie"></i>
                    <span class="d-none d-md-block ms-3">Jobs</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-friends-tab" data-bs-toggle="pill" data-bs-target="#pills-friends" type="button"
                    role="tab" aria-controls="pills-friends" aria-selected="false" tabindex="-1"
                    onclick="window.location.href = './../ads/view_ads.php' ;">
                    <i class="fas fa-ad"></i>
                    <span class="d-none d-md-block ms-3">Ads</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button"
                    role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1"
                    onclick="window.location.href = './../reclamation/rec_list.php' ;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="d-none d-md-block ms-3">Report</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button"
                    role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1"
                    onclick="window.location.href = './../../../about.php' ;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span class="d-none d-md-block ms-3">About</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                    id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button"
                    role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1"
                    onclick="window.location.href = './../interests/interests.php' ;">
                    <i class="fa-solid fa-heart"></i>
                    <span class="d-none d-md-block ms-3">Interests</span>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </div>







    <div class="tab-content">
      <!-- Profile Tab -->
      <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
        tabindex="0">
        <!-- Create Post Section -->

        <?php if ($user_profile_id == $current_profile_id) { ?>

          <div class="row mb-3">
            <div class="col-md-8 offset-md-2">
              <div class="card">
                <div class="card-body">
                  <!-- Post Form -->
                  <form>
                    <div class="d-flex align-items-start">
                      <!-- Profile Picture -->
                      <img src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>"
                        alt="Profile Picture" class="rounded-circle me-3" width="40" height="40">
                      <!-- Description Input -->
                      <div class="mb-3 flex-grow-1">
                        <input type="text" class="form-control button-like-input" id="createPostInput"
                          placeholder="Start a post" onclick="openCreatePostModal()">
                      </div>
                    </div>
                    <!-- Buttons -->
                  </form>
                </div>
              </div>
            </div>
          </div>

        <?php } ?>

      </div>
    </div>

    <div class="row" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

      <!-- Left column for Profile Posts -->
      <div class="col-lg-8">
        <!-- Profile Posts -->
        <div class="row">
          <!-- Profile Picture Post -->
          <div class="col-md-12">
            <!-- Sample post card -->
            <?php
            
            $ads_spacing_counter = 0;
            foreach ($posts as $post) {
              $ads_spacing_counter++;
              if ($current_profile_sub == "limited") {
              if ($ads_spacing_counter > 3) {
                $ads_spacing_counter = 0;
                echo '<div class="card mb-3">';
                echo '<h5 style="margin-top: 15px; margin-left: 15px;">Ad</h5>';

                $add_type = "center";
                require __DIR__ . '/../../../View/front_office/ads/ads_containers.php';
                echo '</div>';
              }
            }
              ?>
              <?php
              $post_openion_data = $post_openion_con->countLikesDislikes($post['id']);
              $post_liked_nb = $post_openion_data['nblikes'];
              $post_disliked_nb = $post_openion_data['nbdislikes'];
              $is_liked_by_user_profile = $post_openion_con->checkReaction($user_profile_id, $post['id']);
              $post_profile = $profileController->getProfileById($post['auteur_id']);
              ?>
              <div class="card mb-3">
                <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                    <!-- Profile Picture -->
                    <a href="profile.php"><img
                        src="data:image/jpeg;base64,<?= base64_encode($post_profile['profile_photo']) ?>"
                        alt="Profile picture" class="profile-post-pic"></a>
                    <!-- Profile Name and Location -->
                    <div class="flex-fill ps-2">
                      <div class="fw-bold"><a href="profile.php"
                          class="text-decoration-none"><?= $post_profile['profile_first_name'] . ' ' . $post_profile['profile_family_name'] ?></a>
                      </div>
                      <div class="small text-body text-opacity-50"><?= $post['date_art'] ?></div>
                      <?php if ($post['shared_from'] != "") { ?>
                        <?php
                        $shared_from_profile_id = $post['shared_from'];
                        $shared_from_profile = $profileController->getProfileById($shared_from_profile_id);
                        ?>

                        <div class="small text-body text-opacity-50">Shared from : <a
                            class="small text-body text-opacity-50"
                            href="profile.php?profile_id=<?= $shared_from_profile['profile_id']; ?>"
                            target="_blank"><?= $shared_from_profile['profile_first_name'] . ' ' . $shared_from_profile['profile_family_name'] ?></a>
                        </div>
                      <?php } ?>
                    </div>
                    <!-- Ellipsis Icon -->

                    <?php if ($guesst == false) { ?>

                      <div class="dropdown">
                        <a href="javascript:void(0)" class="text-body text-opacity-50 me-2 mb-3 ellipsis-icon"
                          id="ellipsis-dropdown-<?php echo $post['id']; ?>">
                          <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <ul class="dropdown-menu" id="myDropdown-<?php echo $post['id']; ?>">
                          <li><a class="dropdown-item" href="#"><i class="fa fa-thumbtack"></i> Pin post</a></li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-save"></i> Save post</a></li>
                          <hr>
                          <li><a class="dropdown-item" href="javascript:void(0)"
                              onclick="openUpdatePostModal(<?php echo $post['id']; ?>, '<?php echo $post['contenu']; ?>', '<?php echo base64_encode($post['imageArticle']); ?>')"><i
                                class="fa fa-edit"></i> Edit post</a></li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-bell-slash"></i> Turn off notifications for
                              this post</a></li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-language"></i> Turn off translations</a>
                          </li>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-calendar-alt"></i> Edit date</a></li>
                          <hr>
                          <li><a class="dropdown-item" href="#"><i class="fa fa-archive"></i> Move to archive</a></li>
                          <li><a class="dropdown-item"
                              href="../articles management/delete_article.php?id=<?php echo $post['id']; ?>"><i
                                class="fa fa-trash-alt"></i> Move to trash</a></li>
                        </ul>
                      </div>

                    <?php } ?>

                  </div>

                  <!-- dropdown posts menu -->
                  <script>
                    document.addEventListener("DOMContentLoaded", function () {
                      var dropdownBtn = document.getElementById("ellipsis-dropdown-<?php echo $post['id']; ?>");
                      var dropdownMenu = document.getElementById("myDropdown-<?php echo $post['id']; ?>");

                      dropdownBtn.addEventListener("click", function () {
                        if (dropdownMenu.classList.contains("show")) {
                          dropdownMenu.classList.remove("show");
                        } else {
                          dropdownMenu.classList.add("show");
                        }
                      });

                      // Close the dropdown when clicking outside of it
                      window.addEventListener("click", function (event) {
                        if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                          dropdownMenu.classList.remove("show");
                        }
                      });
                    });
                  </script>
                  <!-- end -->

                  <!-- Post Content -->
                  <!-- <p><? //$post['contenu'] ?></p> -->
                  <p><?= $articleC->filterBadWords($post['contenu']); ?></p>

                  <?php if (!empty($post['imageArticle'])) { ?>
                    <!-- Profile Picture Post -->
                    <div class="profile-img-list">
                      <img src="data:image/jpeg;base64,<?= base64_encode($post['imageArticle']) ?>"
                        class="card-img-top new-profile-pic" alt="Profile Picture">
                    </div>
                  <?php } ?>

                  <div class="row text-center">
                    <div class="col text-start">
                      <!-- Like Counter (Left) -->
                      <a href=""><span class="like-counter text-start"
                          id="like-counter-<?php echo $post['id']; ?>"><?php echo $post_liked_nb; ?>
                          Likes</span></a>
                    </div>
                    <div class="col text-end">
                      <!-- Comment Counter Button -->
                      <a href="javascript:void(0)"
                        onclick="toggleCommentsSection(event, <?php echo $post['id']; ?>)"><span class="comment-counter">
                          <?php
                          $commentaireC = new CommentaireC();
                          $commentCount = $commentaireC->countCommentsByPostId($post['id']);
                          echo $commentCount . ' <i class="fas fa-comment"></i>';
                          ?>
                        </span></a>
                      <!-- Share Counter -->
                    </div>
                  </div>
                  <!-- Action Buttons -->
                  <hr class="mb-1 opacity-1">
                  <div class="row text-center fw-bold">
                    <div class="col">

                      <?php if ($is_liked_by_user_profile != 'liked') { ?>
                        <!-- like a post -->
                        <button type="button"
                          onclick="window.location.href='like_a_post.php?post_id=<?php echo $post['id']; ?>&profile_id=<?php echo $user_profile_id; ?>&current_profile=<?php echo $current_profile_id; ?>'"
                          class="like-button text-body text-opacity-50 text-decoration-none p-2">
                          <i class="far fa-thumbs-up me-1 d-block d-sm-inline" style="color: #40A2D8;"></i> Likes
                        </button>
                        <!-- end like a post -->
                      <?php } else if ($is_liked_by_user_profile == 'liked') { ?>

                          <!-- unlike a post -->
                          <button type="button"
                            onclick="window.location.href='unlike_a_post.php?post_id=<?php echo $post['id']; ?>&profile_id=<?php echo $user_profile_id; ?>&current_profile=<?php echo $current_profile_id; ?>'"
                            class="like-button text-body active text-opacity-50 text-decoration-none p-2">
                            <i class="fa fa-thumbs-up me-1 d-block d-sm-inline" style="color: #40A2D8;"></i> Liked
                          </button>
                          <!-- end unlike a post -->

                      <?php } ?>

                      <?php //if ($is_liked_by_user_profile != 'liked') { ?>
                      <!-- like a post -->
                      <!-- <button type="button" onclick="like_a_post('<?php //echo $post['id']; ?>', '<?php //echo $user_profile_id; ?>', '<?php //echo $current_profile_id; ?>')" class="like-button text-body text-opacity-50 text-decoration-none p-2">
                        <i class="far fa-thumbs-up me-1 d-block d-sm-inline" style="color: #40A2D8;"></i> Likes
                      </button> -->
                      <!-- end like a post -->
                      <?php // } else if ($is_liked_by_user_profile == 'liked') { ?>

                      <!-- unlike a post -->
                      <!-- <button type="button" onclick="unlike_a_post('<?php //echo $post['id']; ?>', '<?php //echo $user_profile_id; ?>', '<?php //echo $current_profile_id; ?>')" class="like-button text-body active text-opacity-50 text-decoration-none p-2" >
                        <i class="fa fa-thumbs-up me-1 d-block d-sm-inline" style="color: #40A2D8;"></i> Liked
                      </button> -->
                      <!-- end unlike a post -->

                      <?php //} ?>

                    </div>
                    <div class="col">
                      <button class="comment-button text-body text-opacity-50 text-decoration-none p-2"
                        onclick="toggleCommentInput(event, <?php echo $post['id']; ?>)"> <i
                          class="far fa-comment me-1 d-block d-sm-inline" style="color: #4CCD99;"></i> Comment </button>
                    </div>
                    <div class="col">
                      <button type="button"
                        onclick="window.location.href='share_a_post.php?post_id=<?php echo $post['id']; ?>&profile_id=<?php echo $user_profile_id; ?>&current_profile=<?php echo $current_profile_id; ?>'"
                        class="share-button text-body text-opacity-50 text-decoration-none p-2"> <i
                          class="fa fa-share me-1 d-block d-sm-inline" style="color: #FFC700;"></i> Share </button>
                    </div>
                  </div>
                  <hr class="mb-3 mt-1 opacity-1">


                  <!-- Comment Section -->
                  <div class="comment-section" id="comment-section-<?php echo $post['id']; ?>">
                    <!-- Container to hold comments -->
                    <div class="comments-container" id="comments-container-<?php echo $post['id']; ?>">
                      <!-- Comments will be dynamically inserted here -->
                      <?php
                      // Assuming $post is the array containing post data fetched from the database
                      $commentaireC = new CommentaireC();
                      $comments = $commentaireC->getCommentsByPostId($post['id']);

                      // Output comments
                      foreach ($comments as $comment) {
                        // Fetching profile information for the comment owner
                        //$profileId = $comment['auteur_id']; // Profile ID of the comment owner
                        $cmnt_sender_profile = $profileController->getProfileById($comment['sender_id']); // Assuming you have a method to fetch profile info by ID
                        //if ($comment['auteur_id'] == $user_profile_id || $comment['sender_id'] == $user_profile_id) {
                        if ($comment['auteur_id'] == $user_profile_id || $comment['sender_id'] == $user_profile_id) {
                          $cmnt_is_mine = true;
                        } else {
                          $cmnt_is_mine = false;
                        }

                        // Output comment container
                        echo '<div class="comment-conteiner-receiver">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($cmnt_sender_profile["profile_photo"]) . '" alt="Profile Picture" class="profile-post-pic">';
                        echo '<div class="message">';
                        //echo '<p>' . $comment['contenu'] . '</p>';
                        echo '<p>' . $commentaireC->filterBadWords($comment['contenu']) . '</p>';
                        echo '</div>';
                        if ($cmnt_is_mine) {
                          // Comment menu dropdown
                          echo '<div class="dropdown m-3">';
                          echo '<a href="javascript:void(0)" class="text-body text-opacity-50 comment-menu" id="comment-dropdown-' . $comment['id_commentaire'] . '"><i class="fas fa-ellipsis-h"></i></a>';
                          echo '<ul class="dropdown-menu" id="commentDropdownMenu-' . $comment['id_commentaire'] . '">';
                          if ($comment['sender_id'] == $user_profile_id) {
                            echo '<li><a class="dropdown-item" href="javascript:void(0)" onclick="openEditCommentModal(' . $comment['id_commentaire'] . ', \'' . $comment['contenu'] . '\')"><i class="fas fa-edit"></i> Edit Comment</a></li>';
                          }
                          echo '<li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteComment(' . $comment['id_commentaire'] . ')"><i class="fas fa-trash-alt"></i> Delete Comment</a></li>';
                          echo '</ul>';
                          echo '</div>';
                        }
                        echo '</div>';
                        echo '<span class="time-right font-monospace">' . $comment['date_commentaire'] . '</span>';
                        echo '<br>';

                      }
                      ?>
                    </div>
                  </div>

                  <!-- JavaScript to handle comment dropdown menu -->
                  <script>
                    document.addEventListener("DOMContentLoaded", function () {
                      <?php foreach ($comments as $comment): ?>
                        var commentDropdownBtn_<?php echo $comment['id_commentaire']; ?> = document.getElementById("comment-dropdown-<?php echo $comment['id_commentaire']; ?>");
                        var commentDropdownMenu_<?php echo $comment['id_commentaire']; ?> = document.getElementById("commentDropdownMenu-<?php echo $comment['id_commentaire']; ?>");

                        commentDropdownBtn_<?php echo $comment['id_commentaire']; ?>.                            addEventListener("click", function(event) {
                          event.stopPropagation(); // Prevent parent click event
                          if (commentDropdownMenu_<?php echo $comment['id_commentaire']; ?>.classList.contains("show")) {
                            commentDropdownMenu_<?php echo $comment['id_commentaire']; ?>.classList.remove("show");
                          } else {
                            commentDropdownMenu_<?php echo $comment['id_commentaire']; ?>.classList.add("show");
                          }
                        });

                        // Close the dropdown when clicking outside of it
                        window.addEventListener("click", function (event) {
                          if (!commentDropdownBtn_<?php echo $comment['id_commentaire']; ?>.contains(event.target) && !commentDropdownMenu_<?php echo $comment['id_commentaire']; ?>.contains(event.target)) {
                            commentDropdownMenu_<?php echo $comment['id_commentaire']; ?>.classList.remove("show");
                          }
                        });
                      <?php endforeach; ?>
                    });
                  </script>


                  <!-- Comment Input -->
                  <div id="comment-section-input-<?php echo $post['id']; ?>" class="d-none">
                    <br>
                    <hr class="mb-3 mt-1 opacity-1">
                    <div class="d-flex align-items-center">
                      <a href="./profile.php"><img
                          src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>"
                          alt="Profile Picture" class="profile-post-pic"></a>
                      <div class="flex-fill ps-2">
                        <form id="add_comment_form-<?php echo $post['id']; ?>"
                          action="../articles management/add_commentaire.php" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="id_article" value="<?php echo $post['id']; ?>">
                          <input type="hidden" name="sender_id" value="<?php echo $user_profile_id; ?>">
                          <div class="position-relative d-flex align-items-center">
                            <input id="comment-input-<?php echo $post['id']; ?>" type="text" name="contenu"
                              class="form-control rounded-pill bg-white bg-opacity-15" style="padding-right: 120px;"
                              placeholder="Write a comment...">
                            <div class="position-absolute end-0 text-center">
                              <a href="javascript:void(0)"
                                class="text-body text-opacity-50 me-2 emoji-picker-icon-<?php echo $post['id']; ?>"
                                id="emoji-picker-icon-<?php echo $post['id']; ?>" onclick="toggleEmojiPicker(event)"><i
                                  class="fa fa-smile"></i></a>
                              <a href="#" class="text-body text-opacity-50 me-2"><i class="fa fa-camera"></i></a>
                              <button type="submit" class="text-body text-opacity-50 me-3" title="Send Comment"><i
                                  class="fa fa-paper-plane"></i></button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <script>
                    function submitForm() {
                      // Get the form element
                      var form = document.getElementById("add_comment_form-<?php echo $post['id']; ?>");

                      // Submit the form
                      form.submit();
                    }
                  </script>

                </div>
              </div>
            <?php } ?>

            <?php
            if ($current_profile_sub == "limited") {
            if ($ads_spacing_counter <= 3) {
              $ads_spacing_counter = 0;
              echo '<div class="card mb-3">';
              echo '<h5 style="margin-top: 15px; margin-left: 15px;">Ad</h5>';

              $add_type = "center";
              require __DIR__ . '/../../../View/front_office/ads/ads_containers.php';
              echo '</div>';
            }
            }
            ?>
          </div>
          <!-- Add more post cards as needed -->
        </div>
      </div>




      <!-- Right column for Introduction, Friends, and People you may know -->
      <div class="col-lg-4">
        <!-- Introduction Section -->
        <div class="card mb-3">
          <div class="card-body">
            <!-- Profile Bio -->
            <h5 class="card-title">Profile Bio</h5>
            <p class="card-text"><?php echo $profile['profile_bio'] ?></p>
            <hr>

            <!-- Education -->
            <h5 class="card-title">Education</h5>
            <p class="card-text"><?php echo $profile['profile_education'] ?></p>
            <hr>

            <!-- Region/Country -->
            <h5 class="card-title">Region</h5>
            <p class="card-text"><?php echo $profile['profile_region'] ?></p>
            <hr>

            <!-- Ville -->
            <h5 class="card-title">Ville</h5>
            <p class="card-text"><?php echo $profile['profile_city'] ?></p>
            <hr>

            <!-- Edit Details Button -->
            <div>
              <a href="./profile_update.php?profile_id=<?php echo $profile['profile_id'] ?>"
                class="btn edit-details-button w-100"><strong>Edit Details</strong></a>
            </div>
          </div>
        </div>

        <!-- Friends Widget -->
        <div class="card mb-3">
          <div class="card-body">
            <!-- Friends Title -->
            <h5 class="card-title">Friends (<?php echo $profile_friends_nb; ?>)</h5>

            <!-- Friends Section -->
            <div class="friends-section">
              <hr>
              <!-- Sample Friend Item -->
              <?php echo $friendshipC->generateProfileFriendsHTML($profile_friends, 3); ?>
              <!-- Add more friend items as needed -->

              <!-- Show All Button (Only shown if number of friends is more than 4) -->
              <!-- You can use JavaScript to toggle visibility of this button based on the number of friends -->

              <!-- <div>
                <button class="btn show-all-button w-100"><strong>Show All</strong></button>
              </div> -->
              <!-- Show All Button -->
              <div>
                <button class="btn show-all-button w-100" data-bs-toggle="modal" data-bs-target="#friendsModal"><strong>Show All</strong></button>
              </div>

            </div>
          </div>
        </div>


        <!-- People you may know -->
        <div class="card mb-3">
        <?php if ($current_profile_sub == "limited") { 
          echo '<h5 style="margin-top: 15px; margin-left: 15px;">Ad</h5>';
          //<!-- People you may know content -->
          $add_type = "right";
          require __DIR__ . '/../../../View/front_office/ads/ads_containers.php';
          }
          ?>
          <!-- End Ad -->
        </div>
      </div>
    </div>


    <!-- Create Post Popup -->
    <div id="createPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <!-- Part 1: Profile Picture -->
          <a href="./profile.php">
            <img src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>"
              class="rounded-circle mb-3" alt="Profile Picture" width="50" height="50">
          </a>
          <div class="fw-bold"><a href="./profile.php" class="text-decoration-none">
              <h5><?= $user_profile['profile_first_name'] ?>
                <?= $user_profile['profile_family_name'] ?>
              </h5>
            </a></div>
          <!-- <span class="close mb-3" onclick="closeCreatePostModal()">&times;</span> -->
          <span class="close mb-3" id="closeButton-for-post">&times;</span>
        </div>
        <form id="createPostForm" action="../articles management/add_article.php" method="post"
          enctype="multipart/form-data">
          <div class="modal-body mt-3">
            <input type="hidden" name="auteur_id" value="<?php echo $user_profile_id; ?>">

            <!-- Part 2: Description Textarea and Buttons -->
            <textarea id="postDescription" name="contenu" placeholder="What's on your mind?"></textarea>
            <div class="buttons">

              <input type="file" name="imageArticle" id="imageArticle" accept="image/*" hidden>
              <a href="javascript:void(0)" class="btn icon-btn rounded-5"><label for="imageArticle" title="Add Media"><i
                    class="fa fa-camera"></i></label></a>
              <button class="btn icon-btn rounded-5" title="Create an Event"><i class="fa fa-calendar"></i></button>
              <button class="btn icon-btn rounded-5" title="Celebrate an Occasion"><i class="fa fa-gift"></i></button>
            </div>
          </div>

          <div class="modal-footer">
            <!-- Part 3: Post and Schedule Buttons -->
            <button class="btn schedule-btn rounded-5" id="clock" title="Schedule for Later"><i
                class="fa fa-clock-o"></i></button>
            <button class="btn post-btn rounded-5" id="post">Post</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Update Post Popup -->
    <div id="updatePostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Edit Post</h2>
          <span class="close" onclick="closeUpdatePostModal()">&times;</span>
        </div>
        <form id="updatePostForm" action="../articles management/update_article.php" method="post"
          enctype="multipart/form-data">
          <div class="modal-body mt-3">
            <input type="hidden" id="postId" name="id" value="">
            <!-- Post Image Container -->
            <div id="postImageContainer" class="post-image-container"></div>
            <!-- Hidden Image Container -->
            <div id="hiddenPostImageContainer" class="post-image-container hidden" style="display: none;"></div>

            <br>
            <!-- Post Content -->
            <textarea id="updatedPostContent" name="contenu"
              placeholder="Write your updated post content..."></textarea>
            <input type="hidden" name="auteur_id" value="<?php echo $post['auteur_id']; ?>">
            <div class="buttons">
              <input type="file" hidden name="newimageArticle" id="newimageArticle" accept="image/*"
                onchange="previewNewImage()">
              <a href="javascript:void(0)" class="btn icon-btn rounded-5"><label for="newimageArticle"
                  title="Add Media"><i class="fa fa-camera"></i></label></a>
              <a href="javascript:void(0)" class="btn icon-btn rounded-5" title="Create an Event"><i
                  class="fa fa-calendar"></i></a>
              <a href="javascript:void(0)" class="btn icon-btn rounded-5" title="Celebrate an Occasion"><i
                  class="fa fa-gift"></i></a>
            </div>
          </div>
          <div class="modal-footer">
            <!-- Buttons -->
            <a href="javascript:void(0)" class="btn btn-secondary" onclick="closeUpdatePostModal()">Cancel</a>
            <button class="btn post-btn rounded-5" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>


    <!-- Edit Comment Modal -->
    <div id="editCommentModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <span class="close" onclick="closeEditCommentModal()">&times;</span>
          <h2>Edit Comment</h2>
        </div>
        <form id="editCommentForm" action="../articles management/update_commentaire.php" method="post">
          <div class="modal-body mt-3">
            <input type="hidden" name="id_commentaire" id="editCommentId">
            <textarea name="newContenu" id="editCommentTextarea" rows="4" cols="50"></textarea>
            <br>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" onclick="updateComment()">Update</button>
            <button class="btn post-btn rounded-5" type="button" onclick="closeEditCommentModal()">Cancel</button>
          </div>
        </form>
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
          <p><?php echo generateSubtitleProfile(); ?></p>
          <hr>
          <!-- Help Us Improve Link -->
          <a href="../reclamation/reclamation.php" class="popup-link">
            <i class="fas fa-comments"></i> Help us improve HireUp
          </a>
          <!-- Help Us Improve Subtitle -->
          <p><?php echo generateFeedbackSubtitleProfile(); ?></p>
        </div>
      </div>
    </div>

    <!-- Friends Modal -->
    <div class="modal fade" style="z-index: 99999999990 !important;" id="friendsModal" tabindex="-1" aria-labelledby="friendsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="friendsModalLabel">Friends</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <?php echo $friendshipC->generateProfileFriendsPageHTML($profile_friends, $profile['profile_id']); ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    




    <?php
    function generateSubtitleProfile()
    {
      return "Here you can report issues or inappropriate content to the HireUp team.";
    }

    function generateFeedbackSubtitleProfile()
    {
      return "We appreciate your feedback! Share your thoughts and suggestions to help us enhance your HireUp experience.";
    }
    ?>

    <script>
      document.getElementById("closeButton-for-post").addEventListener("click", function () {
        closeCreatePostModal();
      });
    </script>

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
    <footer class="bg-dark text-center text-white py-3 mt-4 bottom-100">
      <div class="container">
        <p>&copy; 2024 All rights reserved to <b>be.net</b></p>
      </div>
    </footer>


    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js'></script>

    <script>
      var dropdown = document.querySelector('.dropdown-toggle');
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

      // Get the modal
      var modal = document.getElementById("createPostModal");

      // Get the input field
      var inputField = document.getElementById("createPostInput");

      // Function to open the create post modal
      function openCreatePostModal() {
        var modal = document.getElementById("createPostModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; // Disable scrolling
      }

      function closeCreatePostModal() {
        var modal = document.getElementById("createPostModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto"; // Enable scrolling
        console.log("Modal closed");
      }


      // When the user clicks on the input field, open the modal
      inputField.onclick = function () {
        openCreatePostModal();
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function (event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
    </script>

    <script>
      var dropdown = document.querySelector('.dropdown-toggle');
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

      // Get the modal
      var modal1 = document.getElementById("createPostModal");

      // Get the input field
      var inputField = document.getElementById("createPostInput");

      // Function to open the create post modal
      function openCreatePostModal() {
        var modal = document.getElementById("createPostModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; // Disable scrolling
      }

      function closeCreatePostModal() {
        var modal = document.getElementById("createPostModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto"; // Enable scrolling
      }


      // When the user clicks on the input field, open the modal
      inputField.onclick = function () {
        openCreatePostModal();
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function (event) {
        if (event.target == modal1) {
          modal1.style.display = "none";
        }
      }
    </script>


    <script>
      // Open the modal and populate inputs with post data
      function openUpdatePostModal(id, content, imageUrl) {
        // Set the post ID
        document.getElementById("postId").value = id;

        // Set the post content
        document.getElementById("updatedPostContent").value = content;

        // Display the modal
        document.getElementById("updatePostModal").style.display = "block";

        // Update the post image container
        if (imageUrl) {
          const imageContainer = document.getElementById("postImageContainer");
          imageContainer.innerHTML = `
          <div class="post-image-container">
              <img src="data:image/jpeg;base64,${imageUrl}" alt="Post Image">
          </div>`;
        }
      }

      // Close the modal
      function closeUpdatePostModal() {
        document.getElementById("updatePostModal").style.display = "none";
      }

      function previewNewImage() {
        const fileInput = document.getElementById("newimageArticle");
        const hiddenImageContainer = document.getElementById("hiddenPostImageContainer");
        const oldImageContainer = document.getElementById("postImageContainer");

        // Clear previous content
        hiddenImageContainer.innerHTML = "";

        // Check if a file is selected
        if (fileInput.files && fileInput.files[0]) {
          const reader = new FileReader();

          reader.onload = function (e) {
            // Create new image element
            const newImage = document.createElement("img");
            newImage.src = e.target.result;
            newImage.alt = "New Post Image";

            // Append new image to hidden container
            hiddenImageContainer.appendChild(newImage);

            // Show hidden container
            hiddenImageContainer.style.display = "block";

            // Hide old image container
            oldImageContainer.style.display = "none";
          };

          // Read the file as data URL
          reader.readAsDataURL(fileInput.files[0]);
        }
      }
    </script>


    <script>
      // Function to toggle comment input
      function toggleCommentInput(event, postId) {
        event.preventDefault();
        var commentInput = document.getElementById('comment-section-input-' + postId);
        commentInput.classList.toggle('d-none');
      }

      // Function to toggle comments section and comment input
      function toggleCommentsSection(event, postId) {
        event.preventDefault();
        var commentsSection = document.getElementById('comment-section-' + postId);
        var commentInput = document.getElementById('comment-section-input-' + postId);

        if (commentsSection.classList.contains('d-none') && commentInput.classList.contains('d-none')) {
          // If both are hidden, remove 'd-none' from both
          commentsSection.classList.remove('d-none');
          commentInput.classList.remove('d-none');
        } else {
          // Otherwise, add 'd-none' to both
          commentsSection.classList.add('d-none');
          commentInput.classList.add('d-none');
        }
      }
    </script>

    <script>
      function deleteComment(commentId) {
        // Confirm before deleting the comment
        if (confirm("Are you sure you want to delete this comment?")) {
          // Construct the URL with the comment ID
          var url = "../articles management/delete_cmnt.php?id=" + commentId;

          // Send a GET request to the delete_cmnt.php file
          fetch(url)
            .then(response => {
              if (response.ok) {
                // Reload the page after successful deletion
                window.location.reload();
              } else {
                // Handle errors if any
                console.error('Error deleting comment:', response.statusText);
              }
            })
            .catch(error => {
              console.error('Error deleting comment:', error);
            });
        }
      }
    </script>

    <script>
      // Function to open the edit comment modal
      function openEditCommentModal(commentId, currentContenu) {
        var modal = document.getElementById('editCommentModal');
        var textarea = document.getElementById('editCommentTextarea');
        var form = document.getElementById('editCommentForm');

        // Set the current comment content and ID in the form
        textarea.value = currentContenu;
        document.getElementById('editCommentId').value = commentId;

        // Show the modal
        modal.style.display = 'block';
      }

      // Function to close the edit comment modal
      function closeEditCommentModal() {
        document.getElementById('editCommentModal').style.display = 'none';
      }

      // Function to update the comment
      function updateComment() {
        // Gather comment ID and new content
        var commentId = document.getElementById('editCommentId').value;
        var newContenu = document.getElementById('editCommentTextarea').value;

        // Submit the form
        document.getElementById('editCommentForm').submit();

        // Close the modal
        closeEditCommentModal();
      }
    </script>


    <script>
      /*function like_a_post (post_id, likeer_id, profile_to_go_back_to){
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "like_a_post.php?post_id=" + post_id + "&profile_id=" + likeer_id + "&current_profile=" + profile_to_go_back_to, true); 
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("PHP page executed successfully");
                // You can handle the response here if needed
            }
        };
        xhr.send();
      }
    
      function unlike_a_post (post_id, likeer_id, profile_to_go_back_to){
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "unlike_a_post.php?post_id=" + post_id + "&profile_id=" + likeer_id + "&current_profile=" + profile_to_go_back_to, true); 
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("PHP page executed successfully");
                // You can handle the response here if needed
            }
        };
        xhr.send();
      }*/
    </script>






    <script>

      function searchProfiles() {
        const query = document.getElementById('search').value.toLowerCase();
        const profilesContainer = document.getElementById('profiles-container');

        if (query.trim() === "") {
          profilesContainer.style.display = 'none';
          profilesContainer.innerHTML = '';
          return;
        }

        // AJAX request to fetch profile data from PHP script
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            const profiles = JSON.parse(xhr.responseText);

            profilesContainer.style.display = 'block';
            profilesContainer.innerHTML = '';

            profiles.forEach(profile => {
              const profileCard = document.createElement('div');
              profileCard.className = 'profile-card';

              profileCard.innerHTML = `
                          <img src="data:image/jpeg;base64,${profile.profile_photo}" alt="${profile.profile_first_name} ${profile.profile_family_name}">
                          <div class="profile-info">
                              <div class="profile-name">${profile.profile_first_name} ${profile.profile_family_name}</div>
                              <div class="profile-job">${profile.user_name}</div>
                          </div>
                      `;

              // Add click event listener to profile card
              profileCard.addEventListener('click', function () {
                // Example: Redirect to profile page with profile_id as query parameter
                window.location.href = `profile.php?profile_id=${profile.profile_id}`;
              });

              profilesContainer.appendChild(profileCard);
            });

            if (profiles.length === 0) {
              profilesContainer.style.display = 'none';
            }
          }
        };

        xhr.open('GET', 'search_profiles.php?keyword=' + query, true);
        xhr.send();
      }

      function closeSearchProfiles() {
        const profilesContainer = document.getElementById('profiles-container');
        profilesContainer.style.display = 'none';


      }


    </script>

    <script>
      function updateNotifications(receiverId, newVal) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_notifications_to_seen.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              console.log("Notifications updated successfully");
              console.log(xhr.responseText);

              updateNotificationsVisibility('notif_nb', 'none');
              // Optionally, you can do something after successful update
            } else {
              console.error("Error updating notifications:", xhr.statusText);
            }
          }
        };
        xhr.onerror = function () {
          console.error("Request failed");
        };
        var params = "receiver_id=" + encodeURIComponent(receiverId) + "&new_val=" + encodeURIComponent(newVal);
        xhr.send(params);
      }

      function updateNotificationsVisibility(notif_id, newVal) {

        // Check if the element exists
        var followBadge = document.getElementById(notif_id);
        if (followBadge) {
          followBadge.style.display = newVal; // Setting display to an empty string will reset it to the default value (usually "inline" or "block")
        }

      }


    </script>


    <!-- voice recognation -->
    <script type="text/javascript"
      src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

    <?php
    include './../jobs management/chatbot.php';
    ?>
    <script src="./../../../front office assets/js/chatbot.js"></script>

    <!-- ads -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
      const postUrl = 'http://localhost/hireup/v1/view/front_office/ads/jobClicked.php';
      function invokePhpFunction(pub_id) {
        console.log('job Clicked');
        // Make an AJAX request to your PHP script to execute the desired function
        // Example using jQuery AJAX:
        $.ajax({
          // url: 'jobClicked.php?id='+pub_id, // Replace 'your_php_script.php' with the path to your PHP script
          url: postUrl + '?id=' + pub_id, // Replace 'your_php_script.php' with the path to your PHP script
          type: 'POST',
          data: { action: 'jobClicked' }, // Pass any necessary data to your PHP function
          success: function (response) {
            // Handle the response if needed
            console.log(response);
            if (response == "1 records UPDATED successfully <br>true") {
              return true;
            } else {
              return false;
            }
          },
          error: function (xhr, status, error) {
            // Handle errors
            console.error(xhr.responseText);
            return false;
          }
        });
      }
    </script>

</body>

</html>