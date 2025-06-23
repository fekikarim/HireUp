<?php
include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $repC->generatePDF($current_id);

    header('Location: ../../../View/back_office/reponse management/reps_management.php');

    
}
else{

    header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>