<?php

require_once __DIR__ . '/../../../Controller/resume_con.php';

$resumeController = new ResumeController();

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $data = $resumeController->getApplyRankInfos($id);

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Encode the data as JSON and output it
    echo json_encode($data);
} else {
    echo json_encode(array('error'=> 'error'));
}

?>