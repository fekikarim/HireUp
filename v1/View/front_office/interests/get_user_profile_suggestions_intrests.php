<?php

require_once __DIR__ . '/../../../Controller/categoryC.php';

$categoryC = new categoryController();

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $categoryC->GenerateCategoryIntrestedSuggestionsSection($id);
   
} else {
    echo 'error';
}

?>