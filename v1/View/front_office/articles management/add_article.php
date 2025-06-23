<?php

include_once __dir__ . "/../../../Controller/articleC.php";
include_once __dir__ . "/../../../Model/article.php";

// Create an instance of the controller
$articleC = new ArticleC();
$erreur_msg = "";

$image_tmp_name = '';

if (
  isset($_POST["contenu"]) &&
  isset($_POST["auteur_id"])
) {
  // Continue with other form field validations
  if (
    !empty($_POST["contenu"]) &&
    !empty($_POST["auteur_id"])
  ) {
        
    // Check if an image is uploaded
    if (!empty($_FILES['imageArticle']['tmp_name'])) {
      // Get image data
      $image_tmp_name = $_FILES['imageArticle']['tmp_name'];
      $image_data = file_get_contents($image_tmp_name);
    } else {
      // Set image data to null if no image is uploaded
      $image_data = null;
    }

    $date_art = date("Y-m-d");

    // Create the Article object
    $article = new Article();
    $article->setContenu($_POST["contenu"]);
    $article->setAuteur($_POST["auteur_id"]);
    $article->setDateArticle($date_art);
    $article->setImageArticle($image_data);

    // Add the article to the database
    $articleC->addArticleFront($article);

    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
  } else {
    // Display an error message if required fields are not all filled
    $erreur_msg .= 'Erreur : Veuillez remplir tous les champs obligatoires.<br>';
  }
}
