<?php

require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';

// Check if the form is submitted via POST method

    // Retrieve data from the form
    $userId = $_GET["userId"];
    $jobId = $_GET["jobId"];
    var_dump($userId);
    var_dump($jobId);

    // Instantiate ApplyController
    $applyController = new ApplyController();
    $NotificationCon = new NotificationCon("notifications");
    $jobC = new JobController();

    // Check if the application exists for the given job and user
    $applyId = $applyController->getApplyIdByJobIdAndProfileId($jobId, $userId);

    // If apply ID exists, delete the application
    if ($applyId !== null) {
        $deleted = $applyController->deleteApply($applyId);
        
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
