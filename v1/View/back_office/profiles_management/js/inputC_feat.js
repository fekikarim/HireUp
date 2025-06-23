var nameError = document.getElementById('name_error');
var descError = document.getElementById('desc_error');
var subsError = document.getElementById('subs_error');


var submitError = document.getElementById('submit_error');

function validateName(){
    var name = document.getElementById('feature_name').value;

    if(name.length == 0){
        nameError.innerHTML = 'Feature Name is required.';
        return false;
    }
    if(name.length < 2){
        nameError.innerHTML = 'Feature Name must be at least 2 characters.';
        return false;
    }
    nameError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}


function validateSubs() {
    var subs = document.getElementById('plan_name').value;
    var subsError = document.getElementById('subs_error');

    // Check if the selected option is not the first row
    if (!subs || subs === "Select Subscription Feature") {
        subsError.innerHTML = 'Please select a Subscription Feature.';
        return false;
    }

    subsError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}

function validateDescription(){
    var desc = document.getElementById('description').value;
    var maxLength = 200;
    var charactersRemaining = maxLength - desc.length;

    if(desc.length == 0){
        descError.innerHTML = 'Description is required.';
        return false;
    }
    if (desc.length < 2){
        descError.innerHTML = 'Description must be at least 2 characters.';
        return false;
    }   
    if(charactersRemaining >= 0 && desc.length >= 2){
        descError.innerHTML = '<b class="text-secondary">' + charactersRemaining + ' characters remaining </b>';
        return true;
    } 
    else {
        descError.innerHTML = 'Feature description should not exceed 200 characters';
        return false;
    }
}



function validateForm(){
    if(!validateName() || !validateSubs() || !validateDescription()){
        submitError.innerHTML = 'Please fix error to submit.';
        return false;
    }
}