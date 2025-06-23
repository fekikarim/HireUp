<?php

require_once __DIR__ . '/../config.php';

class NotificationCon
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

    public function notificationExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE id = :id";
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

    public function generateNotificationId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->notificationExists($current_id, $db));

        return $current_id;
    }

    public function getNotification($id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE id = $id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            $notification = $query->fetch();
            return $notification;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listNotifications()
    {
        $sql = "SELECT * FROM $this->tab_name";

        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addNotification($notification)
    {
        $sql = "INSERT INTO $this->tab_name(id, sender_id, receiver_id, content, link, from_hire_up, date_time, seen) VALUES (:id, :sender_id, :receiver_id, :content, :link, :from_hire_up, :date_time, :seen)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $notification->get_id(),
                'sender_id' => $notification->get_sender_id(),
                'receiver_id' => $notification->get_receiver_id(),
                'content' => $notification->get_content(),
                'link' => $notification->get_link(),
                'from_hire_up' => $notification->get_from_hire_up(),
                'date_time' => $notification->get_date_time(),
                'seen' => $notification->get_seen()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateNotification($notification, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET sender_id = :sender_id, receiver_id = :receiver_id, content = :content, link = :link, from_hire_up = :from_hire_up, date_time = :date_time, seen = :seen WHERE id = :id");
            $query->execute([
                'id' => $id,
                'sender_id' => $notification->get_sender_id(),
                'receiver_id' => $notification->get_receiver_id(),
                'content' => $notification->get_content(),
                'link' => $notification->get_link(),
                'from_hire_up' => $notification->get_from_hire_up(),
                'date_time' => $notification->get_date_time(),
                'seen' => $notification->get_seen()
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteNotification($id)
    {
        $sql = "DELETE FROM $this->tab_name WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }

    public function listNotificationsByReceiverId($receiver_id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE receiver_id = :receiver_id";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $notifications;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function listNotificationsByReceiverIdOrderedByDateTime($receiver_id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE receiver_id = :receiver_id ORDER BY date_time DESC";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $notifications;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function chooseRandomString(array $options) {
        if (empty($options)) {
            return null;
        }
        $randomIndex = array_rand($options);
        return $options[$randomIndex];
    }

    function countUnseenNotifications($notifications_list) {
        $count = 0;
        foreach ($notifications_list as $notification) {
            if ($notification['seen'] == 'not seen') {
                $count++;
            }
        }
        return $count;
    }

    public function updateNotificationsByReceiverId($receiver_id, $val)
    {
        $sql = "UPDATE $this->tab_name SET seen = :val WHERE receiver_id = :receiver_id AND seen = 'not seen'";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':val', $val);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->execute();
            $count = $stmt->rowCount(); // Optional: return the number of rows affected
            return $count; // Optional: return the count of rows affected
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function listNotificationsByReceiverIdOrderedByDateTimeAndNotSeen($receiver_id)
    {
        $seen_val = 'not seen';
        $sql = "SELECT * FROM $this->tab_name WHERE receiver_id = :receiver_id AND seen = :seen ORDER BY date_time DESC";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':seen', $seen_val);
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $notifications;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }



}


?>