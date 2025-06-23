<?php

require_once __DIR__ . '/../config.php';

class PostOpinionCon
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

    public function postOpinionExists($id, $db)
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

    public function generatePostOpinionId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->postOpinionExists($current_id, $db));

        return $current_id;
    }

    public function getPostOpinion($id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE id = $id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            $event = $query->fetch();
            return $event;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listPostOpinions()
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

    public function addPostOpinion($postOpinion)
    {
        $sql = "INSERT INTO $this->tab_name(id, post_openion, id_post, id_profile) VALUES (:id, :post_openion, :id_post, :id_profile)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
                [
                    'id' => $postOpinion->get_id(),
                    'post_openion' => $postOpinion->get_post_openion(),
                    'id_post' => $postOpinion->get_id_post(),
                    'id_profile' => $postOpinion->get_id_profile()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updatePostOpinion($postOpinion, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET post_openion = :post_openion, id_post = :id_post, id_profile = :id_profile WHERE id = :id");
            $query->execute(['id' => $id, 'post_openion' => $postOpinion->get_post_openion(), 'id_post' => $postOpinion->get_id_post(), 'id_profile' => $postOpinion->get_id_profile()]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }

    public function deletePostOpinion($id)
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

    public function countLikesDislikes($id_post)
    {
        $likes = 0;
        $dislikes = 0;

        $sql = "SELECT post_openion FROM $this->tab_name WHERE id_post = :id_post";
        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_post', $id_post);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['post_openion'] == 'liked') {
                    $likes++;
                } elseif ($row['post_openion'] == 'disliked') {
                    $dislikes++;
                }
            }

            return array('nblikes' => $likes, 'nbdislikes' => $dislikes);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function checkReaction($profile_id, $post_id)
    {
        $sql = "SELECT post_openion FROM $this->tab_name WHERE id_profile = :profile_id AND id_post = :post_id";
        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':profile_id', $profile_id);
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['post_openion'];
            } else {
                return 'non'; // Profile hasn't reacted to the post
            }
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function getPostOpinionId($post_id, $profile_id)
    {
        $sql = "SELECT id FROM $this->tab_name WHERE id_post = :post_id AND id_profile = :profile_id";
        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':post_id', $post_id);
            $stmt->bindParam(':profile_id', $profile_id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['id'];
            } else {
                return null; // No opinion found for the specified post_id and profile_id
            }
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function changePostOpenion($post_openion_id, $new_state)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET post_openion = :post_openion WHERE id = :id");
            $query->execute(['id' => $post_openion_id, 'post_openion' => $new_state]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }


}


?>