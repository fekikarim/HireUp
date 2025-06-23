<?php


require_once __DIR__ . '/../config.php';

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/Foo.php';





class dmdCon
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

    public function dmdExists($iddemande, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM demande WHERE iddemande = :iddemande";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':iddemande', $iddemande);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generatedmdId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->dmdExists($current_id, $db));

        return $current_id;
    }

    public function listdmd()
    {
        $sql = "SELECT * FROM demande";

        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function updateStatus($id, $newStatus)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE demande SET status = :d WHERE iddemande = :id");
            $query->execute(['id' => $id, 'd' => $newStatus]);
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }

    public function updatePay($id, $newStatus)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE demande SET paid = :d WHERE iddemande = :id");
            $query->execute(['id' => $id, 'd' => $newStatus]);
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }

    public function adddmd($dmd)
    {
        $sql = "INSERT INTO demande(iddemande, titre, contenu, objectif ,dure ,budget, image, status, user_id, paid) VALUES (:iddemande, :titre, :contenu, :objectif, :dure, :budget, :image, :status, :user_id, :paid)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
                [
                    'iddemande' => $dmd->get_iddemande(),
                    'titre' => $dmd->get_titre(),
                    'contenu' => $dmd->get_contenu(),
                    'objectif' => $dmd->get_objectif(),
                    'dure' => $dmd->get_dure(),
                    'budget' => $dmd->get_budget(),
                    'image' => $dmd->get_image(),
                    'status' => "pending",
                    'user_id' => $dmd->get_user_id(),
                    'paid' => 'false',
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updatedmd($dmd, $iddemande)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE demande SET titre = :titre, contenu = :contenu, objectif = :objectif, dure = :dure, budget = :budget ,image= :image WHERE iddemande = :id");
            $query->execute([
                'id' => $iddemande,
                'titre' => $dmd->get_titre(),
                'contenu' => $dmd->get_contenu(),
                'objectif' => $dmd->get_objectif(),
                'dure' => $dmd->get_dure(),
                'budget' => $dmd->get_budget(),
                'image' => $dmd->get_image()
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }

    function updatedmdWithoutImg($dmd, $iddemande)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE demande SET titre = :titre, contenu = :contenu, objectif = :objectif, dure = :dure, budget = :budget WHERE iddemande = :id");
            $query->execute([
                'id' => $iddemande,
                'titre' => $dmd->get_titre(),
                'contenu' => $dmd->get_contenu(),
                'objectif' => $dmd->get_objectif(),
                'dure' => $dmd->get_dure(),
                'budget' => $dmd->get_budget()
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo ($e);
        }
    }


    public function deletedmd($iddemande)
    {
        $sql = "DELETE FROM demande WHERE iddemande = :iddemande";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':iddemande', $iddemande);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }


    public function sortdmd($by)
    {
        $sql = "SELECT * FROM demande"; // Pas besoin de "Ajout du mot-clé FROM" ici

        if ($by == "everything") {
            $sql .= " ORDER BY iddemande"; // Utilisation de "iddemande" pour le tri par défaut
        } else {
            $sql .= " ORDER BY $by";
        }

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();

            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }




    public function getdmd($iddemande)
    {
        $sql = "SELECT * FROM demande WHERE iddemande = $iddemande";
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
    public function searchdmd($by, $keyword, $status)
    {
        $db = config::getConnexion();
        try {
            if ($by == "everything") {
                $sql = "SELECT * FROM demande WHERE (titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%' OR objectif LIKE '%$keyword%' OR dure LIKE '%$keyword%' or budget LIKE '%$keyword%' OR iddemande LIKE '%$keyword%')";
            } else {
                $sql = "SELECT * FROM demande WHERE $by LIKE '%$keyword%'";
            }

            if ($status != "none") {
                $sql .= " AND status = '$status'";
            }

            $query = $db->prepare($sql);
            $query->execute();
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function searchdmdSorted($by, $keyword, $status)
    {
        $db = config::getConnexion();
        try {
            $sql = "SELECT * FROM demande WHERE ";
            if ($by == "everything") {
                $sql .= "titre LIKE '%$keyword%' OR contenu LIKE '%$keyword%'OR objectif LIKE '%$keyword%'OR dure LIKE '%$keyword%' OR budget LIKE '%$keyword%' OR iddemande LIKE '%$keyword%'";
            } else {
                $sql .= "$by LIKE '%$keyword%'";
            }

            if ($status != "none") {
                $sql .= " AND status = '$status'";
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
    public function generatePDF1($iddemande)
    {

        $data = $this->getdmd($iddemande);

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
        $stylesheet = file_get_contents('/style.css');
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
        $html .= '<h1>facture</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Sujet</th><th>Description</th><th>Date Creation</th><th>Statut</th><th>ID User</th></tr>';

        $html .= '<tr>';
        $html .= '<td>' . $data['iddemande'] . '</td>';
        $html .= '<td>' . $data['titre'] . '</td>';
        $html .= '<td>' . $data['dure'] . '</td>';
        $html .= '<td>' . $data['image'] . '</td>';

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

    public function generatePDF($iddemande)
    {

        $data = $this->getdmd($iddemande);

        // var_dump($data);
        // exit();


        /**
         * Tutorial uses MPDF. You can find more information here: https://mpdf.github.io/
         * run "composer require mpdf/mpdf" to install first or if you download this entire project, run "composer update" to pull it from the current composer file.
         */
        require_once __DIR__ . '/vendor/autoload.php';
        require_once __DIR__ . '/includes/Foo.php';

        $mpdf = new \Mpdf\Mpdf();
        /**
         * Password protect document
         */
        $mpdf->SetProtection([], 'UserPassword', 'Password');

        /**
         * Set file properties
         */
        $mpdf->SetTitle('The DevDrawer Is Awesome');
        $mpdf->SetAuthor('The DevDrawer');

        /**
         * Setup header and footer content / properties, split content left, right, and center with a pipe |
         */
        $mpdf->defaultheaderline = 0;
        $mpdf->setHeader('|Document Title|');
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('Document Title|{DATE F j, Y}|{PAGENO}');

        /**
         * Add external stylesheet
         */
        $stylesheet = file_get_contents('style.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        /**
         * Add a watermark
         */
        $mpdf->SetWatermarkText('THE DEV DRAWER');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = .1;

        /**
         * Add content using direct or string method
         */

        // Load HTML content
        $html = '<html><body>';
        $html .= '<h1>User List</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>Name</th><th>Email</th></tr>';
        $html .= '<tr>';
        $html .= '<td>' . $data['iddemande'] . '</td>';
        $html .= '<td>' . $data['titre'] . '</td>';
        $html .= '<td>' . $data['dure'] . '</td>';
        //$html .= '<td>' . $data['image'] . '</td>';


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
        //$mpdf->Output('filename.pdf', 'D');
        //$mpdf->Output('filename.pdf', 'F');
    }


    public function getPubByIdDmd($id_pub)
    {
        $sql = "SELECT * FROM publicite WHERE id_demande = :id_pub";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id_pub', $id_pub); // Here, bind to $id_pub instead of $id_dmd
            $query->execute();
            $event = $query->fetch();
            return $event;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

}







?>