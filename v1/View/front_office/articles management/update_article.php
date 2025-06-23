<?php
// Include the article controller and model
include_once "../../../Controller/articleC.php";
include_once "../../../Model/article.php";

// Initialize the article controller
$articleController = new ArticleC();

// Check if the article ID is passed in the URL
if (isset($_POST['id'])) {
    $articleId = $_POST['id'];

    // Fetch the article data by its ID
    $article = $articleController->showArticle($articleId);

    // Check if the article exists
    if ($article) {
        // Check if form fields are set
        if (isset($_POST['contenu'], $_POST['auteur_id'])) {
            // Sanitize and validate form inputs
            $contenu = htmlspecialchars($_POST['contenu']);
            $auteur_id = intval($_POST['auteur_id']); // Assuming auteur_id is an integer
            $date_art = date("Y-m-d"); // Get current date in the format YYYY-MM-DD

            if (!empty($_FILES['newimageArticle']['name']) && $_FILES['newimageArticle']['error'] === 0) {
                // New profile photo is uploaded, process the update with the new photo
                $newImage_tmp_name = $_FILES['newimageArticle']['tmp_name'];
                $newImage_data = file_get_contents($newImage_tmp_name);

                // Call the method to update only the profile picture
                $articleController->updatenewImage($articleId, $newImage_data); // corrected $id to $articleId
            }

            // Create a new Article object with the updated data
            $articleToUpdate = new Article();
            $articleToUpdate->setContenu($contenu);
            $articleToUpdate->setAuteur($auteur_id);
            $articleToUpdate->setDateArticle($date_art);

            // Update the article in the database
            $articleController->updateArticleFront($articleToUpdate, $articleId);

            // Redirect to a specific page after the update
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            // Redirect back to the previous page if form fields are not set
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    } else {
        // Redirect back to the previous page if the article does not exist
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
} else {
    // Redirect back to the previous page if the article ID is not passed in the URL
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
