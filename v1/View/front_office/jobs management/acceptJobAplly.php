<?php

require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';

// Check if the form is submitted via POST method

    // Retrieve data from the form
    $userId = htmlspecialchars($_POST["userId"]);
    $jobId = htmlspecialchars($_POST["jobId"]);
    $modId = htmlspecialchars($_POST["mod_id"]);
    $meeting_desc = htmlspecialchars($_POST["meeting_desc"]);
    $meeting_at = htmlspecialchars($_POST["meeting_at"]);

    // Instantiate ApplyController
    $applyController = new ApplyController();
    $NotificationCon = new NotificationCon("notifications");
    $jobC = new JobController();

    // Check if the application exists for the given job and user
    $applyId = $applyController->getApplyIdByJobIdAndProfileId($jobId, $userId);

    // If apply ID exists, delete the application
    if ($applyId !== null) {
        $applyController->updateApplyStatus($applyId, 'interview');

        $current_job = $jobC->getJobById($jobId);
        
        // send notification to the user
        $notification = new Notification(
            $NotificationCon->generateNotificationId(5),
            'HireUp',
            $userId, // it the user profile id
            "Congrats! Your application for '".$current_job['title']."' is accepted. Check for interview details!",
            '#',
            'true'
        );
            
        $NotificationCon->addNotification($notification);

        header('Location: ./../meeting/Schedule_a_meeting.php?mod_profile_id='.$modId.'&profile_id='.$userId.'&meeting_job_id='.$jobId.'&meeting_desc='.$meeting_desc.'&meeting_at='.$meeting_at);
        
        
        echo "Error deleting application.";
        //header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();

    } else {
        echo "Application not found.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

?>
