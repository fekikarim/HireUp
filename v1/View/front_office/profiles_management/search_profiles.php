<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . '/../../../Controller/profileController.php';

$profileController = new ProfileC();

$keyword = "";
if (isset($_GET['keyword'])) {
    $keyword = htmlspecialchars($_GET['keyword']);
}

//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
}

// Define dynamic profile data
$profiles = $profileController->searchProfilesSearchBar($keyword);

// Loop through profiles to encode image data to base64 and remove if user profile
foreach ($profiles as $key => $profile) {
    // Check if profile_photo exists and is not empty
    if (isset($profile['profile_photo']) && !empty($profile['profile_photo'])) {
        // Get the image file path
        $imageData = $profile['profile_photo'];

        // Encode the image data to base64
        $base64ImageData = base64_encode($imageData);

        // Replace the image path with base64 encoded image data
        $profiles[$key]['profile_photo'] = $base64ImageData;
    }

    // Check if user profile and remove from array
    if ($user_profile_id == $profile['profile_id']) {
        unset($profiles[$key]);
    }
}

// Re-index the array
$profiles = array_values($profiles);

// Encode data into JSON format
$json_data = json_encode($profiles);

// Set appropriate header for JSON response
header('Content-Type: application/json');

// Output the JSON-encoded data
echo $json_data;

?>
