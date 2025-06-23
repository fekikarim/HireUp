<?php

require_once __DIR__ . '/../config.php';

class TodoTaskCon
{
    public function generateId($id_length)
    {
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    public function taskExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM todo_tasks WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function generateTaskId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->taskExists($current_id, $db));

        return $current_id;
    }

    public function listTasks()
    {
        $sql = "SELECT * FROM todo_tasks";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addTask($task)
    {
        $sql = "INSERT INTO todo_tasks(id, profile_id, task, status, added_date) 
                VALUES (:id, :profile_id, :task, :status, :added_date)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $task->getId(),
                'profile_id' => $task->getProfileId(),
                'task' => $task->getTask(),
                'status' => $task->getStatus(),
                'added_date' => $task->getAddedDate(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateTask($task, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE todo_tasks 
                                   SET profile_id = :profile_id, task = :task, status = :status, added_date = :added_date
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'profile_id' => $task->getProfileId(),
                'task' => $task->getTask(),
                'status' => $task->getStatus(),
                'added_date' => $task->getAddedDate(),
            ]);
            //echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteTask($id)
    {
        $sql = "DELETE FROM todo_tasks WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getTask($id)
    {
        $sql = "SELECT * FROM todo_tasks WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $task = $query->fetch();
            return $task;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateTaskStatus($id, $new_status, $new_date)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE todo_tasks 
                                   SET status = :status, added_date = :added_date
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'status' => $new_status,
                'added_date' => $new_date,
            ]);
            //echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function listTasksByProfileId($profileId)
    {
        $sql = "SELECT * FROM todo_tasks WHERE profile_id = :profile_id";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':profile_id', $profileId);
            $stmt->execute();
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}


?>
