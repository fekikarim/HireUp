<?php

require '../../../config.php';

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Foo.php';





class recCon{

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

    public function recExists($id, $db) {
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

    public function generateRecId($id_length) {
        $db = config::getConnexion();
    
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->recExists($current_id, $db));
    
        return $current_id;
    }

    public function getRec($id)
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

    public function listRect()
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

    public function addRec($rec) 
    {
        $sql = "INSERT INTO $this->tab_name(id, sujet, description, date_creation, statut, id_user) VALUES (:id, :sujet, :description, :date_creation, :statut, :id_user)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'id' => $rec->get_id(), 
                'sujet' => $rec->get_sujet(), 
                'description' => $rec->get_description(), 
                'date_creation' => $rec->get_date_creation(), 
                'statut' => $rec->get_statut(),
                'id_user' => $rec->get_id_user()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateRec($rec, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET sujet = :sujet, description = :description, date_creation = :date_creation, statut = :statut, id_user = :id_user WHERE id = :id");
            $query->execute(['id' => $id, 'sujet' => $rec->get_sujet(), 'description' => $rec->get_description(), 'date_creation' => $rec->get_date_creation(), 'statut' => $rec->get_statut(), 'id_user' => $rec->get_id_user()]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    public function deleteRec($id)
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

    public function searchRec($by, $keyword, $status){

        if ($by == "everything"){
            $sql = "SELECT * FROM $this->tab_name WHERE (sujet LIKE '%$keyword%' OR description LIKE '%$keyword%' OR statut LIKE '%$keyword%' OR id LIKE '%$keyword%' OR id_user LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM $this->tab_name WHERE $by LIKE '%$keyword%'";
        }

        if ($status != "none") {
            $sql .= " AND statut = '$status'";
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

    public function searchRecSorted($by, $keyword, $status)
    {
        $db = config::getConnexion();
        try {
            $sql = "SELECT * FROM $this->tab_name WHERE ";
            if ($by == "everything") {
                $sql .= "sujet LIKE '%$keyword%' OR description LIKE '%$keyword%' OR statut LIKE '%$keyword%' OR id LIKE '%$keyword%' OR id_user LIKE '%$keyword%'";
            } else {
                $sql .= "$by LIKE '%$keyword%'";
            }

            if ($status != "none") {
                $sql .= " AND status = '$status'";
            }

            // Add ORDER BY clause
            if ($by == "everything") {
                $sql .= " ORDER BY id"; // Replace 'id' with 'iddemande'
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

    public function generatePDF($id) {

        $data = $this->getRec($id);

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
        $html .= '<h1>Reclamation</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Sujet</th><th>Description</th><th>Date Creation</th><th>Statut</th><th>ID User</th></tr>';
        
            $html .= '<tr>';
            $html .= '<td>' . $data['id'] . '</td>';
            $html .= '<td>' . $data['sujet'] . '</td>';
            $html .= '<td>' . $data['description'] . '</td>';
            $html .= '<td>' . $data['date_creation'] . '</td>';
            $html .= '<td>' . $data['statut'] . '</td>';
            $html .= '<td>' . $data['id_user'] . '</td>';
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
  

    public function listRecsByIdUser($id) {
        $sql = "SELECT * FROM reclamations where id_user = :id";
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