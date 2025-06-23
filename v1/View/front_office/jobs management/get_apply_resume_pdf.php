<?php

require_once __DIR__ . '/../../../Controller/resume_con.php';

$resumeController = new ResumeController();

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $data = $resumeController->getResumeByapplyId($id);
    $pdf_blob = $data["content"];

    // Output the PDF blob data
    header('Content-Type: application/pdf');
    echo $pdf_blob;

} else {
    echo 'error';
}

?>