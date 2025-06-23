<?php 

    require_once __DIR__ . '/../../../Controller/messaging_con.php';

    if (isset($_POST['user_profile_id']) && isset($_POST['other_profile_id'])) {
        $user_profile_id = $_POST['user_profile_id'];
        $other_profile_id = $_POST['other_profile_id'];

        $msgC = new MessagingCon("messages");

        $nb = $msgC->countUnseenMessageNbsForFriendshipe($user_profile_id, $other_profile_id);

        if ($nb) {
            echo "$nb";
        } 
    }



?>