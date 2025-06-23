function verif_reponse_managemet_inputs(){

    contenu = document.getElementById('contenu').value.trim()
    date_reponse = document.getElementById('date_reponse').value.trim()
    user_id = document.getElementById('id_user').value.trim()
    reclamation_id = document.getElementById('id_reclamation').value.trim()
    



    // contenu
    if (contenu == ""){
        document.getElementById('contenu_error').innerText = "Content can't be empty";
        return false;
    } else {
        document.getElementById('contenu_error').innerText = "";
    }



    // date_reponse
    if (date_reponse == ""){
        document.getElementById('date_reponse_error').innerText = "Date can't be empty";
        return false;
    } else {
        document.getElementById('date_reponse_error').innerText = "";
    }

   

    // user id
    // Regular expression for validating username
    var useridRegex = /^[0-9]+$/;

    // Validate username
    if (!useridRegex.test(user_id)) {
        document.getElementById('id_user_error').innerText = "ID user can only contain numbers";
        return false;
    } else {
        document.getElementById('id_user_error').innerText = "";
    }


     // reclamation id
    // Regular expression for validating reclamation
    var useridRegex = /^[0-9]+$/;

    // Validate username
    if (!useridRegex.test(reclamation_id)) {
        document.getElementById('id_reclamation_error').innerText = "ID reclamation can only contain numbers";
        return false;
    } else {
        document.getElementById('id_reclamation_error').innerText = "";
    }


    
    return true;
 

}

function verif_reponse_managemet_inputs_front(){

    contenu = document.getElementById('contenu').value.trim()
    user_id = document.getElementById('id_user').value.trim()
    reclamation_id = document.getElementById('id_reclamation').value.trim()


    // sujet
    if (contenu == ""){
        document.getElementById('contenu_error').innerText = "Contenu can't be empty";
        return false;
    } else {
        document.getElementById('contenu_error').innerText = "";
    }

    


    // user id
    // Regular expression for validating username
    var useridRegex = /^[0-9]+$/;

    // Validate user id
    if (!useridRegex.test(user_id)) {
        document.getElementById('id_user_error').innerText = "ID user can only contain numbers";
        return false;
    } else {
        document.getElementById('id_user_error').innerText = "";
    }


    // reclamation id
    // Regular expression for validating reclamation
    var useridRegex = /^[0-9]+$/;

    // Validate user id
    if (!useridRegex.test(reclamation_id)) {
        document.getElementById('id_reclamation_error').innerText = "ID reclamation can only contain numbers";
        return false;
    } else {
        document.getElementById('id_reclamation_error').innerText = "";
    }

    
    return true;
 

}
