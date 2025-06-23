<?php 

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

require __DIR__ . './../../../Controller/meeting_con.php';

$meetingC = new MeetingCon('meetings');

if (isset($_GET['room_name'])) {
    $room_name = htmlspecialchars($_GET['room_name']);
} else {
    $room_name = $meetingC->generateRandomWord(10);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = htmlspecialchars($_POST['userid']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $room = htmlspecialchars($_POST['room']);
    $moderator = htmlspecialchars($_POST['moderator']);


// Sample data
/*$data = [
    'userid' => '123',
    'username' => 'example_user',
    'email' => 'user@example.com',
    'room' => '*',
    'moderator' => true,
    'cookies' => 'vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853',
    'room_name' => $room_name,
];*/
$data = [
    'userid' => $userid,
    'username' => $username,
    'email' => $email,
    'room' => $room,
    'moderator' => ($_POST['moderator'] == 'moderator') ? true : false,
    'cookies' => 'vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853',
    'room_name' => $room_name,
];

// Secret key (replace with your own secret)
$secretKey = 'HireUp by be.net';

// Encode the array to JWT-like token
$jwt = $meetingC->encodeJWT($data, $secretKey);

$meeting_link = $current_url . 'view/front_office//meeting/meeting_room.php?jwt=' . urlencode($jwt);

echo '<a href="'.$meeting_link.'">Meeting</a>';

header('Location: '. $meeting_link);

}

?>