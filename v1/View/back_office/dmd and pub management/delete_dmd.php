<?php
include '../../../Controller/dmd_con.php';
include '../../../Model/dmd.php';

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon("dmd");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $res = $dmdd->deletedmd($current_id);

    if ($res){
        $success_message = "demande deleted successfully!";
        header('Location: ../../../View/back_office/ads managment/dmd_management.php?success_global=' . urlencode($success_message));
        exit();
    }
    else{
        $error_message = "Failed to delete the demande. Please try again later.";
        header('Location: ../../../View/back_office/ads managment/dmd_management.php?error_global=' . urlencode($error_message));
        exit();
    }
}
else{
    $error_message = "Failed to delete the demande. Please try again later.";
    header('Location: ../../../View/back_office/ads managment/dmd_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>