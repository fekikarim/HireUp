<?php

require_once __DIR__ . '/../config.php';

class CategoryInterestController
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

    public function interestExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM category_interests WHERE id = :id";
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

    public function generateInterestId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->interestExists($current_id, $db));

        return $current_id;
    }

    public function listInterests()
    {
        $sql = "SELECT * FROM category_interests";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addInterest($interest)
    {
        $sql = "INSERT INTO category_interests(id, category_id, profile_id, state) 
                VALUES (:id, :category_id, :profile_id, :state)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $interest->getId(),
                'category_id' => $interest->getCategoryId(),
                'profile_id' => $interest->getProfileId(),
                'state' => $interest->getState(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateInterest($interest, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE category_interests 
                                   SET category_id = :category_id, profile_id = :profile_id, state = :state
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'category_id' => $interest->getCategoryId(),
                'profile_id' => $interest->getProfileId(),
                'state' => $interest->getState(),
            ]);
            //echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteInterest($id)
    {
        $sql = "DELETE FROM category_interests WHERE id = :id";
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

    public function getInterest($id)
    {
        $sql = "SELECT * FROM category_interests WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $interest = $query->fetch();
            return $interest;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getInterestByCategoryAndProfile($category_id, $profile_id)
    {
        $sql = "SELECT * FROM category_interests WHERE category_id = :category_id AND profile_id = :profile_id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':category_id', $category_id);
            $query->bindParam(':profile_id', $profile_id);
            $query->execute();
            $category = $query->fetch();
            return $category;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getInterestByProfileId($profile_id)
    {
        $sql = "SELECT * FROM category_interests WHERE profile_id = :profile_id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':profile_id', $profile_id);
            $query->execute();
            $category = $query->fetchAll();
            return $category;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function interestExistsByCategoryAndProfile($category_id, $profile_id)
{
    $sql = "SELECT * FROM category_interests WHERE category_id = :category_id AND profile_id = :profile_id";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->bindParam(':category_id', $category_id);
        $query->bindParam(':profile_id', $profile_id);
        $query->execute();
        $category = $query->fetchAll();
        return !empty($category); // If category array is not empty, return true, otherwise false
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}


}

?>
