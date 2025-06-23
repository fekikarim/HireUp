<?php

require_once __DIR__ . '/../../../config.php';
include_once '../../../Controller/CommentaireC.php'; // Assuming CommentaireC.php contains the CommentaireC class

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the comment ID and new content are provided in the POST request
    if (isset($_POST['id_commentaire']) && isset($_POST['newContenu'])) {
        // Sanitize and store the comment ID and new content
        $commentId = $_POST['id_commentaire'];
        $newContenu = $_POST['newContenu'];

        // Create a new instance of CommentaireC class
        $commentaireC = new CommentaireC();

        // Call the updateComment method to update the comment content and date
        if ($commentaireC->updateComment($commentId, $newContenu)) {
            // Redirect to the previous page after successful update
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Handle errors if update fails
            echo "Failed to update comment.";
        }
    } else {
        // Handle if comment ID or new content is not provided
        echo "Comment ID and new content are required.";
    }
} else {
    // Handle if the request method is not POST
    echo "Invalid request method.";
}

?>
