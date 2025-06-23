<?php 
require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
include_once __DIR__ . '/../../../Controller/meeting_con.php';
include_once __DIR__ . '/../../../Controller/meeting_participants_con.php';
include_once __DIR__ . '/../../../Model/meeting.php';
include_once __DIR__ . '/../../../Model/meeting_participants.php';
require_once __DIR__ . '/../../../Controller/schedualC.php';

require_once __DIR__ . '/../../../Controller/JobC.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}


$userC = new userCon("user");
$profileController = new ProfileC();
$meetingC = new meetingCon('meetings');
$meeting_participantsC = new MeetingParticipantsCon('meeting_participants');
$scheduleController = new ScheduleController();

$jobController = new JobController();

$mod_user_id = '';
$mod_user_profile_id = '';

$user_id = '';
$user_profile_id = '';

//get user_profile id
if (isset($_GET['mod_profile_id'])) {
  $mod_profile_id = htmlspecialchars($_GET['mod_profile_id']);
  $mod_user_id = $userC->get_user_id_by_profile_id($mod_profile_id);
}

if (isset($_GET['profile_id'])) {
    $profile_id = htmlspecialchars($_GET['profile_id']);
    $user_id = $userC->get_user_id_by_profile_id($profile_id);
}

$meeting_description = '';
if (isset($_GET['meeting_desc'])) {
  $meeting_description = htmlspecialchars($_GET['meeting_desc']);
}

$meeting_at = '';
if (isset($_GET['meeting_at'])) {
  $meeting_at = htmlspecialchars($_GET['meeting_at']);
}

$meeting_job_id = '';
if (isset($_GET['meeting_job_id'])) {
  $meeting_job_id = htmlspecialchars($_GET['meeting_job_id']);
}

$mod_profile_data = $profileController->getProfileById($mod_profile_id);
$mod_user_data = $userC->getUser($mod_user_id);
$profile_data = $profileController->getProfileById($profile_id);
$user_data = $userC->getUser($user_id);

$meeting_id = $meetingC->generateMeetingId(5);
$room_name = $meetingC->generateRandomWord(10);
$current_time_date = date('Y-m-d H:i:s', time());
$current_time_date_plus_one_hour = date('Y-m-d H:i:s', strtotime($current_time_date . ' +1 hour'));

$current_meeting = new Meeting(
    $meeting_id,
    $room_name,
    $current_time_date,
    $meeting_description,
    $meeting_at,
    $meeting_job_id
);


///////
$job_data = $jobController->getJobById($meeting_job_id);
//////


$job_title = $job_data['title'];
$event_title = "Meeting for " . $job_title;
$mod_description = "Meeting with ". $profile_data['profile_first_name'] . ' ' . $profile_data['profile_family_name'] . " for " . $job_title;
$normal_user_description = "Meeting with ". $mod_profile_data['profile_first_name'] . ' ' . $mod_profile_data['profile_family_name'] . " for " . $job_title;
$meeting_start_datetime = $current_time_date;
$meeting_end_datetime = $current_time_date_plus_one_hour;

// make a schedule for moderator
$id_sch_mod = $scheduleController->generateScheduleId(5);
$response = $scheduleController->createSchedule($id_sch_mod, $event_title, $mod_description, $meeting_start_datetime, $meeting_end_datetime, $mod_profile_id, $meeting_id);

// make a schedule for non moderator
$id_sch_non_mod = $scheduleController->generateScheduleId(5);
$response = $scheduleController->createSchedule($id_sch_non_mod, $event_title, $normal_user_description, $meeting_start_datetime, $meeting_end_datetime, $profile_id, $meeting_id);

$mod_meeting_participant = new MeetingParticipants(
    $mod_profile_id,
    $meeting_id,
    'moderator',
    $current_time_date,
    $id_sch_mod
);

$normal_meeting_participant = new MeetingParticipants(
    $profile_id,
    $meeting_id,
    'non moderator',
    $current_time_date,
    $id_sch_non_mod
);


$meetingC->addMeeting($current_meeting);
$meeting_participantsC->addParticipant($mod_meeting_participant);
$meeting_participantsC->addParticipant($normal_meeting_participant);


header('Location: ./../jobs management/career_explorers.php');




?>