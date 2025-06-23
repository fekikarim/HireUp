<?php

require_once __DIR__ . '/../../../Controller/applyController.php';
require_once __DIR__ . '/../../../Controller/JobC.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';

require_once __DIR__ . '/../../../Controller/resume_con.php';
require_once __DIR__ . '/../../../Model/resume.php';


if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $jobId = $_POST["jobId"];
    $userId = $_POST["userId"];                                                                                                                                                                                                                 
    $applyTime = date("Y-m-d H:i:s"); // Current date and time
    $status = "pending";
    $apply_desc = $_POST["apply_desc"];

    // Instantiate ApplyController
    $applyController = new ApplyController();
    $jobC = new JobController();
    $profileC = new ProfileC();

    $NotificationCon = new NotificationCon("notifications");
    $notification = null;

    $ResumeCon = new ResumeController();
    $resume = null;

    // Generate apply ID
    $applyId = $applyController->generateApplyId(6); // Length 6

    // Add apply with the retrieved data
    $added = $applyController->addApply($applyId, $userId, $jobId, $applyTime, $status, $apply_desc);

    //add the resume
    if (!empty($_FILES['resume_data']['tmp_name'])) {
        // Get resume data
        $resume_tmp_name = $_FILES['resume_data']['tmp_name'];
        $resume_data = file_get_contents($resume_tmp_name);
    } else {
        // Set image data to null if no image is uploaded
        $resume_data = null;
    }

    if ($resume_data != null) {

        $json_data = $ResumeCon->makeResumeJsonDataByData($resume_data);

        if ($json_data == null) {

            $resumeId = $ResumeCon->generateResumeId(6);
            $resume = new Resume(
                $resumeId,
                $userId,
                $applyId, // it the user profile id
                $resume_data,
                date("Y-m-d H:i:s"),
                null
            );

            $ResumeCon->addResumeNoJson($resume);

        } else {

            $resumeId = $ResumeCon->generateResumeId(6);
            $resume = new Resume(
                $resumeId,
                $userId,
                $applyId, // it the user profile id
                $resume_data,
                date("Y-m-d H:i:s"),
                $json_data
            );

            $ResumeCon->addResume($resume);

        }

    }

    // Check if apply was successfully added
    if ($added) {
        echo "Application submitted successfully.";

        $current_job = $jobC->getJobById($jobId);
        $job_auther_profile = $profileC->getProfileById($current_job['jobs_profile']);


        // send notification to the user
        $notification = new Notification(
            $NotificationCon->generateNotificationId(5),
            'HireUp',
            $userId, // it the user profile id
            "Success! You've applied for '".$current_job['title']."'. Stay tuned for updates!",
            '#',
            'true'
        );
        
        $NotificationCon->addNotification($notification);

        $notification_for_job_owner = new Notification(
            $NotificationCon->generateNotificationId(5),
            'HireUp',
            $job_auther_profile['profile_id'], // it the job owner profile id
            "New applicant for '".$current_job['title']."'! Check your inbox.",
            '#',
            'true'
          );
        
        $NotificationCon->addNotification($notification_for_job_owner);

        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Error submitting application.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
} else {
    // Handle invalid request method (optional)
    echo "Invalid request method.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>
