<?php

include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");

// Création d'une instance de la classe Event
$reponse = null;

if (isset($_GET['id'])){
    $current_id = $_GET['id'];
}


if (
    isset($_POST["contenu"]) &&
    isset($_POST["date_reponse"]) &&
    isset($_POST["id_user"]) &&
    isset($_POST["id_reclamation"]) 
) {
    if (
        !empty($_POST['contenu']) &&
        !empty($_POST["date_reponse"]) &&
        !empty($_POST["id_user"]) &&
        !empty($_POST["id_reclamation"]) 
        
    ) {
       
        $reponse = new Reponse(
            $current_id,
            $_POST['contenu'],
            $_POST['date_reponse'],
            $_POST['id_user'],
            $_POST['id_reclamation'],
            
        );

        $repC->updateRep($reponse, $current_id);
        $success_message = "Answer Updated successfully!";
        header('Location: ../../../View\back_office\reponse management\reps_management.php?success_global=' . urlencode($success_message));
    } else {
        $error = "Missing information";echo "hdjd";
    }

    echo "yyyy";


}else{
    echo "aaaaaa";
}


?>