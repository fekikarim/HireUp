<?php


require_once __DIR__ . '/../../../Controller/friendshipsCon.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';

$friendshipC = new FriendshipCon("friendships");
$NotificationCon = new NotificationCon("notifications");

$notification = null;

if (isset($_GET['id'])){
  $current_id = $_GET['id'];

  if(isset($_GET['profile_id'])) {
    
    $friend_profile_id = htmlspecialchars($_GET['profile_id']);

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");
   
    $res = $friendshipC->acceptFriendship($current_id, $currentDateTime);
    
    $current_friendship = $friendshipC->getFriendship($current_id);

    $notification_sender = '';
    if ($current_friendship['sender_profile'] == $current_friendship['profile1']){
      $notification_sender = $current_friendship['profile2'];
    } else {
      $notification_sender = $current_friendship['profile1'];
    }

    //var_dump($current_friendship);

    $notification = new Notification(
      $NotificationCon->generateNotificationId(5),
      $notification_sender,
      $friend_profile_id,
      'has accepted your follow request',
      '#',
      'false'
    );

    $NotificationCon->addNotification($notification);

    header('Location: ./../profiles_management/profile.php?profile_id=' . $friend_profile_id);
  }


  }
  else{
    echo "ff";
  }


  



?>