<?php

include_once __DIR__ . "/../../../Controller/commentaireC.php";
include_once __DIR__ . "/../../../Model/commentaire.php";

require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/articleC.php';
require_once __DIR__ . '/../../../Model/notification.php';

// Créer une instance du contrôleur
$commentaireC = new CommentaireC();
$NotificationCon = new NotificationCon("notifications");
$profileController = new ProfileC();
$articleC = new ArticleC();


$erreur_msg = "";

if (
    isset($_POST["id_article"]) &&
    isset($_POST["sender_id"]) &&
    isset($_POST["contenu"])
) {
    // Continuer avec les autres validations des champs du formulaire
    if (
        !empty($_POST["id_article"]) &&
        !empty($_POST["sender_id"]) &&
        !empty($_POST["contenu"])
    ) {


        // Création de l'objet Commentaire
        $commentaire = new Commentaire();
        $commentaire->setIdArticle($_POST["id_article"]);
        $commentaire->setSenderId($_POST["sender_id"]);
        $commentaire->setContenu($_POST["contenu"]);
        $commentaire->setDateCommentaire(date("Y-m-d"));

        // Ajout du commentaire à la base de données
        $commentaireC->addCommentaire($commentaire);

        $article = $articleC->getArtical($_POST["id_article"]);
        
        if ($_POST["sender_id"] != $article['auteur_id']){
            
            $myStrings = [
                " just commented on your post! Dive in and join the conversation!", 
                " left a comment on your post! Time to engage!", 
                " just dropped a comment on your post! Join the discussion!", 
            ];
            $selectedString = $NotificationCon->chooseRandomString($myStrings);
            if ($selectedString !== null) {
                echo "Selected string: $selectedString";
            } 

            $notification = new Notification(
                $NotificationCon->generateNotificationId(5),
                $_POST["sender_id"],
                $article['auteur_id'],
                $selectedString,
                '#',
                'false'
            );
        
            $NotificationCon->addNotification($notification);
    
        }


        
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        // Afficher un message d'erreur si les champs obligatoires ne sont pas tous remplis
        $erreur_msg .= 'Erreur : Veuillez remplir tous les champs obligatoires.<br>';
    }
}

?>
