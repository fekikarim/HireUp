<?php

require_once __DIR__ . '/../../../Controller/applyController.php';

$applyController = new ApplyController();

if (isset($_POST['apply_id']) && isset($_POST['apply_desc'])) {
    $id = $_POST['apply_id'];

    $applyController->updateApplyDesc($id, $_POST['apply_desc']);

    header("Location: ./applyJobs_list.php");
} else {
    header("Location: ./applyJobs_list.php");
}


?>