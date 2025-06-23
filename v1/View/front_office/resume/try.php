<!DOCTYPE html>
<html lang="en">

<head>
    <title>HireUp try</title>
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
    <div id="output"></div>

    <script>
        function generateCategoryInterestSuggestions(jsonData, outputElementId) {

            profileId = '1';

            // Parse JSON data
            const categories = JSON.parse(jsonData);

            // Check if data contains categories
            if (!categories || !Array.isArray(categories)) {
                console.error('Invalid JSON data');
                return;
            }

            let html = `
        <section class="ls s-py-lg-50 main_blog">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="contact-header text-center">
                            <h5>Things</h5>
                            <h4>You Might Love</h4>
                        </div>
                        <div class="d-none d-lg-block divider-20"></div>
                        <div class="owl-carousel" data-responsive-lg="3" data-responsive-md="2" data-responsive-sm="2" data-nav="false" data-dots="false">
    `;

            // Loop through categories
            categories.forEach(category => {
                const categoryName = category.name_category;
                const categoryId = category.id_category;

                html += `
            <article class="box vertical-item text-center content-padding padding-small bordered post type-post status-publish format-standard has-post-thumbnail">
                <div class="item-content" style="min-height: 280px !important;">
                    <header class="blog-header ">
                        <a href="javascript:void(0)" rel="bookmark">
                            <h4>${categoryName}</h4>
                        </a>
                    </header>
                    <div class="blog-item-icons" id="blog-item-icons-catid-${categoryId}">
                        <div class="col-sm-4 pr-5" onclick="likeCategory('${categoryId}', '${profileId}')">
                            <a href="javascript:void(0)" class="Interested-btns-like" id="like-a-with-catid-${categoryId}">
                                <i class="fa-solid fa-pen-to-square" id="like-i-with-catid-${categoryId}"></i> Edit
                            </a>
                        </div>
                        <div class="col-sm-4 pr-5" onclick="dislikeCategory('${categoryId}', '${profileId}')">
                            <a href="javascript:void(0)" class="Interested-btns-dislike" id="dislike-a-with-catid-${categoryId}">
                                <i class="fa-solid fa-circle-xmark Interested-btns-dislike" id="dislike-i-with-catid-${categoryId}"></i> Remove
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        `;
            });

            html += `
                        </div>
                    </div>
                </div>
            </div>
        </section>
    `;

            // Output HTML
            document.getElementById(outputElementId).innerHTML = html;
        }

        generateCategoryInterestSuggestions('[{"id_category":"1","name_category":"Category 1"},{"id_category":"2","name_category":"Category 2"},{"id_category":"3","name_category":"Category 3"}]', 'output');
    </script>

    <script src="./../../../front office assets/js/compressed.js"></script>
    <script src="./../../../front office assets/js/main.js"></script>
    <script src="./../../../front office assets/js/chatbot.js"></script>
    <script src="./../../../front office assets/js/switcher.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>

    <!-- voice recognation -->
    <script type="text/javascript"
        src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

</body>
</html>