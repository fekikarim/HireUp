<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Start output buffering
ob_start();

include_once __DIR__ . '/../../../Controller/reclamation_con.php';
include_once __DIR__ . '/../../../Model/reclamation.php';

// Function to filter bad words
function filterBadWords($inputString) {
    // Load the bad words from the file into an array
    $badWords = file(_DIR_ . "/badwords.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Replace newline characters with spaces
    $inputString = str_replace(array("\r", "\n"), ' ', $inputString);

    // Convert the input string to lowercase and split it into individual words
    $words = explode(" ", strtolower($inputString));

    // Flag to track if bad words are found
    $badWordFound = false;

    // Iterate through each word and check if it's a bad word
    foreach ($words as $word) {
        // Check if the word is in the list of bad words
        if (in_array($word, $badWords)) {
            // If it's a bad word, set the flag to true
            $badWordFound = true;
            break;
        }
    }

    // Return the flag indicating if bad words were found
    return $badWordFound;
}

// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");

// Création d'une instance de la classe Event 
$reclamation = null;

if (
    isset($_POST["sujet"]) &&
    isset($_POST["description"]) &&
    isset($_POST["id_user"])
) {
    if (
        !empty($_POST['sujet']) &&
        !empty($_POST["description"]) &&
        !empty($_POST["id_user"])
    ) {
        // Check if the description contains bad words
        if (!filterBadWords($_POST["description"])) {
            $currentDate = date("Y-m-d");
        
            $reclamation = new Reclamation(
                $recC->generateRecId(5),
                $_POST['sujet'],
                $_POST['description'],
                $currentDate,
                "pending request",
                $_POST['id_user']
            );        
        
            $recC->addRec($reclamation);
            $SESSION['message'] = "<div class='alert fade alert-simple alert-success alert-dismissible text-left fontfamily-montserrat fontsize-16 font_weight-light brk-library-rendered rendered show'>
            <button type='button' class='close font__size-18' data-dismiss='alert'>
            <span aria-hidden='true'><a><i class='fa fa-times greencross'></i></a></span><span class='sr-only'>Close</span></button>
            <i class='start-icon far fa-check-circle faa-tada animated'></i>
            <strong class='font__weight-semibold'>Well done!</strong> You successfully read this important alert.</div>";
        } else {
            // If the description contains bad words, display an alert with an error message
            $SESSION['message'] = "<div class='alert fade alert-simple alert-danger alert-dismissible text-left fontfamily-montserrat fontsize-16 font_weight-light brk-library-rendered rendered show'>
            <button type='button' class='close font__size-18' data-dismiss='alert'>
            <span aria-hidden='true'><a><i class='fa fa-times danger'></i></a></span><span class='sr-only'>Close</span></button>
            <i class='start-icon far fa-times-circle faa-pulse animated'></i>
            <strong class='font__weight-semibold'>Oh snap!</strong> The description contains inappropriate language.</div>";
        }
        header('Location: ../../../View/front_office/reclamation/reclamation.php');
        exit();
    //ob_end_flush();
}
}