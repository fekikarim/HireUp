<?php 

    require_once __DIR__ . '/../../../Controller/notification_con.php';

    if (isset($_POST['receiver_id']) && isset($_POST['new_val'])) {
        $receiver_id = $_POST['receiver_id'];
        $new_val = $_POST['new_val'];

        $NotificationCon = new NotificationCon("notifications");

        $NotificationCon->updateNotificationsByReceiverId($receiver_id, $new_val);

        echo 'done';
    }

    else{
        echo 'ff';
    }


?>