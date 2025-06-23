<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Start output buffering
ob_start();

include_once __DIR__ . '/../../../Controller/reclamation_con.php';
include_once __DIR__ . '/../../../Model/reclamation.php';
include_once __DIR__ . '/../../../Controller/user_con.php';


// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");
$userC = new userCon("user");

// Création d'une instance de la classe Event 
$reclamation = null;

if (
    isset($_POST["sujet"]) &&
    isset($_POST["description"]) &&
    isset($_POST["id_user"])
) {
    if (
        !empty($_POST['sujet']) &&
        !empty($_POST["description"]) &&
        !empty($_POST["id_user"])
    ) {
        // Check if the description contains bad words
       
            $currentDate = date("Y-m-d");
            $current_rec_id = $recC->generateRecId(5);
        
            $reclamation = new Reclamation(
                $current_rec_id,
                $_POST['sujet'],
                $_POST['description'],
                $currentDate,
                "pending",
                $_POST['id_user']
            );        
        
            $recC->addRec($reclamation);
            
            $current_rec = $recC->getRec($current_rec_id);
            $current_user = $userC->getUser($_POST['id_user']);
            $email_to_send_to = $current_user['email'];
            $userC->sendReclamationMail($email_to_send_to, $current_rec);
        
        header('Location: ../../../View/front_office/reclamation/rec_list.php');
        exit();
    //ob_end_flush();
}
}