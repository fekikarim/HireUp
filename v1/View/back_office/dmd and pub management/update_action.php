<?php
include '../../../Controller/dmd_con.php';
include '../../../Model/dmd.php';

$dmdd = new dmdCon("dmds");
$dmd = null;

if (isset($_GET['id'])) {
    $current_id = $_GET['id'];
}

if (isset($_POST["titre"]) &&
    isset($_POST["contenu"]) &&
    isset($_POST["objectif"]) &&
    isset($_POST["dure"]) &&
    isset($_POST["budget"])
) {
    if (
        !empty($_POST['titre']) &&
        !empty($_POST["contenu"]) &&
        !empty($_POST["objectif"]) &&
        !empty($_POST["dure"]) &&
        !empty($_POST["budget"])
    ) {

        $image_publication_data = null;
        if (!empty($_FILES['image_publication']['name']) && $_FILES['image_publication']['error'] === 0){
            $image_publication_tmp_name = $_FILES['image_publication']['tmp_name'];
            $image_publication_data = file_get_contents($image_publication_tmp_name);

            $dmd = new Dmd(
                $current_id,
                $_POST['titre'],
                $_POST['contenu'],
                $_POST['objectif'],
                $_POST['dure'],
                $_POST['budget'],
                $image_publication_data
            );
    
            $dmdd->updatedmd($dmd, $current_id);
            header('Location:../../../View/back_office/ads managment/dmd_management.php');
        }
        else {
            $dmd = new Dmd(
                $current_id,
                $_POST['titre'],
                $_POST['contenu'],
                $_POST['objectif'],
                $_POST['dure'],
                $_POST['budget'],
                $image_publication_data
            );
    
            $dmdd->updatedmdWithoutImg($dmd, $current_id);
            header('Location:../../../View/back_office/ads managment/dmd_management.php');
        }

        
    } else {
        $error = "Missing information";
    }
} else {
    echo "error";
}
?>