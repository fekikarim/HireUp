<?php

require_once __DIR__ . '/../config.php';

class WantedSkillController
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

    public function skillExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM wanted_skills_for_category WHERE id = :id";
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

    public function generateSkillId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->skillExists($current_id, $db));

        return $current_id;
    }

    public function listSkills()
    {
        $sql = "SELECT * FROM wanted_skills_for_category";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addSkill($skill)
    {
        $sql = "INSERT INTO wanted_skills_for_category(id, category_id, skill) 
                VALUES (:id, :category_id, :skill)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $skill->getId(),
                'category_id' => $skill->getCategoryId(),
                'skill' => $skill->getSkill(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateSkill($skill, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE wanted_skills_for_category 
                                   SET category_id = :category_id, skill = :skill
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'category_id' => $skill->getCategoryId(),
                'skill' => $skill->getSkill(),
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteSkill($id)
    {
        $sql = "DELETE FROM wanted_skills_for_category WHERE id = :id";
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

    public function getSkill($id)
    {
        $sql = "SELECT * FROM wanted_skills_for_category WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $skill = $query->fetch();
            return $skill;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getCetagorySkills($id)
    {
        $sql = "SELECT * FROM wanted_skills_for_category WHERE category_id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $skills = $query->fetchAll();
            return $skills;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function generateCategoryOptions()
    {
        // Fetching the category IDs from the database
        $sql = "SELECT id_category, name_category FROM category";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['id_category'] . '">' . $row['name_category'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateCategoryOptionsUpdate()
    {
        // Fetching the category IDs and names from the database
        $sql = "SELECT id_category, name_category FROM category";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['id_category'] . '"' . '? "selected"' . '>' . $row['name_category'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    public function getCategoryIdByName($name_category)
    {
        // Fetching the category ID based on the category name from the database
        $sql = "SELECT id_category FROM category WHERE name_category = :name_category LIMIT 1";

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name_category', $name_category, PDO::PARAM_STR);
            $stmt->execute();

            // Fetching the category ID
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a result is found
            if ($row) {
                return $row['id_category'];
            } else {
                return null; // Or handle the case where the category name does not exist
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getCategoryNameById($id_category)
    {
        // Fetching the category ID based on the category name from the database
        $sql = "SELECT name_category FROM category WHERE id_category = :id_category";

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_category', $id_category, PDO::PARAM_STR);
            $stmt->execute();

            // Fetching the category ID
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a result is found
            if ($row) {
                return $row['name_category'];
            } else {
                return null; // Or handle the case where the category name does not exist
            }
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

}




?>