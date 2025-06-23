<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/jobC.php';

$profileController = new ProfileC();
$jobC = new JobController();

$jobs = array();

//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
}

// Define dynamic profile data
$jobs = $jobC->getAllRecentJobs();
$jobs_nb = count($jobs);


// Encode data into JSON format
$json_data = json_encode($jobs);

// Set appropriate header for JSON response
header('Content-Type: application/json');
header('Content-Type: application/json');

// Output the JSON-encoded data
echo $json_data;

?>
