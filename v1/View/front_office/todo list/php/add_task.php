<?php

require_once __DIR__ . '/../../../../Controller/todo_tasks_con.php';
require_once __DIR__ . '/../../../../Controller/profileController.php';
require_once __DIR__ . '/../../../../Model/todo_tasks.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$todoController = new TodoTaskCon();
$profileController = new ProfileC();

$user_id = '';
$user_profile_id = '';


if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);

    // Get profile ID from the URL
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

}

if (isset($_POST['task']) && !empty($_POST['task'])) {
    $task_content = htmlspecialchars($_POST['task']);
    $status = 'still';
    $addedDate = date("Y-m-d H:i:s");
    $profile_id = $user_profile_id;
    $task_id = $todoController->generateTaskId(6);

    $task = new TodoTask(
        $task_id,
        $profile_id,
        $task_content,
        $status,
        $addedDate
    );

    $todoController->addTask($task);

    echo "Task added successfully:" . $task_id;
}



?>