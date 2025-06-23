<?php
$file = fopen("badwords.txt", "r") or die("Impossible d'ouvrir le fichier!");
$forbiddenWords = [];

// Lire le fichier ligne par ligne et stocker les mots interdits dans un tableau
while(!feof($file)) {
    $word = trim(fgets($file)); // Supprimer les espaces inutiles
    if (!empty($word)) {
        $forbiddenWords[] = strtolower($word); // Convertir en minuscules pour une comparaison insensible à la casse
    }
}

fclose($file);
echo json_encode($forbiddenWords); // Envoyer la liste des mots interdits au client JavaScript
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interdit mots</title>
</head>
<body>
<h2>Interdit Mots</h2>
<input type="text" id="inputField" oninput="checkInput()">
<div id="errorMessage" style="color: red;"></div>
<script>
function checkInput() {
    var inputField = document.getElementById("inputField");
    var inputValue = inputField.value.toLowerCase(); // Convertir en minuscules pour une comparaison insensible à la casse
    
    // Liste des mots interdits
    var forbiddenWords = ["mot1", "mot2", "mot3"]; // Remplacez ceci par la liste des mots interdits obtenue depuis le serveur PHP

    var errorMessage = document.getElementById("errorMessage");

    // Vérifier si l'entrée contient un mot interdit
    for (var i = 0; i < forbiddenWords.length; i++) {
        if (inputValue.includes(forbiddenWords[i])) {
            inputField.value = ''; // Effacer le champ d'entrée
            errorMessage.innerText = "Le mot '" + forbiddenWords[i] + "' est interdit!";
            return;
        }
    }

    // Si aucun mot interdit n'est trouvé, effacer le message d'erreur
    errorMessage.innerText = '';
}
</script>



</body>
</html>
