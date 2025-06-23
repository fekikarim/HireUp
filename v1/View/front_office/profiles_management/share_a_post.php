<?php

include_once __dir__ .'/../../../Controller/articleC.php';
require_once __DIR__ . '/../../../Controller/notification_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Model/notification.php';
include_once __dir__ . "/../../../Model/article.php";
include_once __DIR__ . '/../../../Controller/user_con.php';

$articleC = new ArticleC();
$NotificationCon = new NotificationCon("notifications");
$profileController = new ProfileC();
$userC = new userCon("user");


if (isset($_GET['post_id']) && isset($_GET['profile_id']) && isset($_GET['current_profile'])){
    $current_id = $_GET['post_id'];
    $profile_id_to_share = $_GET['profile_id'];
    $profile_that_came_from = $_GET['current_profile'];

    $old_post = $articleC->getArtical($current_id);

    $article = new Article();
    $article->getIdArticle($articleC->generateJobId(5));
    $article->setContenu($old_post["contenu"]);
    $article->setAuteur($profile_id_to_share);
    $article->setDateArticle(date('Y-m-d'));
    $article->setImageArticle($old_post['imageArticle']);
    $article->set_shared_from($old_post['auteur_id']);

    $articleC->addArticleFront($article);  

    $old_post_auther_profile = $profileController->getProfileById($old_post['auteur_id']);

    if ($profile_id_to_share != $old_post['auteur_id']){
        $myStrings = [
            " shared your post! Keep the buzz going", 
            " shared your post! Keep the momentum going", 
            " shared your post! Keep up the great work", 
        ];
        $selectedString = $NotificationCon->chooseRandomString($myStrings);
        if ($selectedString !== null) {
            echo "Selected string: $selectedString";
        } 

        $notification = new Notification(
            $NotificationCon->generateNotificationId(5),
            $profile_id_to_share,
            $old_post['auteur_id'],
            $selectedString,
            '#',
            'false'
        );
    
        $NotificationCon->addNotification($notification);

        $email_to_send_to = "";
        $old_post_auther_user_id = $old_post_auther_profile['profile_userid'];
        $old_post_auther_user_data = $userC->getUser($old_post_auther_user_id);

        $profile_that_shared = $profileController->getProfileById($profile_id_to_share);

        $userC->sendSharingEmail($old_post_auther_user_data['email'], $profile_that_shared);

    
    }


}

header('Location: profile.php?profile_id=' . urlencode($profile_that_came_from));
exit();


?>