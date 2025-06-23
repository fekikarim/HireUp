<?php

require_once __DIR__ . '/../../../Controller/applyController.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $jobId = $_POST["jobId"];
    $userId = $_POST["userId"];

    // Instantiate ApplyController
    $applyController = new ApplyController();

    // Check if the application exists for the given job and user
    $applyId = $applyController->getApplyIdByJobIdAndProfileId($jobId, $userId);

    // If apply ID exists, delete the application
    if ($applyId !== null) {
        $deleted = $applyController->deleteApply($applyId);
        
        if ($deleted) {
            echo "Application deleted successfully.";
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
} else {
    // Handle invalid request method (optional)
    echo "Invalid request method.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>
