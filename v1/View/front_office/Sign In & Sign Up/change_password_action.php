<?php

include '../../../Controller/user_con.php';


  
if (isset($_POST['user_con_password'])) {

    // Retrieve and sanitize the error message
    $user_new_password = htmlspecialchars($_POST['user_con_password']);
    $hashed_password = password_hash($user_new_password, PASSWORD_DEFAULT);
    
    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params(0, '/', '', true, true);
        session_start();
    }
    if (isset($_SESSION['user id'])) {
        $user_id = htmlspecialchars($_SESSION['user id']);
        
        $userC = new userCon("user");
        $res = $userC->updateUserPassword($user_id, $hashed_password);

        #echo($res);
        
        if ($res == true){
            $success_message = "Password changed successfully!";
            if ($userC->get_user_need_password_change_by_id($user_id) == 'true'){
                $res = $userC->updateUser_need_password_change($user_id, 'false');
                
                if ($res == true){
                    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?success_global=' . urlencode($success_message) . '&user_name_email=' . urlencode($userC->get_user_username_by_id($user_id)));
                    echo "dddd";
                    exit();
                }
            }
            header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?success_global=' . urlencode($success_message) . '&user_name_email=' . urlencode($userC->get_user_username_by_id($user_id)));
            echo "cccc";
            exit(); // Make sure to stop further execution after redirection
          }
          else {
            echo "aaa";
        }
    }
    
    // returning an error
    $error_message = "Failed to change password. Please try again later.";
    #header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_message));
    echo "bbbbb";
    exit(); // Make sure to stop further execution after redirection
    
}
else{
    echo "aaa";
}


?>