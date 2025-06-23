<?php 

require_once __DIR__ . '/../../../Controller/pub_con.php';

$pubb = new pubCon("pub");

if (isset($_GET['id'])) {
    $pub_id = htmlspecialchars($_GET['id']);

    $pubb->updatePubliciteClickedTimes($pub_id);

    echo 'true';
    
}

?>