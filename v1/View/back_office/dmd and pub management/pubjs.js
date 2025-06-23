function verif() {
    var titre = document.getElementById('titre').value.trim();
    var contenu = document.getElementById('contenu').value.trim();
    var dat = document.getElementById('dat').value.trim();

    // Regular expression for validating fields (allowing letters and spaces)
    var chaine = /^[a-zA-Z ]+$/;

    // Validate titre
    if (!chaine.test(titre)) {
        document.getElementById('titre_error').innerText = "Le titre ne peut contenir que des lettres et des espaces";
        return false;
    } else {
        document.getElementById('titre_error').innerText = "";
    }
    // Validate contenu
    if (!chaine.test(contenu)) {
        document.getElementById('contenu_error').innerText = "Le contenu ne peut contenir que des lettres et des espaces";
        return false;
    } else {
        document.getElementById('contenu_error').innerText = "";
    }
   
    // Convert the entered date into a Date object
    var enteredDate = new Date(dat);
    // Get today's date
    var today = new Date();

    // Check if the entered date is before today's date
    if (enteredDate < today) {
        document.getElementById('dat_error').innerText = "La date ne peut être antérieure à aujourd'hui";
        return false;
    } else {
        document.getElementById('dat_error').innerText = "";
    }

    return true;
}
