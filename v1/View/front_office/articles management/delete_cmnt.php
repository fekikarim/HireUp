<?php

include_once "../../../Controller/commentaireC.php";
include_once "../../../Model/commentaire.php";

// Check if comment ID is provided
if (isset($_GET['id'])) {

    // Instantiate the CommentaireC class
    $commentaireC = new CommentaireC();

    // Get the comment ID from the query string
    $commentId = $_GET['id'];

    // Call the deleteCommentaire method to delete the comment
    $commentaireC->deleteCommentaire($commentId);

    // Redirect back to the page where the comment was deleted from
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
} else {
    // If comment ID is not provided, redirect to an error page or handle accordingly
    echo "Comment ID not provided.";
}
?>
