<?php
// Include the controller file
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/categoryC.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


// Create an instance of JobController
$profileController = new ProfileC();
$categoryC = new categoryController();


$user_id = '';
$user_profile_id = '';


if (isset($_SESSION['user id'])) {

    $user_id = htmlspecialchars($_SESSION['user id']);

    // Get profile ID from the URL
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);

    $profile = $profileController->getProfileById($user_profile_id);
}







$block_call_back = 'false';
$access_level = "else";
include ('./../../../View/callback.php');


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>HireUp Jobs</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <link rel="stylesheet" href="./../../../front office assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/animations.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/font-awesome.css" />
    <link rel="stylesheet" href="./../../../front office assets/css/main.css" class="color-switcher-link" />
    <script src="./../../../front office assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

    <!-- Interested-btns -->
    <style>
        .Interested-btns-like:hover,
        .Interested-btns-like-active {
            color: #55bce7 !important;
        }

        .Interested-btns-dislike:hover,
        .Interested-btns-dislike-active {
            color: #ff0000 !important;
        }
    </style>
</head>
<body>

<div id="popup-interests-liked"></div>
<div id="popup-interests-disliked"></div>

<script>

function fetchData(user_profile_id, int_type, callback) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var responseData = xhr.responseText;
        if (responseData != 'error') {
          // Call the callback function with the response data
          callback(responseData);
        }

      } else {
        // Handle errors
        console.error('Request failed with status:', xhr.status);
      }
    }
  };
  xhr.open('GET', 'get_user_profile_intrests.php?id=' + user_profile_id + '&int_type=' + int_type, true);
  xhr.send();
}


function show_intrests_popup(user_profile_id) {

  // get liked intrests
  fetchData(user_profile_id, 'liked', function (responseData) {

    console.log(responseData);
    var likes_div = document.getElementById('popup-interests-liked');
    likes_div.innerHTML = responseData;

  });

  // get disliked intrests
  fetchData(user_profile_id, 'disliked', function (responseData) {

    console.log(responseData);
    var dislikes_div = document.getElementById('popup-interests-disliked');
    dislikes_div.innerHTML = responseData;

  });


}

show_intrests_popup('<?= $user_profile_id ?>');

</script>

<!-- category Intrests Slider -->
<script>

function like_category(categoryId, profileId) {

  var like_btn_a = document.getElementById('like-a-with-catid-' + categoryId);
  var like_btn_i = document.getElementById('like-i-with-catid-' + categoryId);

  var dislike_btn_a = document.getElementById('dislike-a-with-catid-' + categoryId);
  var dislike_btn_i = document.getElementById('dislike-i-with-catid-' + categoryId);

  // Create a new XMLHttpRequest object
  const xhr = new XMLHttpRequest();

  // Define the URL of your PHP script
  const url = 'like_a_category.php';

  // Define the data to be sent in the request body
  const data = new URLSearchParams();
  data.append('category_id', categoryId);
  data.append('profile_id', profileId);

  // Open the request
  xhr.open('POST', url, true);

  // Set the Content-Type header
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Set up the onload event handler
  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      // Success! Handle the response
      console.log(xhr.responseText);
      if (xhr.responseText == 'interest added successfully' || xhr.responseText == 'interest updated successfully') {
        like_btn_a.classList.remove('Interested-btns-like');
        like_btn_a.classList.add('Interested-btns-like-active');
        like_btn_i.classList.remove('Interested-btns-like');
        like_btn_i.classList.add('Interested-btns-like-active');

        dislike_btn_a.classList.remove('Interested-btns-dislike-active');
        dislike_btn_a.classList.add('Interested-btns-dislike');
        dislike_btn_i.classList.remove('Interested-btns-dislike-active');
        dislike_btn_i.classList.add('Interested-btns-dislike');

      } else if (xhr.responseText == 'interest deleted successfully') {
        like_btn_a.classList.remove('Interested-btns-like-active');
        like_btn_a.classList.add('Interested-btns-like');
        like_btn_i.classList.remove('Interested-btns-like-active');
        like_btn_i.classList.add('Interested-btns-like');
      }
    } else {
      // Request failed
      console.error('Request failed with status:', xhr.status);
    }
  };

  // Set up the onerror event handler
  xhr.onerror = function () {
    // There was a network error
    console.error('Network error occurred');
  };

  // Send the request
  xhr.send(data);
}

function dislike_category(categoryId, profileId) {

  var like_btn_a = document.getElementById('like-a-with-catid-' + categoryId);
  var like_btn_i = document.getElementById('like-i-with-catid-' + categoryId);

  var dislike_btn_a = document.getElementById('dislike-a-with-catid-' + categoryId);
  var dislike_btn_i = document.getElementById('dislike-i-with-catid-' + categoryId);

  // Create a new XMLHttpRequest object
  const xhr = new XMLHttpRequest();

  // Define the URL of your PHP script
  const url = 'dislike_a_category.php';

  // Define the data to be sent in the request body
  const data = new URLSearchParams();
  data.append('category_id', categoryId);
  data.append('profile_id', profileId);

  // Open the request
  xhr.open('POST', url, true);

  // Set the Content-Type header
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Set up the onload event handler
  xhr.onload = function () {
    if (xhr.status >= 200 && xhr.status < 300) {
      // Success! Handle the response
      console.log(xhr.responseText);
      if (xhr.responseText == 'interest added successfully' || xhr.responseText == 'interest updated successfully') {
        dislike_btn_a.classList.remove('Interested-btns-dislike');
        dislike_btn_a.classList.add('Interested-btns-dislike-active');
        dislike_btn_i.classList.remove('Interested-btns-dislike');
        dislike_btn_i.classList.add('Interested-btns-dislike-active');

        like_btn_a.classList.remove('Interested-btns-like-active');
        like_btn_a.classList.add('Interested-btns-like');
        like_btn_i.classList.remove('Interested-btns-like-active');
        like_btn_i.classList.add('Interested-btns-like');

      } else if (xhr.responseText == 'interest deleted successfully') {
        dislike_btn_a.classList.remove('Interested-btns-dislike-active');
        dislike_btn_a.classList.add('Interested-btns-dislike');
        dislike_btn_i.classList.remove('Interested-btns-dislike-active');
        dislike_btn_i.classList.add('Interested-btns-dislike');
      }
    } else {
      // Request failed
      console.error('Request failed with status:', xhr.status);
    }
  };

  // Set up the onerror event handler
  xhr.onerror = function () {
    // There was a network error
    console.error('Network error occurred');
  };

  // Send the request
  xhr.send(data);
}


</script>
<!-- End category Intrests Slider -->

    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/scripts.js"></script>
    <script src="./../../../front office assets/js/chatbot.js"></script>
    <script src="./../../../front office assets/js/switcher.js"></script>

</body>
</html>