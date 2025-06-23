<?php

include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");

// Création d'une instance de la classe Event
$reponse = null;

if (
    isset($_POST["contenu"]) &&
    isset($_POST["id_user"])&&
    isset($_POST["id_reclamation"])
) {
    if (
        !empty($_POST['contenu']) &&
        !empty($_POST["id_user"]) &&
        !empty($_POST["id_reclamation"])
    ) {
        
        $currentDate = date("Y-m-d");

        $reponse = new Reponse(
            $repC->generateRepId(5),
            $_POST['contenu'],
            $currentDate,
            $_POST['id_user'],
            $_POST['id_reclamation']
        );        

        $repC->addRep($reponse);
        $success_message = "Answer added successfully!";
        header('Location: ../../../View/front_office/reponse/reponse.php?success_global=' . urlencode($success_message));
        exit(); // Make sure to stop further execution after redirection
    } else {
        // returning an error
        $error_message = "Failed to add the answer. Please try again later.";

        header('Location: ../../../View/front_office/reponse/reponse.php?error_global=' . urlencode($error_message));
        exit(); // Make sure to stop further execution after redirection
    }
}
else{
    echo("dfg");
}


?>