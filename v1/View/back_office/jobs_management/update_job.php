<?php
// Include the JobController
require_once __DIR__ . '/../../../Controller/JobC.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_job_id"])) {
    // Get job ID and new information from form
    $jobId = $_POST["update_job_id"];
    $title = $_POST["update_job_title"];
    $company = $_POST["update_company"];
    $location = $_POST["update_location"];
    $description = $_POST["update_description"];
    $salary = $_POST["update_salary"];
    $category = $_POST["update_category"];
    // Create an instance of JobController
    $jobController = new JobController();

    if (!empty($_FILES['job_image']['name']) && $_FILES['job_image']['error'] === 0) {

        // Get profile photo and cover data
        $job_image_tmp_name = $_FILES['job_image']['tmp_name'];
        $job_image = file_get_contents($job_image_tmp_name);

        // Only echo the result if the job update is successful
        $result = $jobController->updateJob($job_id, $title, $company, $location, $description, $salary, $category, $job_image);

        if ($result !== false) {
            // Redirect to prevent form resubmission

            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        }
    }

    
}