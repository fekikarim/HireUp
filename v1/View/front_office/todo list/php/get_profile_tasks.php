<?php

require_once __DIR__ . '/../../../../Controller/todo_tasks_con.php';
require_once __DIR__ . '/../../../../Controller/profileController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$todoController = new TodoTaskCon();
$profileController = new ProfileC();

$user_id = '';
$user_profile_id = '';


if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);

    // Get profile ID from the URL
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

}

if (isset($user_profile_id) && !empty($user_profile_id)) {

    $tasks = $todoController->listTasksByProfileId($user_profile_id);

    // Encode data into JSON format
    $json_data = json_encode($tasks);

    // Set appropriate header for JSON response
    header('Content-Type: application/json');
    // Output the JSON-encoded data
    echo $json_data;
} else {
    echo json_encode(array("error" => "Profile id is required"));
}






?>