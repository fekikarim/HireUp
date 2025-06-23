<?php

require_once(__DIR__ . '/../config.php');

class CommentaireC {
    public function listCommentaires() {
        $sql = "SELECT * FROM commentaires";
        $db = Config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function deleteCommentaire($id) {
        $sql = "DELETE FROM commentaires WHERE id_commentaire = :id";
        $db = Config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    public function incrementLikes($commentId) {
        $db = Config::getConnexion();
        $stmt = $db->prepare("UPDATE commentaires SET likes = likes + 1 WHERE id_commentaire = :commentId");
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function incrementDislikes($commentId) {
        $db = Config::getConnexion();
        $stmt = $db->prepare("UPDATE commentaires SET dislikes = dislikes + 1 WHERE id_commentaire = :commentId");
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateComment($commentId, $newContenu)
    {
        $db = Config::getConnexion();
        $currentDate = date("Y-m-d"); // Get current date in YYYY-MM-DD format

        try {
            $stmt = $db->prepare("UPDATE commentaires SET contenu = :contenu, date_commentaire = :date_commentaire WHERE id_commentaire = :commentId");
            $stmt->bindParam(':contenu', $newContenu);
            $stmt->bindParam(':date_commentaire', $currentDate);
            $stmt->bindParam(':commentId', $commentId);
            $stmt->execute();
            return true; // Return true if update is successful
        } catch (PDOException $e) {
            echo "Error updating comment: " . $e->getMessage();
            return false; // Return false if there's an error
        }
    }
    
    public function countCommentsByPostId($postId)
    {
        $sql = "SELECT COUNT(*) AS comment_count FROM commentaires WHERE id_article = :postId";
        $db = Config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['comment_count'];
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function getCommentLikesDislikes($commentId) {
        $db = Config::getConnexion();
        $stmt = $db->prepare("SELECT likes, dislikes FROM commentaires WHERE id_commentaire = :commentId");
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCommentaire(Commentaire $commentaire)
    {
        $db = Config::getConnexion();

        // First, let's fetch the profile_id (auteur_id) of the owner of the post
        $postId = $commentaire->getIdArticle();
        $sql = "SELECT auteur_id FROM articles WHERE id = :postId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $auteurId = $row['auteur_id'];

        // Now, let's add the comment with both the auteur_id of the post owner and the sender_id of the commenter
        $sql =  "INSERT INTO `commentaires` (`id_commentaire`, `id_article`, `auteur_id`, `contenu`, `date_commentaire`, `likes`, `dislikes`, `sender_id`) 
             VALUES (NULL, :id_article, :auteur_id, :contenu, :date_commentaire, '0', '0', :sender_id)";
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_article' => $commentaire->getIdArticle(),
                'auteur_id' => $auteurId, // Using the profile_id (auteur_id) of the post owner
                'contenu' => $commentaire->getContenu(),
                'date_commentaire' => $commentaire->getDateCommentaire(),
                'sender_id' => $commentaire->getSenderId() // Using the sender_id of the commenter
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function showCommentaire($id) {
        $sql = "SELECT * FROM commentaires WHERE id_commentaire = $id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $commentaire = $query->fetch();
            return $commentaire;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getCommentsByPostIdNotOrderedByDateTime($postId)
    {
        $sql = "SELECT c.*, p.profile_photo 
                FROM commentaires c
                INNER JOIN profile p ON c.auteur_id = p.profile_id
                WHERE c.id_article = :postId";
        $db = Config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function getCommentsByPostId($postId)
    {
        $sql = "SELECT c.*, p.profile_photo 
                FROM commentaires c
                INNER JOIN profile p ON c.auteur_id = p.profile_id
                WHERE c.id_article = :postId ORDER BY date_commentaire DESC";
        $db = Config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function updateCommentaire(Commentaire $commentaire, $id) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare(
                'UPDATE commentaires SET 
                    id_article = :id_article, 
                    auteur = :auteur, 
                    contenu = :contenu, 
                    date_commentaire = :date_commentaire 
                WHERE id_commentaire = :id'
            );

            $query->execute([
                'id' => $id,
                'id_article' => $commentaire->getIdArticle(),
                'auteur' => $commentaire->getAuteur(),
                'contenu' => $commentaire->getContenu(),
                'date_commentaire' => $commentaire->getDateCommentaire()
            ]);

            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function generateArticleOptions()
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT id, titre FROM articles";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['id'] . '">' . $row['titre'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateArticleOptionsSelectedId($id)
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT id, titre FROM articles";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['id'] == $id){
                    $options .= '<option selected value="' . $row['id'] . '">' . $row['titre'] . '</option>';
                }
                else{
                    $options .= '<option value="' . $row['id'] . '">' . $row['titre'] . '</option>';
                }
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function filterBadWords($inputString) {
        // Load bad words from file
        $badWordsFile = __dir__ . "/badwords.txt";
        $badWords = file($badWordsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        // Escape special characters in bad words
        $escapedBadWords = array_map('preg_quote', $badWords);
    
        // Replace bad words with asterisks
        foreach ($escapedBadWords as $word) {
            $inputString = preg_replace("/\b$word\b/i", str_repeat("*", strlen($word)), $inputString);
        }
    
        return $inputString;
    }


}

?>