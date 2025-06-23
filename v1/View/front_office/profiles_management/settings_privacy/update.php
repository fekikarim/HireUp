<?php
require_once __DIR__ . '/../../../../Controller/profileController.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['profile_id'])) {
    // Retrieve the profile information from the form
    $id = $_POST["profile_id"];
    $first_name = $_POST["profile_first_name"];
    $family_name = $_POST["profile_family_name"];
 
    $region = $_POST["profile_region"];
    $city = $_POST["profile_city"];

    $current_position = $_POST["profile_current_position"];
    $education = $_POST["profile_education"];

    $bday = $_POST["profile_bday"];
    $gender = $_POST["profile_gender"];

    // Create an instance of the controller
    $profileController = new ProfileC();

    // Call the method to update the profile without changing the existing photo and cover
    $profileController->updateProfileEdit($id, $first_name, $family_name, $region, $city, $current_position, $education, $gender, $bday);
    
    // Redirect to profile management page after the update
    header('Location: ./edit-profile.php');
    exit();
}
