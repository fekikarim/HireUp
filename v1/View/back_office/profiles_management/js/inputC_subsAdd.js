var nameError = document.getElementById('name_error');
var durError = document.getElementById('dur_error');
var priceError = document.getElementById('price_error');
var cardError = document.getElementById('card_error');
var statusError = document.getElementById('status_error');
var descError = document.getElementById('desc_error');

var submitError = document.getElementById('submit_error');

function validateName(){
    var name = document.getElementById('plan_name').value;

    if(name.length == 0){
        nameError.innerHTML = 'Name Plan is required.';
        return false;
    }
    if(name.length < 2){
        nameError.innerHTML = 'Name Plan must be at least 2 characters.';
        return false;
    }
    nameError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}

function validateDuration(){
    var duration = document.getElementById('duration').value;

    if(duration.length == 0){
        durError.innerHTML = 'Duration is required.';
        return false;
    }
    if(duration.length < 2){
        durError.innerHTML = 'Duration must be at least 2 characters.';
        return false;
    }
    if(!/^[a-zA-Z ]+$/.test(duration)){
        durError.innerHTML = 'Duration contain only alphabets.';
        return false;
    }
    durError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}

function validatePrice() {
    var price = document.getElementById('price').value.trim();
    var priceError = document.getElementById('price_error');

    // Regular expression to validate price format
    var priceRegex = /^(?:[$€£¥]?|Dt)\s?\d+(\.\d{1,2})?$/i;

    if (price.length === 0) {
        priceError.innerHTML = 'Price is required.';
        return false;
    }
    if (!priceRegex.test(price)) {
        priceError.innerHTML = 'Invalid price format.';
        return false;
    }

    priceError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}


function validateCard() {
    var card = document.getElementById('card').value;
    var cardError = document.getElementById('card_error');

    // Check if the selected option is not the first row
    if (!card || card === "Select Card Type") {
        cardError.innerHTML = 'Please select a Card Type.';
        return false;
    }

    cardError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    return true;
}


function validateStatus(){
    var status = document.getElementById('subscription_status').value;

    if(!status || status === "Select Status"){
        statusError.innerHTML = 'Status is required.';
        return false;
    }
    statusError.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
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
        descError.innerHTML = charactersRemaining + ' characters remaining';
        return true;
    } 
    else {
        descError.innerHTML = 'Subscription description should not exceed 200 characters';
        return false;
    }
}



function validateForm(){
    if(!validateName() || !validateDuration() || !validatePrice() || !validateCard() || !validateStatus() || !validateDescription()){
        submitError.innerHTML = 'Please fix error to submit.';
        return false;
    }
}