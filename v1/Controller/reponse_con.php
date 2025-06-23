<?php

require_once __DIR__ . '/../config.php';




class repCon{

    private $tab_name;

    public function __construct($tab_name){
        $this->tab_name = $tab_name;
    }

    public function generateId($id_length){
        //generatre a random number
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    public function repExists($id, $db) {
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

    public function generateRepId($id_length) {
        $db = config::getConnexion();
    
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->repExists($current_id, $db));
    
        return $current_id;
    }

    public function getRep($id)
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

    public function listRept()
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

    public function addRep($rep) 
    {
        $sql = "INSERT INTO $this->tab_name(id, contenu, date_reponse, id_user, id_reclamation) VALUES (:id, :contenu, :date_reponse, :id_user, :id_reclamation)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'id' => $rep->get_id(), 
                'contenu' => $rep->get_contenu(), 
                'date_reponse' => $rep->get_date_reponse(), 
                'id_user' => $rep->get_id_user(), 
                'id_reclamation' => $rep->get_id_reclamation(),
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateRep($rep, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET contenu = :contenu, date_reponse = :date_reponse, id_user = :id_user, id_reclamation = :id_reclamation WHERE id = :id");
            $query->execute(['id' => $id, 'contenu' => $rep->get_contenu(), 'date_reponse' => $rep->get_date_reponse(), 'id_user' => $rep->get_id_user(), 'id_reclamation' => $rep->get_id_reclamation()]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    public function deleteRep($id)
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
    public function searchRep($by, $keyword){

        if ($by == "everything"){
            $sql = "SELECT * FROM $this->tab_name WHERE (contenu LIKE '%$keyword%'  OR id_user LIKE '%$keyword%' OR id_reclamation LIKE '%$keyword%' OR  id LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM $this->tab_name WHERE $by LIKE '%$keyword%'";
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

    public function sortRep($by){

        
        $sql = "SELECT * FROM $this->tab_name";
        
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

   

    public function searchRepSorted($by, $keyword){

        if ($by == "everything"){
            $sql = "SELECT * FROM $this->tab_name WHERE contenu LIKE '%$keyword%' OR date_reponse LIKE '%$keyword%' OR id_user LIKE '%$keyword%' OR id_reclamation LIKE '%$keyword%' OR id LIKE '%$keyword%'";
        }
        else{
            $sql = "SELECT * FROM $this->tab_name WHERE $by LIKE '%$keyword%'";
        }

        // add order by//recherche avec tri
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


    public function generateRecOptions()
{
    // Fetching the blog IDs from the database
    $sql = "SELECT id, sujet FROM reclamations";

    $db = config::getConnexion();

    try {
        $stmt = $db->query($sql);

        // Generating the <option> tags
        $options = '';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= '<option value="' . $row['id'] . '">' . $row['sujet'] . '</option>';
        }

        return $options;
    } catch (PDOException $e) {
        die('Error:' . $e->getMessage());
    }
}

public function generateRecOptionsSelected($id)
{
    // Fetching the blog IDs from the database
    $sql = "SELECT id, sujet FROM reclamations";

    $db = config::getConnexion();

    try {
        $stmt = $db->query($sql);

        // Generating the <option> tags
        $options = '';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($id == $row['id']){
                $options .= '<option selected value="' . $row['id'] . '">' . $row['sujet'] . '</option>';
            }
            else{
                $options .= '<option value="' . $row['id'] . '">' . $row['sujet'] . '</option>';
            }
        }

        return $options;
    } catch (PDOException $e) {
        die('Error:' . $e->getMessage());
    }
}

    
    public function generatePDF($id) {

        $data = $this->getRep($id);

        $mpdf = new \Mpdf\Mpdf();
        /**
         * Password protect document
         */
        $mpdf->SetProtection([], 'UserPassword', 'Password');

        /**
         * Set file properties
         */
        $mpdf->SetTitle('HireUp Reclamation');
        $mpdf->SetAuthor('HireUp');

        /**
         * Setup header and footer content / properties, split content left, right, and center with a pipe |
         */
        $mpdf->defaultheaderline = 0;
        $mpdf->setHeader('|HireUp Reclamation|');
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('|HireUp Reclamation|{DATE F j, Y}|{PAGENO}');

        /**
         * Add external stylesheet
         */
        $stylesheet = file_get_contents('style.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        /**
         * Add a watermark
         */
        $mpdf->SetWatermarkText('HireUp');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = .1;

        /**
         * Add content using direct or string method
         */

        // Load HTML content
        $html = '<html><body>';
        $html .= '<h1>Answer</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Content</th><th>Date</th><th>ID User</th><th>ID Reclamation</th></tr>';
        
            $html .= '<tr>';
            $html .= '<td>' . $data['id'] . '</td>';
            $html .= '<td>' . $data['contenu'] . '</td>';
            $html .= '<td>' . $data['date_reponse'] . '</td>';
            $html .= '<td>' . $data['id_user'] . '</td>';
            $html .= '<td>' . $data['id_reclamation'] . '</td>';
            $html .= '</tr>';
        
        $html .= '</table>';
        $html .= '</body></html>';


        $mpdf->WriteHTML($html);



        $mpdf->AddPage();

        /**
         * Add content from a class
         */
        $mpdf->WriteHTML((new Foo())->bar());

        /**
         * Output the file (blank for screen, D for download, F for file save)
         */
        $mpdf->Output();
    }






    
public function updateStatus($id)
{
    try {
        $db = config::getConnexion();
        $query = $db->prepare("UPDATE reclamations SET statut = :s WHERE id = :id");
        $query->execute(['id' => $id,'s' => "solved"]);
    } catch (PDOException $e) {
        $e->getMessage();
        echo($e);
    }
}

public function listRepByIdec($id) {
    $sql = "SELECT * FROM reponses where id_reclamation = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([               
            'id' => $id
        ]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}


  
}









?>