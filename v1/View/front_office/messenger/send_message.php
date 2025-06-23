<?php 

include_once __DIR__ . '/../../../Controller/messaging_con.php';
include_once __DIR__ . '/../../../Model/message.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$sender_profile_id = "";
if (isset($_GET['user_profile_id'])){
    $sender_profile_id = $_GET['user_profile_id'];
}


$send_to_profile_id = "";
if (isset($_POST['msg_to_profile_id'])){
    $send_to_profile_id = $_POST['msg_to_profile_id'];
}

$msg = "";
if (isset($_POST['msg_text_area'])){
    $msg_content = $_POST['msg_text_area'];
}

$go_back_to = "";
if (isset($_POST['go_back_to'])){
    $go_back_to = $_POST['go_back_to'];
}

$msgC = new MessagingCon("messages");

$msg = new Message(
    $msgC->generateId(5),
    $sender_profile_id,
    $send_to_profile_id,
    $msg_content,
    ''
);

$msgC->sendMessage($msg);


header("Location: messaging.php?reciever_id=" .  urlencode($send_to_profile_id) . "&go_back_to=" . urlencode($go_back_to));



?>