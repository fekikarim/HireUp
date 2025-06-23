<?php

require_once __DIR__ . '/../../../Controller/faces_con.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

$faceC = new FaceController();


if(isset($_SESSION['user id'])) {

  $user_id = htmlspecialchars($_SESSION['user id']);

  $face_data = $faceC->getFaceByUserId($user_id);

  $face_id = $face_data['id'];
  
  $faceC->deleteFace($face_id);

  header("Location: ../profiles_management/settings_privacy/edit-profile.php");
  exit; 
}


?>