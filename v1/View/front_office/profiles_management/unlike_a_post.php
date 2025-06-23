<?php
include '../../../Controller/post_openion_con.php';

// Création d'une instance du contrôleur des événements
$post_openionC = new PostOpinionCon("post_openion");

if (isset($_GET['post_id']) && isset($_GET['profile_id']) && isset($_GET['current_profile'])){
    $current_id = $_GET['post_id'];
    $profile_id_to_unlike = $_GET['profile_id'];
    $profile_that_came_from = $_GET['current_profile'];

    $post_opnion_id = $post_openionC->getPostOpinionId($current_id, $profile_id_to_unlike);

    $post_openionC->deletePostOpinion($post_opnion_id);   
}

header('Location: profile.php?profile_id=' . urlencode($profile_that_came_from));
exit();


?>