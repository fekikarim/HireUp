<?php
require_once 'config.php';

class Schedule
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    // Function to get all schedules
    public function getAllSchedules()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM schedule_list");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Function to get a schedule by ID
    public function getScheduleById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM schedule_list WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Function to add a new schedule
    public function addSchedule($title, $description, $start_datetime, $end_datetime)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO schedule_list (title, description, start_datetime, end_datetime) VALUES (:title, :description, :start_datetime, :end_datetime)");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':start_datetime', $start_datetime, PDO::PARAM_STR);
            $stmt->bindParam(':end_datetime', $end_datetime, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Function to update a schedule by ID
    public function updateSchedule($id, $title, $description, $start_datetime, $end_datetime)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE schedule_list SET title = :title, description = :description, start_datetime = :start_datetime, end_datetime = :end_datetime WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':start_datetime', $start_datetime, PDO::PARAM_STR);
            $stmt->bindParam(':end_datetime', $end_datetime, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Function to delete a schedule by ID
    public function deleteSchedule($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM schedule_list WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>