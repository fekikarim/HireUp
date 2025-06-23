<?php

include '../../../Controller/user_con.php';
include '../../../Model/user.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

//MARK: important cz it checks if the user_id is set or not
// check if its already logged in
if(isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);

    $user_banned = $userC->get_user_banned_by_id($user_id);

    if ($user_banned == "false"){

        $user_verified = $userC->get_user_verified_by_id($user_id);

        if ($user_verified == "true"){
            echo("Your verified ");
            echo("Welcome user id : " . $user_id);

            header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php');
        }
        else{
            header('Location: ../../../View/front_office/Sign In & Sign Up/verify-account.php');
        }
    
    }
    else{
        header('Location: ../../../View/front_office/Sign In & Sign Up/banned.php');
    }

}
// logging in
else
{

    if (
        isset($_POST["user_name"]) &&
        isset($_POST["user_password"])
    ) {
        if (
            !empty($_POST['user_name']) &&
            !empty($_POST["user_password"])
        ) {
            
            $user_name_email = $_POST["user_name"];
            $password = $_POST["user_password"];

            if ($userC->isEmailOrUserName($user_name_email) == "email"){
                $res = $userC->verifLoginInfosByEmail($user_name_email, $password);
            }
            elseif ($userC->isEmailOrUserName($user_name_email) == "user name"){
                $res = $userC->verifLoginInfosByUserName($user_name_email, $password);
            }

            if ($res == "wrong email"){
                $error_user_name_email = "This email is not registered. Would you like to sign up instead?";
                header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_user_name_email=' . urlencode($error_user_name_email) . '&user_name_email=' . urlencode($user_name_email));
                exit(); // Make sure to stop further execution after redirection
            }
            elseif ($res == "wrong user name"){
                $error_user_name_email = "This username is not registered. Would you like to sign up instead?";
                header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_user_name_email=' . urlencode($error_user_name_email) . '&user_name_email=' . urlencode($user_name_email));
                exit(); // Make sure to stop further execution after redirection
            }

            elseif ($res == "wrong password"){
                $error_password = "Incorrect password. Please try again.";
                header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_password=' . urlencode($error_password) . '&user_name_email=' . urlencode($user_name_email));
                exit(); // Make sure to stop further execution after redirection
            }

            else{
                if (session_status() == PHP_SESSION_NONE) {
                    session_set_cookie_params(0, '/', '', true, true);
                    session_start();
                }
                //MARK: - Set the user id in the session
                $_SESSION['user id'] = $res; // Set the user id in the session

                $user_banned = $userC->get_user_banned_by_id($res);

                if ($user_banned == "false"){

                    $user_verified = $userC->get_user_verified_by_id($res);

                    if ($user_verified == "true"){

                        $user_need_password_change = $userC->get_user_need_password_change_by_id($user_id);
              
                        if ($user_need_password_change == 'true'){
              
                          // take the user to the change password page
                          header('Location: ../../../View/front_office/Sign In & Sign Up/change-password.php');
              
                        }
                        else{
                          echo("Your verified ");
                          echo("Welcome user id : " . $user_id);
              
                          header('Location: ../../../index.php');//MARK: go back to index.php
              
                        }
              
                      }
                    else{
                        header('Location: ../../../View/front_office/Sign In & Sign Up/verify-account.php');
                    }
                
                }
                else{
                    header('Location: ../../../View/front_office/Sign In & Sign Up/banned.php');
                }

                exit();
            }
    
        }
    }


}

?>