<?php

require_once __DIR__ . '/../../../Controller/JobC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user's profile ID from the form
    $userProfileId = $_POST["jobs_profile"];

    // Other job details from the form
    $title = $_POST["job_title"];
    $company = $_POST["company"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $salary = $_POST["salary"];
    $category = $_POST["category"];
    $lng = $_POST["longitude"];
    $lat = $_POST["latitude"];

    // Include the controller file
    $jobController = new JobController();
    $job_id = $jobController->generateJobId(7);

    if (!empty($_FILES['job_image']['name'])) {
        // Get profile photo and cover data
        $job_image_tmp_name = $_FILES['job_image']['tmp_name'];
        $job_image = file_get_contents($job_image_tmp_name);

        // Only redirect if the job creation is successful
        $result = $jobController->createJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $userProfileId, $lng, $lat);

        if ($result !== false) {
            // Redirect to prevent form resubmission
            header("Location: ./myJobs_list.php?creation-image=true");
            exit;
        }
    } else {
        // Get profile photo and cover data
        $job_image = null;

        // Only redirect if the job creation is successful
        $result = $jobController->createJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image, $userProfileId, $lng, $lat);

        if ($result !== false) {
            // Redirect to prevent form resubmission
            header("Location: ./myJobs_list.php?creation-job=true");
            exit;
        }
    }
}
?>
