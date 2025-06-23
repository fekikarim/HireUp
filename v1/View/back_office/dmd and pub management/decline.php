<?php

include '../../../Controller/dmd_con.php';
include '../../../Model/dmd.php';

require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Model/notification.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon();
$NotificationCon = new NotificationCon("notifications");
$profileController = new ProfileC();

// Création d'une instance de la classe Dmd
$dmd = null;
$dmd = $dmdd->getdmd($_GET['id']);
$profile_id = $profileController->getProfileIdByUserId($dmd['user_id']);

        $dmdd->updateStatus($_GET['id'], 'declined');
        $notification = new Notification(
                $NotificationCon->generateNotificationId(5),
                'HireUp',
                $profile_id,
                'Your request for ID [' . $dmd['iddemande'] . '] has been rejected',
                '#',
                'true'
        );
          
        $NotificationCon->addNotification($notification);
        $success_message = "declined seccussfully";
        header('Location: dmd_management.php?success_global=' . urlencode($success_message));
        exit(); // Make sure to stop further execution after redirection

        // returning an error
        //$error_message = "Failed to add the answer. Please try again later.";
        //header('Location: ../../../View/back_office/reponse management/reps_management.php?error_global=' . urlencode($error_message));
        //exit(); // Make sure to stop further execution after redirection



?>