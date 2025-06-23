<?php

require_once __DIR__ . '/../config.php';

class FaceController
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

    public function faceExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM faces WHERE id = :id";
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

    public function generateFaceId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->faceExists($current_id, $db));

        return $current_id;
    }

    public function listFaces()
    {
        $sql = "SELECT * FROM faces";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function addFace($face)
    {
        $sql = "INSERT INTO faces(id, user_id, content, name) 
                VALUES (:id, :user_id, :content, :name)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $face->getId(),
                'user_id' => $face->getUserId(),
                'content' => $face->getContent(),
                'name' => $face->getName(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateFace($face, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE faces 
                                   SET user_id = :user_id, content = :content, name = :name
                                   WHERE id = :id");
            $query->execute([
                'id' => $id,
                'user_id' => $face->getUserId(),
                'content' => $face->getContent(),
                'name' => $face->getName(),
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteFace($id)
    {
        $sql = "DELETE FROM faces WHERE id = :id";
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

    public function getFace($id)
    {
        $sql = "SELECT * FROM faces WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $face = $query->fetch();
            return $face;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getFaceByUserId($id)
    {
        $sql = "SELECT * FROM faces WHERE user_id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $face = $query->fetch();
            return $face;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getImageExtension($imageBlob)
    {
        $bytes = substr($imageBlob, 0, 2);

        // JPEG
        if ($bytes === "\xFF\xD8") {
            return '.jpg';
        }
        // PNG
        elseif ($bytes === "\x89\x50") {
            return '.png';
        }
        // GIF
        elseif ($bytes === "GIF") {
            return '.gif';
        }
        // BMP
        elseif (substr($bytes, 0, 2) === "BM") {
            return '.bmp';
        }
        // TIFF
        elseif (($bytes === "II" && substr($imageBlob, 2, 2) === "\x2A\x00") || ($bytes === "MM" && substr($imageBlob, 2, 2) === "\x00\x2A")) {
            return '.tiff';
        }
        // WebP
        elseif (substr($imageBlob, 8, 4) === "WEBP") {
            return '.webp';
        }
        // SVG
        elseif (strpos($imageBlob, '<svg') !== false) {
            return '.svg';
        }
        // Unknown format
        else {
            return false;
        }
    }

    public function saveImageToFile($imageBlob, $filename)
    {
        // Determine file extension based on magic bytes
        $fileExtension = $this->getImageExtension($imageBlob);

        if (!$fileExtension) {
            return false; // Unable to determine image format
        }

        // Set the file path
        $filePath = __DIR__ . '/py_face_recognation/photos/' . $filename . $fileExtension;

        // Write image data to file
        file_put_contents($filePath, $imageBlob);

        return $filePath;
    }

    public function loadAllFacesImages() {
        $faces = $this->listFaces();
        foreach ($faces as $face) {
            $face_img_blob = $face['content'];
            
            //save the image
            $filename = $face['user_id'];
            $savedFilePath = $this->saveImageToFile($face_img_blob, $filename);
        }
    }

    public function extract_id($string) {
        // Split the string by ':' and get the second part
        $parts = explode(':', $string);
        // Get the ID from the second part, trim any whitespace
        $id = trim($parts[1]);
        return $id;
    }

    public function faceExistsByUserId($id)
    {
        $sql = "SELECT COUNT(*) as count FROM faces WHERE user_id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0; // Return true if count > 0, indicating existence
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

}

?>
