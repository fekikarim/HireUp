<?php

require_once __DIR__ . '/../../../Controller/categoryC.php';

$categoryC = new categoryController();

if (isset($_GET["id"]) && isset($_GET["int_type"])) {
    $id = $_GET["id"];
    $int_type = $_GET["int_type"];

    if ($int_type == 'liked') {
        $categoryC->GenerateCategoryAlreadyIntrestedOrNotSection($id, 'true');
    } else if ($int_type == 'disliked') {
        $categoryC->GenerateCategoryAlreadyIntrestedOrNotSection($id, 'false');
    } else {
        echo 'error';
    }
   
} else {
    echo 'error';
}

?>