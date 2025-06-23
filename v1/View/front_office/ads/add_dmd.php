<?php

include '../../../Controller/dmd_con.php';
include '../../../Controller/pub_con.php';
include '../../../Model/dmd.php';
include '../../../Model/pub.php';

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(0, '/', '', true, true);
	session_start();
}

$user_id = '';
if (isset($_SESSION['user id'])) {
	$user_id = htmlspecialchars($_SESSION['user id']);
}

// Création d'une instance du contrôleur des événements
$dmdd = new dmdCon();
$pubb = new pubCon("pub");

// Création d'une instance de la classe Dmd
$dmd = null;
$pub = null;

if (
    isset($_POST["titre"]) &&
    isset($_POST["contenu"]) &&
    isset($_POST["objectif"]) &&
    isset($_POST["dure"]) &&
    isset($_POST["budget"]) &&
    isset($_POST["link"]) &&
    isset($_FILES['image_publication']) && // Vérifie si le champ de fichier est présent
    $_FILES['image_publication']['error'] === UPLOAD_ERR_OK // Vérifie si le téléchargement du fichier s'est bien déroulé
) {
    if (
        !empty($_POST['titre']) &&
        !empty($_POST["contenu"]) &&
        !empty($_POST["objectif"]) &&
        !empty($_POST["dure"]) &&
        !empty($_POST["budget"]) &&
        !empty($_POST["link"]) 
    ) {   
        
        // Get profile photo and cover data
        $image_publication_tmp_name = $_FILES['image_publication']['tmp_name'];
        $image_publication_data = file_get_contents($image_publication_tmp_name);
        
        $id_dmd = $dmdd->generatedmdId(5);
        $dmd = new Dmd(
            $id_dmd,
            $_POST['titre'],
            $_POST['contenu'],
            $_POST['objectif'],
            $_POST['dure'],
            $_POST['budget'],
            $image_publication_data,
            $user_id
        );

        # do some checks first
        # check titre existence
        if ($dmdd->dmdExists($dmd->get_iddemande(), config::getConnexion())){
            $error_titre= "title already exists";
            header('Location: dmd_management.php?error_titre=' . urlencode($error_titre) . '&titre=' . urlencode($dmd->get_titre()));
            exit(); // Make sure to stop further execution after redirection
        }

        $pub = new Pub(
            $pubb->generatepubId(5),
            $_POST['titre'],
            $_POST['contenu'],
            date("Y-m-d"),
            $id_dmd,
            $_POST["link"]     
        );

        if ($pubb->titreExists($pub->get_titre())){
            $error_titre= "title already exists";
            header('Location: ads.php?error_titre=' . urlencode($error_titre) . '&titre=' . urlencode($dmd->get_titre()));
            exit(); // Make sure to stop further execution after redirection
        }

        $dmdd->adddmd($dmd);
        $pubb->addpub($pub);
        $success_message = "reqeust added successfully!";
        header('Location: ads.php?success_global=' . urlencode($success_message) . '&titre=' . urlencode($dmd->get_titre()));
        exit(); // Make sure to stop further execution after redirection
    } else {
        // returning an error
        $error_message = "Failed to add the reqeust. Please try again later.";
        header('Location: ads.php?error_global=' . urlencode($error_message));
        exit(); // Make sure to stop further execution after redirection
    }
} else {
    // returning an error if the file upload failed
    $error_message = "Failed to upload the file. Please try again.";
    header('Location: ads.php?error_global=' . urlencode($error_message));
    exit(); // Make sure to stop further execution after redirection
}

?>
