<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . '/../../../Controller/profileController.php';

$profileController = new ProfileC();


//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
}

if (isset($_GET['profile_id'])) {
    $profile_id = htmlspecialchars($_GET['profile_id']);
    $profile = $profileController->getProfileById($profile_id);
}

if ($profile){
    if ($user_profile_id == $profile_id) {
        $rep = 'you';
    } else {
        $rep = $profile['profile_first_name'].' '. $profile['profile_family_name'];
    }
}


$array_data = array(
    "posted_by" => $rep
);


// Encode data into JSON format
$json_data = json_encode($array_data);

// Set appropriate header for JSON response
header('Content-Type: application/json');

// Output the JSON-encoded data
echo $json_data;

?>
