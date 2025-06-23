<?php
include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $res = $repC->deleteRep($current_id);

    if ($res){
        $success_message = "Answer deleted successfully!";
        header('Location: ../../../View/back_office/reponse management/reps_management.php?success_global=' . urlencode($success_message));
        exit();
    }
    else{
        $error_message = "Failed to delete the Answer. Please try again later.";
        header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
        exit();
    }
}
else{
    $error_message = "Failed to delete the Answer. Please try again later.";
    header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>