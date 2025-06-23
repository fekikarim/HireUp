<?php
require_once __DIR__ . '/../../../Controller/profileController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the profile information from the form
    $first_name = $_POST["profile_first_name"];
    $family_name = $_POST["profile_family_name"];
    $phone_number = $_POST["profile_phone_number"];
    $region = $_POST["profile_region"];
    $city = $_POST["profile_city"];
    $bio = $_POST["profile_bio"];
    $current_position = $_POST["profile_current_position"];
    $education = $_POST["profile_education"];
    $subscription = $_POST["plan_name"];
    $auth = $_POST["profile_auth"];
    $acc_verif = $_POST["profile_acc_verif"];
    $bday = $_POST["profile_bday"];
    $gender = $_POST["profile_gender"];
    
    // Create an instance of the controller
    $profileController = new ProfileC();

    // Generate profile ID
    $profile_id = $profileController->generateProfileId(6); // 6 is the length of the profile ID
    $userid = $profileController->generateProfileUserId(6); // 6 is the length of the pro   file ID

    // Fetch subscription_id based on plan_name
    $subscription_id = $profileController->getSubscriptionIdByPlanName($subscription);

    if ($subscription_id) {
        // Check if the file inputs are set and not empty
        if (!empty($_FILES['profile_photo']['name']) && !empty($_FILES['profile_cover']['name'])) {
            // Get profile photo and cover data
            $profile_photo_tmp_name = $_FILES['profile_photo']['tmp_name'];
            $profile_photo_data = file_get_contents($profile_photo_tmp_name);

            $profile_cover_tmp_name = $_FILES['profile_cover']['tmp_name'];
            $profile_cover_data = file_get_contents($profile_cover_tmp_name);

            // Call the method to add the profile with profile photo and cover data
            $result = $profileController->addProfile($profile_id, $first_name, $family_name, $userid, $phone_number, $region, $city, $bio, $current_position, $education, $subscription_id, $auth, $acc_verif, $bday, $gender, $profile_photo_data, $profile_cover_data);

            if ($result) {
                // Redirect to the feature management page with a success message
                header("Location: ./profile_management.php?creation_success=true");
                exit;
            } else {
                // Redirect to the feature management page with an error message
                header("Location: ./profile_management.php?creation_error=true");
                exit;
            }
        } else {
            echo "Please select a photo and cover image.";
        }
    } else {
        // If the request method is not POST, redirect to an error page
        header("Location: ./profile_management.php?post_error=true");
        exit;
    }
}
