<?php
include_once __DIR__ . '/../../../Controller/reclamation_con.php';
include_once __DIR__ . '/../../../Model/reclamation.php';

// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $recC->generatePDF($current_id);

    header('Location: ../../../View/back_office/reclamations managment/recs_management.php');

    
}
else{

    header('Location: ../../../View/back_office/reclamations managment/recs_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>