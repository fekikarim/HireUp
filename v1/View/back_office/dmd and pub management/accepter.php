<?php

include_once __DIR__ . '/../../../Controller/dmd_con.php';
include_once __DIR__ . '/../../../Model/dmd.php';

require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Model/notification.php';

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon();
$NotificationCon = new NotificationCon("notifications");
$profileController = new ProfileC();

// Création d'une instance de la classe Dmd
$dmd = null;

        $dmdd->updateStatus($_GET['id'], 'accepted');
        $dmdd->updatePay($_GET['id'], 'pending');

        $dmd = $dmdd->getdmd($_GET['id']);
        $profile_id = $profileController->getProfileIdByUserId($dmd['user_id']);

        $notification = new Notification(
                $NotificationCon->generateNotificationId(5),
                'HireUp',
                $profile_id,
                'Your request for ID [' . $dmd['iddemande'] . '] has been approved',
                '#',
                'true'
              );
          
              $NotificationCon->addNotification($notification);

        $success_message = "accepted seccussfully";
        header('Location: dmd_management.php?success_global=' . urlencode($success_message));
        exit(); // Make sure to stop further execution after redirection

        // returning an error
        //$error_message = "Failed to add the answer. Please try again later.";
        //header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
        //exit(); // Make sure to stop further execution after redirection



?>