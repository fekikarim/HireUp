<?php

require_once __DIR__ . '/../../../Controller/profileController.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
include_once __DIR__ . '/../../../Controller/meeting_con.php';
include_once __DIR__ . '/../../../Controller/meeting_participants_con.php';
include_once __DIR__ . '/../../../Controller/JobC.php';
include_once __DIR__ . '/../../../Controller/schedualC.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

$userC = new userCon("user");
$profileController = new ProfileC();
$meetingC = new MeetingCon("meetings");
$meetingPrC = new MeetingParticipantsCon("meeting_participants");
$jobC = new JobController();
$schedualC = new ScheduleController();


if (isset($_GET['id'])) {
    $sch_id = htmlspecialchars($_GET['id']);
}

$schedual_data = $schedualC->getScheduleById($sch_id);
//var_dump($schedual_data);
$meeting_id = $schedual_data['meeting_id'];

$meeting = $meetingC->getMeeting($meeting_id);

$user_meeting = $meetingPrC->getParticipant($schedual_data['profile_id'], $schedual_data['meeting_id']);

$current_meeting_job = $jobC->getJobById($meeting['meeting_job_id']);

    $meeting_user_role = $user_meeting['profile_role'];
    if ($meeting_user_role == 'moderator') {
        $meeting_link = './../meeting/make_moderator_meeting.php?meeting_id=' . $user_meeting['meeting_id'] . '&profile_id=' . $user_meeting['profile_id'];
    } else {
        $meeting_link = './../meeting/make_non_moderator_meeting.php?meeting_id=' . $user_meeting['meeting_id'] . '&profile_id=' . $user_meeting['profile_id'];
    }


echo $meeting_link;

header('Location: '. $meeting_link);

?>