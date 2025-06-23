<?php

require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/meeting_con.php';
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';

require_once __DIR__ . '/../../../Controller/schedualC.php';


// Check if the form is submitted via POST method

    // Retrieve data from the form
    $userId = htmlspecialchars($_GET["userId"]);
    $jobId = htmlspecialchars($_GET["jobId"]);
    $meeting_id = htmlspecialchars($_GET["meeting_id"]);

    // Instantiate ApplyController
    $applyController = new ApplyController();
    $meetingC = new MeetingCon('meetings');
    $NotificationCon = new NotificationCon("notifications");
    $jobC = new JobController();

    $scheduleController = new ScheduleController();

    // Check if the application exists for the given job and user
    $applyId = $applyController->getApplyIdByJobIdAndProfileId($jobId, $userId);

    // If apply ID exists, delete the application
    if ($applyId !== null) {
        $deleted = $applyController->deleteApply($applyId);
        $meetingC->deleteMeeting($meeting_id);

        //delete the scheduled meeting
        $scheduleController->deleteScheduleWhereMeetingId($meeting_id);
        
        if ($deleted) {
            echo "Application deleted successfully.";

            $current_job = $jobC->getJobById($jobId);
            // send notification to the user
            $notification = new Notification(
                $NotificationCon->generateNotificationId(5),
                'HireUp',
                $userId, // it the user profile id
                "Apologies, your application for '".$current_job['title']."' has been declined. Best of luck in your search!",
                '#',
                'true'
            );
            
            $NotificationCon->addNotification($notification);

            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            echo "Error deleting application.";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    } else {
        echo "Application not found.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

?>
