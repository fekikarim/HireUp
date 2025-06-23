<?php


require_once __DIR__ . '/../../../Controller/friendshipsCon.php';

$friendshipC = new FriendshipCon("friendships");

if (isset($_GET['id'])){
  $current_id = $_GET['id'];

  if(isset($_GET['profile_id'])) {
    
    $friend_profile_id = htmlspecialchars($_GET['profile_id']);
   
    $res = $friendshipC->deleteFriendship($current_id);

    header('Location: ./../profiles_management/profile.php?profile_id=' . $friend_profile_id);
  }


  }
  else{
    echo "ff";
  }


  



?>