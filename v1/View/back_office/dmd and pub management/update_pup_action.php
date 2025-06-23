<?php
include '../../../Controller/pub_con.php';
include '../../../Model/pub.php';

// Création d'une instance du contrôleur des événements
$pubb = new pubCon("pubs");

// Création d'une instance de la classe Event
$pub = null;

if (isset($_GET['id'])){
    $current_id = $_GET['id'];
}

if (
    isset($_POST["titre"]) &&
    isset($_POST["contenu"]) &&
    isset($_POST["dat"]) &&
    isset($_POST["id_dmd"])
) {
    if (
        !empty($_POST['titre']) &&
        !empty($_POST["contenu"]) &&
        !empty($_POST["dat"]) &&
        !empty($_POST["id_dmd"])
    ) {
       
        $pub = new Pub(
            $current_id,
            $_POST['titre'],
            $_POST['contenu'],
            $_POST['dat'],
            $_POST['id_dmd']
        );

        $pubb->updatepub($pub, $current_id);
        header('Location:../../../View/back_office/ads managment/pub_management.php');
    } else {
        $error = "Missing information";
    }
}


?>