<?php

include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/friendshipsCon.php';
include_once __DIR__ .  '/../../../Model/Friendship.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$userC = new userCon("user");

$profileController = new ProfileC();

$friendshipC = new FriendshipCon("friendships");

if(isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);
  
  } else {
    $user_id = '';
  }

    
  // Get profile ID from the URL
  $user_profile_id = $profileController->getProfileIdByUserId($user_id);

  if(isset($user_profile_id) && isset($_GET['profile_id'])) {

    $friend_profile_id = htmlspecialchars($_GET['profile_id']);

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    $friendship = new Friendship(
        $userC->generateUserId(5),
        $user_profile_id,
        $friend_profile_id,
        'pending', // ('pending', 'accepted', 'rejected')
        $user_profile_id,
        $currentDateTime
    );

    $friendshipC->addFriendship($friendship);
    echo "gg";


    header('Location: ./../profiles_management/profile.php?profile_id=' . $friend_profile_id);

  }
  else{
    echo "ff";
  }


  



?>