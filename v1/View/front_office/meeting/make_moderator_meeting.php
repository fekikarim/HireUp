<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireUp Moderator Meeting</title>
</head>

<?php 
require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
include_once __DIR__ . '/../../../Controller/meeting_con.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


$userC = new userCon("user");
$profileController = new ProfileC();
$meetingC = new MeetingCon('meetings');

$user_id = '';
$user_profile_id = '';
$meeting_id = '';

//get user_profile id
if (isset($_GET['profile_id'])) {
  $profile_id = htmlspecialchars($_GET['profile_id']);
  $user_id = $userC->get_user_id_by_profile_id($profile_id);
}

if (isset($_GET['meeting_id'])) {
    $meeting_id = htmlspecialchars($_GET['meeting_id']);
} else {
    exit('No meeting id');
}

$profile_data = $profileController->getProfileById($profile_id);
$user_data = $userC->getUser($user_id);
$meeting_data = $meetingC->getMeeting($meeting_id);


?>
<body>
    <form id="autoSubmitForm" action="make_a_meeting_link.php?room_name=<?php echo $meeting_data['room_name'] ; ?>" method="POST" onsubmit="addUserToMeeting()">
        <input type="hidden" name="userid" value="<?php echo $profile_id;?>">
        <input type="hidden" name="username" value="<?php echo $profile_data['profile_first_name'] . " " . $profile_data['profile_family_name'];?>">
        <input type="hidden" name="email" value="<?php echo $user_data['email'];?>">
        <input type="hidden" name="room" value="<?php echo '*';?>">
        <input type="hidden" name="moderator" value="<?php echo 'moderator';?>">
    </form>

    <div id="response"></div>

    <script>
        // Function to auto-submit the form
        function autoSubmitForm() {
            document.getElementById('autoSubmitForm').submit();
        }

        // Wait for the DOM to load
        document.addEventListener('DOMContentLoaded', function() {
            autoSubmitForm();
        });
    </script>

</body>
</html>
