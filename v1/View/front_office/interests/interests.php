<!-- <?php
/*
function getUserLocation()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $api_url = "https://freegeoip.app/json/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirections
    $response = curl_exec($ch);
    //var_dump($response);
    curl_close($ch);

    if ($response) {
        return $response;
    } else {
        return false;
    }
}
*/
?> -->



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
    <title>HireUp Interests</title>
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


    <style>
        /* Popup modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            /* Ensure it overlays other content */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
        }

        .valid-message {
            color: #aaa;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 1000px;
            /* Limit maximum width */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add shadow for depth */
            z-index: 99999;
            /* Ensure it overlays other content */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Adjustments to the main content when modal is open */
        .modal-open {
            overflow: hidden;
            /* Prevent scrolling */
        }



        /* JOB IMAGE STYLESHEET */
        /* Style for job container */
        .job-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
        }

        /* Style for job image */
        .job-img-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .voice-icon {
            cursor: pointer;
            margin-left: 5px;
        }

        /* Style for job container */
        .hidden-job-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */

        }
    </style>

    <style>
        /* Styling for the popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        /* Styling for the overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
    </style>

    <style>
        progress {
            display: inline-block;
            position: relative;
            background: none;
            border: 0;
            border-radius: 5px;
            width: 100%;
            text-align: left;
            position: relative;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.8em;
        }

        progress::-webkit-progress-bar {
            margin: 0 auto;
            background-color: #CCC;
            border-radius: 5px;

        }

        progress::-webkit-progress-value {
            display: relative;
            margin: 0px -10px 0 0;
            background: #55bce7;
            border-radius: 5px;
        }

        progress:after {
            margin: -36px 0 0 7px;
            padding: 0;
            display: inline-block;
            float: right;
            content: attr(value) '%';
            position: relative;
        }
    </style>

    <style>
        .popup-card {
            display: none;
            position: fixed;
            z-index: 99999999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(245, 245, 245, 0.4);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            max-height: 100%;
            min-height: auto;
            min-width: auto;
            padding: 20px;
            border-radius: 5px;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .skills-list {
            list-style-type: none;
            padding: 0;
        }

        .skills-list li {
            margin-bottom: 5px;
        }

        .skills-list .found {
            color: green;
        }

        .skills-list .not-found {
            color: red;
        }

        .progress-bar-container {
            margin-top: 10px;
            padding: 2% 10%;
        }

        .progress-bar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 20px;
            background-color: #55bce7;
            width: 0;
            text-align: center;
            color: white;
            line-height: 20px;
        }
    </style>

    <style>
        .popup-card {
            display: none;
            position: fixed;
            z-index: 99999999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(245, 245, 245, 0.4);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            max-height: 100%;
            min-height: auto;
            min-width: auto;
            padding: 20px;
            border-radius: 5px;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            border: 1px solid #888;
            width: 80%;
            height: 82%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .popup-content iframe {
            width: 100%;
            height: 82%;
            /* Set the height to adjust based on content */
        }
    </style>

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



    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

    <!-- Overlay to cover the background -->
    <div id="overlay" class="overlay"></div>




    <div class="preloader">
        <div class="preloader_image"></div>
    </div>


    <!-- wrappers for visual page editor and boxed version of template -->
    <div id="canvas">

        <div id="box_wrapper">


            <!-- header -->
            <?php
            $active_page = 'jobs';
            include ('../front_header.php');
            ?>


            <section class="page_title cs s-py-25" style="background-color: #283149 !important;">
                <div class="divider-100" style="margin-bottom: 150px;"></div>

            </section>

            <section class="page_title cs s-py-25" style="background-color: #283149 !important;">
                <div class="container">
                    <div class="row">
                        <div class="divider-50"></div>

                        <div class="col-md-12 text-center">
                            <h1 class="">Interests</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../../../index.php">Home</a>
                                </li>

                                <li class="breadcrumb-item active">Interests</li>
                            </ol>
                        </div>

                        <div class="divider-50"></div>
                    </div>
                </div>
            </section>

            <br>
            <div class="d-none d-lg-block divider-60"></div>



            <section class="ls s-py-50 s-py-50">
                <div class="container">
                    <div class="d-none d-lg-block divider-20"></div>

                    <div id="popup-interests-liked"><?php $categoryC->GenerateCategoryAlreadyIntrestedOrNotSection($user_profile_id, 'true'); ?></div>
                    <div div id="popup-interests-disliked"><?php $categoryC->GenerateCategoryAlreadyIntrestedOrNotSection($user_profile_id, 'false'); ?></div>
                    <div div id="popup-interests-suggestions"><?php $categoryC->GenerateCategoryIntrestedSuggestionsSection($user_profile_id); ?></div>

                </div>
            </section>


            <!-- Footer -->
            <?php include (__DIR__ . '/../../../View/front_office/front_footer.php') ?>
            <!-- End Footer -->

            <?php
            include './../jobs management/chatbot.php';
            ?>




        </div>
        <!-- eof #box_wrapper -->
    </div>
    <!-- eof #canvas -->
    <!-- Font Awesome library -->




    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/chatbot.js"></script>
    <script src="./../../../front office assets/js/switcher.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>





    <!-- voice recognation -->
    <script type="text/javascript"
        src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


    <!-- category Intrests Slider -->
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

        function fetchDataSuggestions(user_profile_id, callback) {
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
            xhr.open('GET', 'get_user_profile_suggestions_intrests.php?id=' + user_profile_id, true);
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

            // get suggestions intrests
            fetchDataSuggestions(user_profile_id, function (responseData) {

                console.log(responseData);
                var dislikes_div = document.getElementById('popup-interests-suggestions');
                dislikes_div.innerHTML = responseData;

            });


        }

       // show_intrests_popup('<?//= $user_profile_id ?>');

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
                    //console.log(xhr.responseText);
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
                    //console.log(xhr.responseText);
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



</body>

</html>