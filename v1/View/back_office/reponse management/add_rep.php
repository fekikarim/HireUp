<?php

include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';
include_once __DIR__ . '/../../../Controller/user_con.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");
$userC = new userCon("user");

// Création d'une instance de la classe Event
$reponse = null;

$id=$_POST['id_reclamation'];
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
        
        $current_rep_id = $repC->generateRepId(5);

        $reponse = new Reponse(
            $current_rep_id,
            $_POST['contenu'],
            date("Y-m-d"),
            $_POST['id_user'],
            $_POST['id_reclamation']
            
        );        

        $repC->addRep($reponse);
        $repC->updateStatus($_POST['id_reclamation']);

        $current_rep = $repC->getRep($current_rep_id);
        $current_user = $userC->getUser($_POST['id_user']);
        $email_to_send_to = $current_user['email'];
        $userC->sendRepMail($email_to_send_to, $current_rep);

        $success_message = "answer added successfully!";
        header("Location: ../../../View/back_office/reponse management/reps_management.php?id=$id");
        exit(); // Make sure to stop further execution after redirection
    } else {
        // returning an error
        $error_message = "Failed to add the answer. Please try again later.";
        header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
        exit(); // Make sure to stop further execution after redirection
    }
}


?>