<?php

require_once(__DIR__ . '/../config.php');

class ArticleC {

    public function getArtical($id)
    {
        $sql = "SELECT * FROM articles WHERE id = $id";
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

    public function listArticles() {
        $sql = "SELECT * FROM articles"; // Modify this query as needed
        $db = Config::getConnexion();
        try {
            $stmt = $db->query($sql);
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $articles;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function listArticlesByProfileIdNotOrderedByDateTime($auteur_id)
    {
        $sql = "SELECT * FROM articles WHERE auteur_id = :auteur_id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':auteur_id' => $auteur_id]);
            $liste = $query->fetchAll();
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    public function listArticlesByProfileId($auteur_id)
    {
        $sql = "SELECT * FROM articles WHERE auteur_id = :auteur_id ORDER BY date_art DESC";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':auteur_id' => $auteur_id]);
            $liste = $query->fetchAll();
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function updateArticleFront(Article $article, $id)
    {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare(
                'UPDATE articles SET 
                    contenu = :contenu, 
                    auteur_id = :auteur_id, 
                    date_art = :date_art
                WHERE id = :id'
            );

            $query->execute([
                'id' => $id,
                'contenu' => $article->getContenu(),
                'auteur_id' => $article->getAuteur(),
                'date_art' => $article->getDateArticle()
            ]);

            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updatenewImage($id, $imageData)
    {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare('UPDATE articles SET imageArticle = :image_data WHERE id = :id');
            $query->bindParam(':image_data', $imageData, PDO::PARAM_LOB);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            echo $query->rowCount() . " record updated successfully";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    
    public function articleExists($id)
    {
        $tableName = "articles";

        $sql = "SELECT COUNT(*) as count FROM $tableName WHERE id = :id";
        $db = Config::getConnexion();
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

    public function generateJobId($id_length)
    {
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->articleExists($current_id));

        return $current_id;
    }

    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE id = :id";
        $db = Config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
    
        try {
            $req->execute();
            // Check if any rows were affected by the deletion
            $deleted = $req->rowCount() > 0;
            return $deleted;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    


    public function addArticle($id, $title, $company, $location, $description,$category,$article_image="") {
    $sql = "INSERT INTO `articles` (`id`, `titre`, `contenu`, `auteur_id`, `date_art`, `categories`, `imageArticle`) VALUES (:id , :titre, :contenu, :auteur_id, :date_art, :categories, :imageArticle);";
        $db = new config();
        $conn = $db->getConnexion();
        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'id' => $id,
                'titre' => $title,
                'contenu' => $company,
                'auteur_id' => $location,
                'date_art' => $description,
                'categories' => $category,
                'imageArticle' => $article_image
            ]);
            return "New article created successfully";
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function addArticleFront(Article $article)
    {
        $sql = "INSERT INTO `articles` (`id`, `titre`, `contenu`, `auteur_id`, `date_art`, `categories`, `imageArticle`, `shared_from`) VALUES (NULL, :titre, :contenu, :auteur_id, :date_art, :categories, :imageArticle, :shared_from);";
        $db = new config();
        $conn = $db->getConnexion();
        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'titre' => $article->getTitre(),
                'contenu' => $article->getContenu(),
                'auteur_id' => $article->getAuteur(),
                'date_art' => $article->getDateArticle(),
                'categories' => $article->getCategorie(),
                'imageArticle' => $article->getImageArticle(),
                'shared_from' => $article->get_shared_from()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function showArticle($id) {
        $sql = "SELECT * FROM articles WHERE id = $id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $article = $query->fetch();
            return $article;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    public function updateArticle($id, $title, $content, $author, $date_art, $category) {
        try {
            $db = Config::getConnexion();
            $query = $db->prepare(
                'UPDATE articles SET 
                    titre = :title, 
                    contenu = :content, 
                    auteur_id = :auteur_id, 
                    date_art = :date_art, 
                    categories = :category
                WHERE id = :id'
            );
    
            $query->execute([
                'id' => $id,
                'title' => $title,
                'content' => $content,
                'auteur_id' => $author,
                'date_art' => $date_art,
                'category' => $category
            ]);
    
            return $query->rowCount(); // Return the number of affected rows
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false; // Return false if an error occurs
        }
    }

    public function searchart($by, $keyword)
    {
        $db = config::getConnexion();
        try {
            if ($by == "everything") {
                $sql = "SELECT * FROM articles WHERE (titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%' OR date_art LIKE '%$keyword%' OR categories LIKE '%$keyword%' or auteur_id LIKE '%$keyword%' OR id LIKE '%$keyword%')";
            } else {
                $sql = "SELECT * FROM articles WHERE $by LIKE '%$keyword%'";
            }

            $query = $db->prepare($sql);
            $query->execute();
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function searchartSorted($by, $keyword)
    {
        $db = config::getConnexion();
        try {
            $sql = "SELECT * FROM articles WHERE ";
            if ($by == "everything") {
                $sql .= "titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%' OR date_art LIKE '%$keyword%' OR categories LIKE '%$keyword%' or auteur_id LIKE '%$keyword%' OR id LIKE '%$keyword%'";
            } else {
                $sql .= "$by LIKE '%$keyword%'";
            }

            // Add ORDER BY clause
            if ($by == "everything") {
                $sql .= " ORDER BY iddemande"; // Replace 'id' with 'iddemande'
            } else {
                $sql .= " ORDER BY $by";
            }

            $query = $db->prepare($sql);
            $query->execute();
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateAutherOptions()
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT profile_id, profile_first_name, profile_family_name FROM articles";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['profile_id'] . '">' . $row['profile_first_name'] . ' ' . $row['profile_family_name'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateAutherOptionsSelectedId($id)
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT profile_id, profile_first_name, profile_family_name FROM articles";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['profile_id'] == $id){
                    $options .= '<option selected value="' . $row['profile_id'] . '">' . $row['profile_first_name'] . ' ' . $row['profile_family_name'] . '</option>';
                }
                else{
                    $options .= '<option value="' . $row['profile_id'] . '">' . $row['profile_first_name'] . ' ' . $row['profile_family_name'] . '</option>';
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