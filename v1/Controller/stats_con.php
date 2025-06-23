<?php 

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/stats.php';

class StatsCon {

    private $tab_name;

    public function __construct($tab_name){
        $this->tab_name = $tab_name;
    }

    public function statExists($date) {
        $db = config::getConnexion();

        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE date = :date";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function getStat($date)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE date = :date";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':date', $date);
            $query->execute();
            $event = $query->fetch();
            return $event;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    public function listStats()
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

    public function addStat($stats)
    {
        $sql = "INSERT INTO $this->tab_name(date, accounts_created) VALUES (:date, :accounts_created)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'date' => $stats->get_date(), 
                'accounts_created' => $stats->get_accounts_created()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateUserAccountCreatedInStat($date, $accounts_nb) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET accounts_created = :accounts_created WHERE date = :date");
            $query->execute(['date' => $date, 'accounts_created' => $accounts_nb]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    public function addUserAccountCreatedInStat($date) {
        if ($this->statExists($date)) {
            $this->updateUserAccountCreatedInStat($date, $this->getStat($date)['accounts_created'] + 1);
        } else {
            $this->addStat(new Stats($date, 1));
        }
    }

    public function deleteUserAccountCreatedInStat($date) {
        if ($this->statExists($date)) {
            $this->updateUserAccountCreatedInStat($date, $this->getStat($date)['accounts_created'] - 1);
        } else {
            $this->addStat(new Stats($date, -1));
        }
    }
}


?>