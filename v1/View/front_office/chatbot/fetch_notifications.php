<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';

$profileController = new ProfileC();
$NotificationCon = new NotificationCon("notifications");

$user_notifications = array();
$user_notifications_nb = 0;

//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
}

// Define dynamic profile data
$user_notifications = $NotificationCon->listNotificationsByReceiverIdOrderedByDateTimeAndNotSeen($user_profile_id);
$user_notifications_nb = count($user_notifications);


// Encode data into JSON format
$json_data = json_encode($user_notifications);

// Set appropriate header for JSON response
header('Content-Type: application/json');
header('Content-Type: application/json');

// Output the JSON-encoded data
echo $json_data;

?>
