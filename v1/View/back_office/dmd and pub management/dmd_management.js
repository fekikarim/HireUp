function verif_pub_manaet_inputs(){
    titre = document.getElementById('titre').value.trim()
    contenu = document.getElementById('contenu').value.trim()
    objectif = document.getElementById('objectif').value.trim()
    dure = document.getElementById('dure').value.trim()
    budget = document.getElementById('budget').value.trim()


    // Regular expression for validating des champs
    var chaine = /^[a-zA-Z_ ]+$/;
    var entier = /^\d+$/;


    // Validate titre
    if (!chaine.test(titre)) {
        document.getElementById('titre_error').innerText = "titre can only contain letters";
        return false;
    } else {
        document.getElementById('titre_error').innerText = "";
    }
    // Validate contenu
    if (!chaine.test(contenu)) {
        document.getElementById('contenu_error').innerText = "contenu can only contain letters";
        return false;
    } else {
        document.getElementById('contenu_error').innerText = "";
    }
    // Validate objectif
    if (!chaine.test(objectif)) {
        document.getElementById('objectif_error').innerText = "objectif can only contain letters";
        return false;
    } else {
        document.getElementById('objectif_error').innerText = "";
    }
    // Validate dure
    if (!entier.test(dure)) {
        document.getElementById('dure_error').innerText = "dure can only contain une dure";
        return false;
    } else {
        document.getElementById('dure_error').innerText = "";
    }
    // Validate budget
    if (!entier.test(budget)) {
        document.getElementById('budget_error').innerText = "budget can only contain entier";
        return false;
    } else {
        document.getElementById('budget_error').innerText = "";
    }

    
    return true;

}

// Function to handle file input change for publication photo
function handlePhotoChange(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {	
      const publicationPhoto = document.getElementById('publication_pic_display');
      const hiddenPublicationPhotoContainer = document.getElementById('hiddenPublicationPhotoContainer');

      // Set the source of hidden publication photo
      document.getElementById('hiddenPublicationPhoto').src = e.target.result;

      // Show the hidden publication photo container and hide the displayed photo
      publicationPhoto.style.display = 'none';
      hiddenPublicationPhotoContainer.style.display = 'block';
    };

    reader.readAsDataURL(file);
  }
  function handlePhotoChange(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        const publicationPhoto = document.getElementById('publication_pic_display');
        const hiddenPublicationPhotoContainer = document.getElementById('hiddenPublicationPhotoContainer');

        // Set the source of hidden publication photo
        document.getElementById('hiddenPublicationPhoto').src = e.target.result;

        // Show the hidden publication photo container and hide the displayed photo
        publicationPhoto.style.display = 'none';
        hiddenPublicationPhotoContainer.style.display = 'block';
    };

    reader.readAsDataURL(file);
}
function changeStatus(demandeId, newStatus) {
    // Vous pouvez utiliser AJAX pour envoyer une requête au serveur pour mettre à jour le statut de la demande
    // Par exemple, en utilisant jQuery AJAX :

    $.ajax({
        type: "POST",
        url: "change_status.php", // Le fichier PHP qui gère la mise à jour du statut
        data: { demande_id: demandeId, new_status: newStatus },
        success: function(response) {
            // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires après la mise à jour du statut
            console.log("Statut de la demande mis à jour avec succès !");
        },
        error: function(xhr, status, error) {
            // Gérez les erreurs ici
            console.error("Erreur lors de la mise à jour du statut de la demande :", error);
        }
    });
}
