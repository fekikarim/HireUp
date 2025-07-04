<?php

require_once __DIR__ . '/../../../Controller/profileController.php';


if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$profileController = new ProfileC();

$user_id = '';
$current_profile_id = '';
$user_profile_id = '';

//get user_profile id
if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
    $profile = $profileController->getProfileById($user_profile_id);
}

//fetch subscription
$subs_type = array(
    "1-ADVANCED-SUBS" => "advanced",
    "1-BASIC-SUBS" => "basic",
    "1-PREMIUM-SUBS" => "premium",
    "else" => "limited"
);

$current_profile_sub = "";
if (array_key_exists($profile['profile_subscription'], $subs_type)) {
    // If it exists, return the corresponding value
    $current_profile_sub = $subs_type[$profile['profile_subscription']];
} else {
    // If not, return 'bb'
    $current_profile_sub = $subs_type['else'];
}

if ($current_profile_sub == "limited") {

    header("Location: ./../../../index.php");
    exit;

}



$block_call_back = 'false';
$access_level = "else";
include ('./../../../View/callback.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#062e3f">
    <meta name="Description" content="The HireUp dynamic and aesthetic To-Do List.">
    <link rel="icon" type="image/png" href="./../../../front office assets\images\HireUp_icon.ico" />

    <!-- Google Font: Quick Sand -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">

    <!-- font awesome (https://fontawesome.com) for basic icons; source: https://cdnjs.com/libraries/font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css"
        integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

    <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/corner.css">
    <!-- <title>HireUp To Do List</title> -->
    <title>To DoUp</title>

</head>

<body>
    <div id="header">
        <div class="flexrow-container">
            <div class="standard-theme theme-selector"></div>
            <div class="light-theme theme-selector"></div>
            <div class="darker-theme theme-selector"></div>
        </div>
        <!-- <h1 id="title">Just do it.<div id="border"></div></h1> -->
        <h1 id="title">To DoUp.<div id="border"></div>
        </h1>
    </div>

    <div id="form">
        <form id="taskForm">
            <input class="todo-input" type="text" placeholder="Add a task." id="taskInput">
            <button type="button" class="todo-btn" type="submit">I Got This!</button>
        </form>
    </div>

    <!-- div for top left corner -->
    <div class="version">

        <div class="demo version-section"><a href="https://github.com/lordwill1/todo-list" class="github-corner">
                <svg width="80" height="80" viewBox="0 0 250 250"
                    style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; left: 0; transform: scale(-1, 1);">
                    <path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
                    <path
                        d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2"
                        fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path>
                    <path
                        d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z"
                        fill="currentColor" class="octo-body"></path>
                </svg></a>
        </div>

        <div>
            <p><span id="datetime"></span></p>
            <script src="JS/time.js"></script>
        </div>

        <div id="myUnOrdList">
            <ul class="todo-list">
                <!-- (Basic Format)
            <div class="todo">
                items added to this list:
                <li></li>
                <button>delete</button>
                <button>check</button>
            </div> -->
            </ul>
        </div>
        <script src="JS/main.js" type="text/javascript"> </script>
</body>

</html>