<?php 


$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

// Clear all previously set HTTP headers
header_remove();

// Get the current URL path
$link_prime = __DIR__;
$word_to_remove = "View";

$link = str_replace($word_to_remove, "", $link_prime);

include_once $link .'Controller/user_con.php';
include_once $link . 'Model/user.php';
include_once $link . 'Controller/profileController.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");
$profileController = new ProfileC();

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


if(isset($_SESSION['user id'])) {

    //MARK: important cz it checks if the user_id is set or not
    $user_id = htmlspecialchars($_SESSION['user id']);

    $user_banned = $userC->get_user_banned_by_id($user_id);

    $user_role = $userC->get_user_role_by_id($user_id);


    if ($block_call_back == 'false'){


        if ($user_role != "admin"){
            if ($access_level == "admin"){
                header('Location: ' . $current_url . 'index.php');
            }
        }

        if ($access_level == "none"){
            header('Location: ' . $current_url . 'index.php');
        }
    }

    if ($user_banned == 'true' && (!isset($special_case))){
        header('Location: ' . $current_url . 'View/front_office/Sign In & Sign Up/banned.php');
        exit(); 
    }
    
    $user_verified = $userC->get_user_verified_by_id($user_id); 
    if ($user_verified == "false" && (!isset($special_case))){
        header('Location: ' . $current_url . 'View/front_office/Sign In & Sign Up/verify-account.php');
        exit(); 
    }

    $user_need_password_change = $userC->get_user_need_password_change_by_id($user_id);
    if ($user_need_password_change == 'true' && (!isset($special_case)) && (!isset($accept))){
        header('Location: ' . $current_url . 'View/front_office/Sign In & Sign Up/change-password.php');
        exit();
    }

    // Get profile ID from the URL
    $profile_id = $profileController->getProfileIdByUserId($user_id);
    if ($profile_id == "error" && (!isset($special_case))){
        header('Location: ' . $current_url . 'View\front_office\profiles_management\profile-register.php');
        exit();
    }

    if(isset($special_case)) {

        if ($special_case == 'user_banned') {
            if ($user_banned == 'false') {
                header('Location: ' . $current_url . 'index.php');
                exit();
            }
        }

        if ($special_case == 'profile_creation') {
            if ($profile_id != "error") {
                header('Location: ' . $current_url . 'View\front_office\profiles_management\profile.php');
                exit();
            }
        }

        if ($special_case == 'password_changing' && (!isset($accept))) {
            if ($user_need_password_change == 'false') {
                header('Location: ' . $current_url . 'index.php');
                exit();
            }
        }

        if ($special_case == 'user_verification') {
            if ($user_verified == "true") {
                header('Location: ' . $current_url . 'index.php');
                exit();
            }
        }
    }

}else{
    
    if ($access_level != "none"){
        header('Location: ' . $current_url . 'View/front_office/Sign In & Sign Up/authentication-login.php');
        exit();
    }
    if ($access_level == "profile"){
        header('Location: ' . $current_url . 'View/front_office/Sign In & Sign Up/authentication-login.php');
        exit();
    }
    
}

?>
