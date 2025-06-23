<?php

require_once __DIR__ . '/../../../../Controller/todo_tasks_con.php';
require_once __DIR__ . '/../../../../Model/todo_tasks.php';

$todoController = new TodoTaskCon();

if (isset($_POST['task_id']) && !empty($_POST['task_id'])) {
    $newDate = date("Y-m-d H:i:s");
    $task_id = htmlspecialchars($_POST['task_id']);
    $task = $todoController->getTask($task_id);
    $old_state = $task['status'];
    $new_state = ($old_state == 'still') ? 'done' : 'still';
    $todoController->updateTaskStatus($task_id, $new_state, $newDate);

    echo "Task updated successfully";
}



?>