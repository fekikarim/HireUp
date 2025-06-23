<?php

require_once __DIR__ . '/../../../Controller/resume_con.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$ResumeCon = new ResumeController();
$resume_data = null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file is uploaded
    if (isset($_FILES['resumeFile']) && $_FILES['resumeFile']['error'] === UPLOAD_ERR_OK) {
        
        // Get resume data
        $resume_tmp_name = $_FILES['resumeFile']['tmp_name'];
        $resume_data = file_get_contents($resume_tmp_name);

    } else if (isset($_FILES['resumeFile1']) && $_FILES['resumeFile1']['error'] === UPLOAD_ERR_OK) {
        
        // Get resume data
        $resume_tmp_name = $_FILES['resumeFile1']['tmp_name'];
        $resume_data = file_get_contents($resume_tmp_name);

    } else {
        // No file uploaded or an error occurred during upload
        echo "No file uploaded or an error occurred during upload.";
    }

    if ($resume_data != null) {
        $json_data = $ResumeCon->makeResumeJsonDataByData($resume_data);
        $data = $ResumeCon->getResumesSkillsRankingByAllCategory($json_data);
        
        for ($i = 0; $i < count($data); $i++) {
            $val = $ResumeCon->getResumesSkillsRankingByCategoryValue($json_data, $data[$i]['category_id']);
            $data[$i]['rank'] = $val;
        }

        $data_sorted = $ResumeCon->sortResumesByRank($data);

        $jsonData = json_encode($data_sorted);

        // Send the JSON response
        header('Content-Type: application/json');
        echo $jsonData;

        //var_dump($data_sorted);

    } 

} else {
    // If the form is not submitted via POST method
    echo "Form submission method not allowed.";
}
?>

