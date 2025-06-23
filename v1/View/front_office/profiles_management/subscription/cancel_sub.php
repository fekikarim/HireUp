<?php

require_once __DIR__ . '/../../../../Controller/profileController.php';

require_once __DIR__ . '/../../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../../Model/notification.php';

require_once __DIR__ . '/../../../../Controller/subscriptionControls.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
  }

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

$profileController = new ProfileC();
$subscriptionController = new SubscriptionControls();
$NotificationCon = new NotificationCon("notifications");

if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);
  
    // Get profile ID from the URL
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

}


$current_sub = '';




$profileController->updateSubscription($user_profile_id, $current_sub);

$notification = new Notification(
    $NotificationCon->generateNotificationId(5),
    'HireUp',
    $user_profile_id,
    "Your subscription has been successfully canceled.",
    '#',
    'true'
  );

$NotificationCon->addNotification($notification);

header("location: ./subscriptionCards.php");


?>