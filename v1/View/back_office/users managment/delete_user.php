<?php
include '../../../Controller/user_con.php';
include '../../../Model/user.php';

include '../../../Controller/stats_con.php';

$statsC = new StatsCon("stats");

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

if (isset($_GET['id'])){
    $current_id = $_GET['id'];

    $res = $userC->deleteUser($current_id);

    if ($res){

        // removing an account creation from stats
        $currentDate = date("Y-m-d");

        $statsC->deleteUserAccountCreatedInStat($currentDate);

        $success_message = "User deleted successfully!";
        header('Location: ../../../View/back_office/users managment/users_management.php?success_global=' . urlencode($success_message));
        exit();
    }
    else{
        $error_message = "Failed to delete the user. Please try again later.";
        header('Location: ../../../View/back_office/users managment/users_management.php?error_global=' . urlencode($error_message));
        exit();
    }
}
else{
    $error_message = "Failed to delete the user. Please try again later.";
    header('Location: ../../../View/back_office/users managment/users_management.php?error_global=' . urlencode($error_message));
    exit();
}


?>