<?php

require_once __DIR__ . '/../config.php';

class MeetingParticipantsCon
{
    private $tab_name;

    public function __construct($tab_name)
    {
        $this->tab_name = $tab_name;
    }

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

    public function participantExists($profile_id, $meeting_id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE profile_id = :profile_id AND meeting_id = :meeting_id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':profile_id', $profile_id);
            $stmt->bindParam(':meeting_id', $meeting_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateParticipantId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->participantExists($current_id, $db));

        return $current_id;
    }

    public function getParticipant($profile_id, $meeting_id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE profile_id = :profile_id AND meeting_id = :meeting_id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':profile_id', $profile_id);
            $query->bindParam(':meeting_id', $meeting_id);
            $query->execute();
            $participant = $query->fetch(PDO::FETCH_ASSOC);
            return $participant;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listParticipants()
    {
        $sql = "SELECT * FROM $this->tab_name";
        $db = config::getConnexion();

        try {
            $list = $db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addParticipant($participant)
    {
        $sql = "INSERT INTO $this->tab_name(profile_id, meeting_id, id_schedule, profile_role, added_at) VALUES (:profile_id, :meeting_id, :id_schedule, :profile_role, :added_at)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'profile_id' => $participant->getProfileId(),
                'meeting_id' => $participant->getMeetingId(),
                'id_schedule' => $participant->getid_sch(),
                'profile_role' => $participant->getProfileRole(),
                'added_at' => $participant->getAddedAt()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateParticipant($participant, $profile_id, $meeting_id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET profile_role = :profile_role, added_at = :added_at WHERE profile_id = :profile_id AND meeting_id = :meeting_id");
            $query->execute([
                'profile_role' => $participant->getProfileRole(),
                'added_at' => $participant->getAddedAt(),
                'profile_id' => $profile_id,
                'meeting_id' => $meeting_id
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteParticipant($profile_id, $meeting_id)
    {
        $sql = "DELETE FROM $this->tab_name WHERE profile_id = :profile_id AND meeting_id = :meeting_id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindParam(':profile_id', $profile_id);
        $req->bindParam(':meeting_id', $meeting_id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }
}
?>
