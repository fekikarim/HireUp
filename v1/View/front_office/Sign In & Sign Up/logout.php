<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

if(isset($_SESSION['user id'])) {
  unset($_SESSION['user id']);
}


// Redirect back to the page after logout
header("Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php");
exit();

?>
