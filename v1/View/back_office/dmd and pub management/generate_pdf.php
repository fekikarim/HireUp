<?php
include '../../../Controller/dmd_con.php';
include '../../../Model/dmd.php';

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon("dmds");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $dmdd->generatePDF($current_id);

    //header('Location: ../../../View/back_office/ads managment/dmd_management.php');
    echo "aaaaaa";
    
}
else{
    echo "hjhff";
    //header('Location: ../../../View/back_office/ads managment/dmd_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>