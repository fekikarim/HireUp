<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

include_once __DIR__. '/../../../../Controller\profileController.php';

// Check if new phone number and verification code are posted
if (isset($_GET['profile_id'])) {
    // Get the new phone number and verification code from the POST data
    $id = $_GET["profile_id"];
    //$newPhoneNumber = $_POST["new_phone_number"];
    if (isset($_GET['phone_nb'])){
        $newPhoneNumber = $_GET['phone_nb'];
    }
    $userInputCode = $_POST["digit1"] . $_POST["digit2"] . $_POST["digit3"] . $_POST["digit4"];
    if (isset($_SESSION['verif_code'])){
        $sentCode = $_SESSION['verif_code'];
    }
    //$sentCode = $_GET["verification_code"];

    // Create an instance of the controller
    $profileController = new ProfileC();

    // Compare the sent code with the user input code
    if ($userInputCode === $sentCode) {
        // If codes match, update the phone number
        $result = $profileController->updatePhoneNb($id, $newPhoneNumber);
        // Redirect back to the profile page
        echo "ff : ";
        echo $newPhoneNumber;
        echo 'id : ';
        echo $id;
        if($result){
            header('Location: ./edit-profile.php?profile_id=' . $id . '?phone_updated_successfully');
            echo "aaaa : ";
            exit();
        } else {        
            header('Location: ./edit-profile.php?profile_id=' . $id . '?update_error');
            echo "bbbb : ";
            exit();
        }
        
    } else {
        // If codes don't match, display an error message
        header('Location: ./edit-profile.php?profile_id=' . $id . '?verification_code_incorrect');
        echo "cccccc : ";
        exit();
    }
}
else{
    echo "ffff";
}
