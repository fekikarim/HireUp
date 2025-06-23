<?php 

    require_once __DIR__ . '/../../../Controller/messaging_con.php';

    if (isset($_POST['receiver_id']) && isset($_POST['new_val'])) {
        $receiver_id = $_POST['receiver_id'];
        $sender_id = $_POST['sender_id'];
        $new_val = $_POST['new_val'];

        $msgC = new MessagingCon("messages");

        $msgC->updateMessagesByReceiverIdAndSenderId($receiver_id, $sender_id, $new_val);

        echo 'done';
    }

    else{
        echo 'ff';
    }


?>