<?php

require_once __DIR__ . '/../../../../Controller/todo_tasks_con.php';
require_once __DIR__ . '/../../../../Model/todo_tasks.php';

$todoController = new TodoTaskCon();

if (isset($_POST['task_id']) && !empty($_POST['task_id'])) {
    $task_id = htmlspecialchars($_POST['task_id']);

    $todoController->deleteTask($task_id);

    echo "Task deleted successfully";
}



?>