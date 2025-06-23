<?php

include_once __DIR__ . '/../../../Controller/dmd_con.php';
require_once __DIR__ . '/../../../Model/notification.php';
include_once __DIR__ . '/../../../Model/dmd.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/pub_con.php';

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon();
$NotificationCon = new NotificationCon("notifications");
$profileController = new ProfileC();
$pubb = new pubCon("pub");

// Création d'une instance de la classe Dmd
$dmd = null;

$dmdd->updatePay($_GET['iddemande'], 'payed');

$dmd = $dmdd->getdmd($_GET['iddemande']);
$profile_id = $profileController->getProfileIdByUserId($dmd['user_id']);

$ad_id = $pubb->get_pup_id_by_dmd_id($_GET['iddemande']);

$notification = new Notification(
    $NotificationCon->generateNotificationId(5),
    'HireUp',
    $profile_id,
    'Payment for ad ID [' . $ad_id . '] has been successfully processed',
    '#',
    'true'
  );

  $NotificationCon->addNotification($notification);

$success_message = "accepted seccussfully";
header('Location: view_ads.php?success_global=' . urlencode($success_message));
exit(); // Make sure to stop further execution after redirection

