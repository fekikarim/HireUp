/*function update_number_btn_clicked() {
    verificationModal = document.querySelector(".verification-modal");

    console.log("Update number button clicked");
    // Show verification form
    verificationModal.style.display = "block";
    // Disable scrolling
    document.body.style.overflow = "hidden";


}*/

// Check if session cookie exists
function isSessionStarted() {
    return document.cookie.indexOf('session=') !== -1;
}

// Start session
function startSession() {
    if (!isSessionStarted()) {
        // Set session cookie
        document.cookie = "session=started; path=/";
    }
}

function generateFourDigitNumber() {
    // Generate a random number between 1000 and 9999
    return Math.floor(Math.random() * 9000) + 1000;
  }


  function executePHPFunction(url_path, methode) {
    var xhr = new XMLHttpRequest();
    xhr.open(methode, url_path, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            console.log(response); // Output: "Hello from PHP!"
        }
    };
    xhr.send();
}



startSession();

document.addEventListener("DOMContentLoaded", function() {
    const updateNumberButton = document.getElementById("update_number_button");
    const verificationModal = document.querySelector(".verification-modal");
    //var phone_nb = document.getElementById("profile_phone_number").value;
    var phone_nb = document.getElementById("old_profile_phone_number").value;
    //alert(phone_nb);

    // Update number button click event
    updateNumberButton.addEventListener("click", function() {
        // Show verification form
        verificationModal.style.display = "block";
        // Disable scrolling
        document.body.style.overflow = "hidden";

        // Generate a random number
        //var randomNumber = generateFourDigitNumber();
        //executePHPFunction("sendSms.php", "POST");
        executePHPFunction("sendSms.php?phone_nb="+phone_nb, "POST");

    });

    // Close verification form
    document.querySelector(".verification-modal .close").addEventListener("click", function() {
        verificationModal.style.display = "none";
        // Enable scrolling
        document.body.style.overflow = "auto";

        //<?php $code = $prf->generateVerificationCode() ?>
    });


    // Clear button click event
    document.querySelector(".verification-modal .clear").addEventListener("click", function() {
        // Clear input fields
        document.querySelectorAll(".verification-modal .input-fields input").forEach(function(input) {
            input.value = "";
        });
    });
});



