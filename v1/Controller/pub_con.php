<?php

require_once __DIR__ . '/../config.php';




class pubCon{


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

    public function pubExists($idpub, $db) {
        $sql = "SELECT COUNT(*) as count FROM publicite WHERE idpub = :idpub";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':idpub', $idpub);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generatepubId($id_length) {
        $db = config::getConnexion();
    
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->pubExists($current_id, $db));
    
        return $current_id;
    }


    public function listpub()
    {
        $sql = "SELECT * FROM publicite";

        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addpub($pub)
    {
        $sql = "INSERT INTO publicite(idpub, titre, contenu, dat, id_demande, pub_link) VALUES (:idpub, :titre, :contenu, :dat, :id_dmd, :pub_link)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'idpub' => $pub->get_idpub(), 
                'titre' => $pub->get_titre(), 
                'contenu' => $pub->get_contenu(), 
                'dat' => $pub->get_dat(),
                'id_dmd' => $pub->get_id_dmd(),
                'pub_link' => $pub->get_pub_link()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updatepub($pub, $idpub)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE publicite SET titre = :titre, contenu = :contenu, dat = :dat, id_demande = :id_dmd WHERE idpub = :idpub");
            $query->execute([
                'idpub' => $idpub, 
                'titre' => $pub->get_titre(),
                'contenu' => $pub->get_contenu(),
                'dat' => $pub->get_dat(),
                'id_dmd' => $pub->get_id_dmd()
        
                
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }
    

    public function deletepub($idpub)
    {
        $sql = "DELETE FROM publicite WHERE idpub = :idpub";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':idpub', $idpub);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }


    public function sortpub($by){

        
        $sql = "SELECT * FROM publicite";
        
        if ($by == "everything"){
            $sql .= " ORDER BY id";
        }
        else{
            $sql .= " ORDER BY $by";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }

    public function searchpub($by, $keyword ){

        if ($by == "everything"){
            $sql = "SELECT * FROM publicite WHERE (titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%' OR dat LIKE '%$keyword%' OR id_demande LIKE '%$keyword%' OR idpub LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM publicite WHERE $by LIKE '%$keyword%'";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }

    public function titreExists($titre) {
        $db = config::getConnexion();

        $sql = "SELECT COUNT(*) as count FROM publicite WHERE titre = :titre";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':titre', $titre);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }


    public function getpub($idpub)
    {
        $sql = "SELECT * FROM publicite WHERE idpub = $idpub";
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

    public function searchpubSorted($by, $keyword){

        if ($by == "everything"){
            $sql = "SELECT * FROM publicite WHERE (titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%' OR dat LIKE '%$keyword%' OR id_demande LIKE '%$keyword%' OR idpub LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM publicite WHERE $by LIKE '%$keyword%'";
        }

       

        // add order by
        if ($by == "everything"){
            $sql .= " ORDER BY id";
        }
        else{
            $sql .= " ORDER BY $by";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }


    public function generateDmdOptions()
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT iddemande, titre FROM demande";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['iddemande'] . '">' . $row['titre'] . '</option>';
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateDmdOptionsSelected($id)
    {
        // Fetching the blog IDs from the database
        $sql = "SELECT iddemande, titre FROM demande";

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);

            // Generating the <option> tags
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['iddemande'] == $id){
                    $options .= '<option selected value="' . $row['iddemande'] . '">' . $row['titre'] . '</option>';
                }
                else{
                    $options .= '<option value="' . $row['iddemande'] . '">' . $row['titre'] . '</option>';
                }
            }

            return $options;
        } catch (PDOException $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function listAcceptedPubs()
    {
        $sql = "SELECT publicite.* 
                FROM publicite 
                INNER JOIN demande ON publicite.id_demande = demande.iddemande 
                WHERE demande.status = 'accepted' and demande.paid = 'payed' ";

        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function get_pup_img_by_dmd_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM demande WHERE iddemande = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['image'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function get_pup_id_by_dmd_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM publicite WHERE id_demande = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['idpub'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function generate_pub(){

        $array = $this->listAcceptedPubs();

        $pubs = $array->fetchAll(PDO::FETCH_ASSOC);

        if (count($pubs) > 0) {

            //var_dump($pubs);

            $length = count($pubs);

            $randomNumber = rand(0, $length - 1);

            $current_pub = $pubs[$randomNumber];

            $its_id_dmd = $current_pub['id_demande'];

            //echo ''. $its_id_dmd .'';

            $current_dmd_img = $this->get_pup_img_by_dmd_id($its_id_dmd);


            echo '<a target="_blank" href="'.$current_pub['pub_link'].'" onclick="return invokePhpFunction(\''.$current_pub['idpub'].'\');"><img src="data:image/jpeg;base64,' . base64_encode($current_dmd_img) . '" alt="" class="img-fluid"></a>';
        }
    }

    public function updatePubliciteClickedTimes($idpub) 
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE publicite SET clicked_times = clicked_times + 1 WHERE idpub = :idpub");
            $query->execute(['idpub' => $idpub]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


}




   


?>