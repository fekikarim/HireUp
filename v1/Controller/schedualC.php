<?php

require_once __DIR__ . '/../config.php';

class ScheduleController
{
    private $conn;

    public function __construct()
    {
        $this->conn = config::getConnexion(); // Get PDO connection
    }

    // Create a new schedule

    public function generateId($id_length){
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    public function ScheduleExists($id, $db) {
        $sql = "SELECT COUNT(*) as count FROM schedule_list WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateScheduleId($id_length) {
        $db = config::getConnexion();
    
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->ScheduleExists($current_id, $db));
    
        return $current_id;
    }

    public function createSchedule($id, $title, $description, $start_datetime, $end_datetime, $profile_id, $meeting_id)
    {
        try {
            // Insert the new schedule
            $stmt = $this->conn->prepare("INSERT INTO schedule_list (id, title, description, start_datetime, end_datetime, profile_id, meeting_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $title, $description, $start_datetime, $end_datetime, $profile_id, $meeting_id]);
            return "New schedule created successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Read all schedules
    public function getAllSchedules()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM schedule_list ORDER BY start_datetime");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllSchedulesWhereProfileId($profile_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM schedule_list WHERE profile_id = :profile_id ORDER BY start_datetime");
            $stmt->execute(['profile_id' => $profile_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    // Read a schedule by ID
    public function getScheduleById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM schedule_list WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Update a schedule
    public function updateSchedule($id, $title, $description, $start_datetime, $end_datetime)
    {
        try {
            // Update the schedule
            $stmt = $this->conn->prepare("UPDATE schedule_list SET title = ?, description = ?, start_datetime = ?, end_datetime = ? WHERE id = ?");
            $stmt->execute([$title, $description, $start_datetime, $end_datetime, $id]);
            return "Schedule updated successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Delete a schedule
    public function deleteSchedule($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM schedule_list WHERE id = ?");
            $stmt->execute([$id]);
            return "Schedule deleted successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function deleteScheduleWhereMeetingId($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM schedule_list WHERE meeting_id = ?");
            $stmt->execute([$id]);
            return "Schedule deleted successfully";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

// Example usage
//$controller = new ScheduleController();

// Create a new schedule
//$response = $controller->createSchedule('Meeting', 'Discuss project updates', '2024-05-25 10:00:00', '2024-05-25 11:00:00');
//echo $response . "\n";

// Get all schedules
//$schedules = $controller->getAllSchedules();
//print_r($schedules);

// Get a schedule by ID
//$schedule = $controller->getScheduleById(1);
//print_r($schedule);

// Update a schedule
//$response = $controller->updateSchedule(1, 'Updated Meeting', 'Discuss updated project updates', '2024-05-25 10:30:00', '2024-05-25 11:30:00');
//echo $response . "\n";

// Delete a schedule
//$response = $controller->deleteSchedule(1);
//echo $response . "\n";

?>