<?php

error_reporting(E_ALL); ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . "/../../../Controller/articleC.php";
$articleController = new ArticleC();


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add") {
        // Add new job
        $title = $_POST["article_title"];
        $content = $_POST["Contenu"];
        $author = $_POST["Auteur"];
        $date_art = date("Y-m-d");
        $category = $_POST["category"];
        $id = $articleController->generateJobId(7);
        $result = $articleController->addArticle($id, $title, $content, $author, $date_art, $category, $article_image = "");
        if ($result !== false) {
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        }
    } elseif ($_POST["action"] == "delete" && isset($_POST["article_id"])) {
        // Delete job
        $article_id = $_POST["article_id"];
        $deleted = $articleController->deleteArticle($article_id);
        if ($deleted) {
            echo "article deleted successfully.";
        } else {
            echo "Error deleting article.";
        }
    } elseif ($_POST["action"] == "update") {
        // Récupérer les données du formulaire
        $id = $_POST['article_id']; // Corrected variable name
        $title = $_POST['article_title']; // Corrected variable name
        $content = $_POST['Contenu']; // Corrected variable name
        $author = $_POST['Auteur']; // Corrected variable name
        $date_art = date("Y-m-d");
        $category = $_POST['update_category']; // Corrected variable name

        $result = $articleController->updateArticle($id, $title, $content, $author, $date_art, $category);

        if ($result !== false) {
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        }
    }
}

$articles = $articleController->listArticles();

if (isset($_GET['search_inp'])) {
    $keyword = trim($_GET['search_inp']);
    $search_by = trim($_GET['sl_search_type']);

    // Vérifier quel bouton a été cliqué
    if (isset($_GET['search_btn'])) {
        // Bouton de recherche cliqué
        if (str_replace(' ', '', $keyword) == '') {
            $articles = $articleController->listArticles();      
        } else {
            $articles = $articleController->searchart($search_by, $keyword);
        }
    } elseif (isset($_GET['sort_btn'])) {
        // Bouton de tri cliqué
        if (str_replace(' ', '', $keyword) == '') {
            $articles = $articleController->listArticles();
        } else {
            $articles = $articleController->searchartSorted($search_by, $keyword);
        }
    } else {
        // Ni le bouton de recherche ni le bouton de tri n'ont été cliqués
        $articles = $articleController->listArticles();
    }
} else {
    $articles = $articleController->listArticles();
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HireUp Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" href="../../../assets/css/styles.min.css" />

    <style>
        /*
        .currency-input {
            position: relative;
            display: inline-block;
        }
        
        #currencySelect {
            position: absolute;
            top: 100%;
            left: 0;
            display: none;
            min-width: 150px;
            padding: 5px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
        }
        
        #currencySelect.active {
            display: block;
        }
        */
        .logo-img {
            margin: 0 auto;
            /* Center the image horizontally */
            display: block;
            /* Ensure the link occupies full width */
            padding-top: 5%;
        }

        /* CSS for the popup form */
        .modal {
            display: none;
            /* Hide the modal by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Ensure the modal appears above other elements */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scrolling if needed */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black with opacity */
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            max-width: 80%;
            /* Set a maximum width */
        }

        /* Media query for smaller screens */
        @media only screen and (max-width: 768px) {
            .modal-content {
                max-width: 90%;
                /* Adjust maximum width for smaller screens */
            }
        }


        /* Close button style */
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

        /* Ensure the modal appears above the header */
        .app-header {
            z-index: 999;
            /* Ensure the header appears above the modal */
        }

        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }



        /* article IMAGE STYLESHEET */
        /* Style for article container */
        .article-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
        }

        /* Style for article image */
        .article-img-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Style for article container */
        .hidden-article-img-container {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
        }
    </style>

    <link rel="stylesheet" href="../../../assets/css/search_bar_style.css" />

    <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php 
        $access_level = "admin";
        $block_call_back = 'false';
        include('./../../../View/callback.php')  
        ?>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php 
            $block_call_back = 'false';
            $active_page = "articles";
            $nb_adds_for_link = 3;
            include('../../../View/back_office/dashboard_side_bar.php') 
        ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <?php include('../../../View/back_office/header_bar.php') ?>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h1>article Management</h1>
                            <hr> <br>
                            <h2>Add article</h2><br>
                            <!-- Form for adding new article -->
                            <form id="addarticleForm" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <input type="hidden" name="action" value="add">
                                <div class="mb-3">
                                    <label for="article_title" class="form-label">Titre :</label>
                                    <input type="text" class="form-control" id="article_title" name="article_title" placeholder="Enter article Title">
                                    <span id="article_title_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="Contenu" class="form-label">Contenu :</label>
                                    <input type="text" class="form-control" id="Contenu" name="Contenu" placeholder="Enter Contenu">
                                    <span id="article_Contenu_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="Auteur" class="form-label">Auteur :</label>
                                    <input type="text" class="form-control" id="Auteur" name="Auteur" placeholder="Enter Auteur">
                                    <span id="article_Auteur_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>

                                <div class="form-group mb-3">
                                    <label for="category"><i class="fas fa-tags mr-2"></i>Catégorie :</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="" disabled selected>Choisissez une catégorie</option>
                                        <option value="politique">Politique</option>
                                        <option value="informatique">Informatique</option>
                                        <option value="économie">Économie</option>
                                        <option value="santé">Santé</option>
                                        <option value="écologie">Écologie</option>
                                    </select>
                                    <span id="categorie_error" class="error-message"></span>
                                </div>


                                <button type="submit" class="btn btn-primary">Add article</button>
                            </form>
                        </div>
                    </div>


                    <!-- Popup Form for Updating article -->
                    <div id="updatearticleModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><a class="ti ti-edit" style="color: white;"></a> Update article</h2>
                            <hr><br>
                            <form id="updatearticleForm" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <div class="mb-3">
                                    <label for="update_article_id" class="form-label">article ID *</label>
                                    <input type="text" class="form-control" id="update_article_id" name="article_id" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="update_article_title" class="form-label">article Title *</label>
                                    <input type="text" class="form-control" id="update_article_title" name="article_title" placeholder="Enter article Title">
                                    <span id="update_article_title_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_Contenu" class="form-label">Contenu</label>
                                    <input type="text" class="form-control" id="update_Contenu" name="Contenu" placeholder="Enter company">
                                    <span id="update_Contenu_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_Auteur" class="form-label">Auteur</label>
                                    <input type="text" class="form-control" id="update_Auteur" name="Auteur" placeholder="Enter location">
                                    <span id="update_Auteur_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_date_art" class="form-label">Date de publication</label>
                                    <input type="date" class="form-control" id="update_date_art" name="date_art" readonly >
                                    <span id="update_date_art_error" class="text-danger"></span> <!-- Error message placeholder -->
                                </div>
                                <div class="form-group">
                                    <label for="update_category"><i class="fas fa-tags mr-2"></i>Catégorie :</label>
                                    <select class="form-select" id="update_category" name="update_category">
                                        <option value="" disabled selected>Choisissez une catégorie</option>
                                        <option value="politique">Politique</option>
                                        <option value="informatique">Informatique</option>
                                        <option value="économie">Économie</option>
                                        <option value="santé">Santé</option>
                                        <option value="écologie">Écologie</option>
                                    </select>
                                    <span id="update_category_error" class="error-message"></span>
                                </div>

                                <button type="submit" class="btn btn-primary" id="updatearticleBtn">Update article</button>
                                <button type="button" class="btn btn-secondary cancel-btn" id="cancelUpdateBtn">Cancel</button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>

            <button type="button" class="btn btn-success btn-sm me-2" id="scrollToTopBtn" style="font-size: large;"><a class="ti ti-arrow-up text-white"></a></button>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        
                        <!-- Table for displaying existing articles -->
                        <div class="table-responsive">
                                    
                                    <form action="" method="">
                                        <div class="mb-3">
                                            
                                            <div class="search-container">
                                                <div class="search-by">
                                                    <label for="search_type">Search By:</label>
                                                    <select class="form-select" id="sl_search_type" name="sl_search_type">
                                                        <option value="everything">Everything</option>
                                                        <option value="id">ID</option>
                                                        <option value="titre">title</option>
                                                        <option value="contenu">content</option>
                                                        <option value="date_art">date</option>
                                                        <option value="categories">Category</option>
                                                        <option value="auteur_id ">Auther ID</option>
                                                    </select>
                                                </div>
                                                <div class="search-input">
                                                    <label for="search_inp">Search:</label>
                                                    <input type="text" class="form-control" id="search_inp" name="search_inp" placeholder="Search">
                                                </div>

                                                <div>
                                                    <label for="search_btn"></label> <br>
                                                    <button type="submit" class="btn btn-primary" id="search_btn" name="search_btn" value="search">Search</button>
                                                    <button type="submit" class="btn btn-primary" id="sort_btn" name="sort_btn" value="sort">Sort</button>
                                                    
                                                </div>

                                            </div>

                                            <div id="search_error" style="color: red;"></div>

                                        </div>
                                    </form>
                            <!-- Table for displaying existing articles -->
                            <table class="table text-nowrap mb-0 align-middle" id="articles-table">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">ID</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Titre</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Contenu</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Auteur</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Date Article</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Catégorie </h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Actions </h6>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Les lignes seront ajoutées dynamiquement ici -->
                                    <!-- Exemple de ligne (à remplacer par des données dynamiques de la base de données) -->
                                    <?php foreach ($articles as $article) : ?>
                                        <tr>
                                            <td><?= $article['id']; ?></td>
                                            <td><?= $article['titre']; ?></td>
                                            <td><?= $article['contenu']; ?></td>
                                            <td><?= $article['auteur_id']; ?></td>
                                            <td><?= $article['date_art']; ?></td>
                                            <td><?= isset($article['categories']) ? $article['categories'] : 'N/A'; ?></td>

                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn" data-article-id="<?= $article['id']; ?>" data-article-title="<?= $article['titre']; ?>" data-article-content="<?= $article['contenu']; ?>" data-article-auteur="<?= $article['id']; ?>" data-article-date="<?= $article['date_art']; ?>" data-article-category="<?= $article['categories']  ?>">
                                                    Edit
                                                </button>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">Delete</button>
                                                </form>
                                            </td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/sidebarmenu.js"></script>
    <script src="../../../assets/js/app.min.js"></script>
    <script src="../../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../../../assets/js/finition.js"></script>

    <script src="./../finition.js"></script>
   
    <!-- pop up JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the modal
            var modal = document.getElementById("updatearticleModal");

            // Get the close button
            var span = document.getElementsByClassName("close")[0];

            // Get all edit buttons
            var editButtons = document.querySelectorAll(".edit-btn");

            // Add event listener for edit button click
            editButtons.forEach(function(button) {
                button.onclick = function() {
                    // Get article details from data attributes
                    const articleId = this.getAttribute("data-article-id");
                    const articleTitle = this.getAttribute("data-article-title");
                    const articleContent = this.getAttribute("data-article-content");
                    const articleAuteur = this.getAttribute("data-article-auteur");
                    const articleDate = this.getAttribute("data-article-date");
                    const articleCategory = this.getAttribute("data-article-category");

                    // Populate update form inputs with article details
                    document.getElementById("update_article_id").value = articleId;
                    document.getElementById("update_article_title").value = articleTitle;
                    document.getElementById("update_Contenu").value = articleContent;
                    document.getElementById("update_Auteur").value = articleAuteur;
                    document.getElementById("update_date_art").value = articleDate;
                    document.getElementById("update_category").value = articleCategory;

                    // Show the update form modal
                    modal.style.display = "block";
                    modal.style.display = "flex";
                };
            });



            // Add event listener for close button click
            span.onclick = function() {
                modal.style.display = "none";
            };

            // Add event listener for clicking outside the modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        });
    </script>

<script>
    document.getElementById("addarticleForm").addEventListener("submit", function(event) {
        // Reset previous error messages
        document.getElementById("article_title_error").textContent = ""; // Reset error message for article title
        document.getElementById("article_Contenu_error").textContent = ""; // Reset error message for Contenu
        document.getElementById("article_Auteur_error").textContent = ""; // Reset error message for Auteur
        document.getElementById("categorie_error").textContent = ""; // Reset error message for category

        // Get input values
        var articleTitle = document.getElementById("article_title").value.trim();
        var Contenu = document.getElementById("Contenu").value.trim();
        var Auteur = document.getElementById("Auteur").value.trim();
        var category = document.getElementById("category").value.trim();

        // Variable to store the common error message
        var errorMessage = "";

        // Validate article title (characters only)
        if (!/^[a-zA-Z\s]+$/.test(articleTitle)) {
            errorMessage = "article title must contain only characters."; // Set common error message
            displayError("article_title_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (articleTitle === "") {
            errorMessage = "article title is required."; // Set common error message
            displayError("article_title_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (Contenu === "") {
            errorMessage = "Contenu is required."; // Set common error message
            displayError("article_Contenu_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (Auteur === "") {
            errorMessage = "Auteur is required."; // Set common error message
            displayError("article_Auteur_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (category === "") {
            errorMessage = "category is required."; // Set common error message
            displayError("categorie_error", errorMessage, true); // Display error message
        }

        // Prevent form submission if there's an error message
        if (errorMessage !== "") {
            event.preventDefault();
        }
    });

    // Listen for input event on article title field
    document.getElementById("article_title").addEventListener("input", function(event) {
        var articleTitle = this.value.trim(); // Get value of article title field
        var articleTitleError = document.getElementById("article_title_error"); // Get error message element

        // Validate article title format (characters only)
        if (articleTitle === "") {
            displayError("article_title_error", "Title is required.", true); // Display error message for empty article title
        } else if (/^[a-zA-Z\s]+$/.test(articleTitle)) {
            displayError("article_title_error", "Valid article Title", false); // Display valid message for article title
        } else {
            displayError("article_title_error", "article title must contain only characters.", true); // Display error message for invalid article title
        }
    });

    // Listen for input event on Contenu field
    document.getElementById("Contenu").addEventListener("input", function(event) {
        var Contenu = this.value.trim(); // Get value of Contenu field
        var ContenuError = document.getElementById("article_Contenu_error"); // Get error message element

        // Validate if Contenu is empty
        if (Contenu === "") {
            displayError("article_Contenu_error", "Contenu is required.", true); // Display error message for empty Contenu
        } else {
            displayError("article_Contenu_error", "Valid Contenu", false); // Display valid message for Contenu
        }
    });

    // Listen for input event on Auteur field
    document.getElementById("Auteur").addEventListener("input", function(event) {
        var Auteur = this.value.trim(); // Get value of Auteur field
        var AuteurError = document.getElementById("article_Auteur_error"); // Get error message element

        // Validate if Auteur is empty
        if (Auteur === "") {
            displayError("article_Auteur_error", "Auteur is required.", true); // Display error message for empty Auteur
        } else {
            displayError("article_Auteur_error", "Valid Auteur", false); // Display valid message for Auteur
        }
    });

    // Listen for input event on category field
    document.getElementById("category").addEventListener("change", function(event) {
        var category = this.value.trim(); // Get value of category field

        // Validate if category is selected
        if (category === "") {
            displayError("categorie_error", "Category is required.", true); // Display error message for empty category
        } else {
            displayError("categorie_error", "Valid category", false); // Display valid message for category
        }
    });

    // Function to display error message
    function displayError(elementId, errorMessage, isError) {
        var errorElement = document.getElementById(elementId);
        errorElement.textContent = errorMessage;
        errorElement.classList.toggle("text-danger", isError);
        errorElement.classList.toggle("text-success", !isError);
    }
</script>


<script>
    document.getElementById("updatearticleForm").addEventListener("submit", function(event) {
        // Reset previous error messages
        document.getElementById("update_article_title_error").textContent = ""; // Reset error message for article title
        document.getElementById("update_Contenu_error").textContent = ""; // Reset error message for Contenu
        document.getElementById("update_Auteur_error").textContent = ""; // Reset error message for Auteur
        document.getElementById("update_category_error").textContent = ""; // Reset error message for category

        // Get input values
        var articleTitle = document.getElementById("update_article_title").value.trim();
        var Contenu = document.getElementById("update_Contenu").value.trim();
        var auteur = document.getElementById("update_Auteur").value.trim();
        var category = document.getElementById("update_category").value.trim();

        // Variable to store the common error message
        var errorMessage = "";

        // Validate article title (characters only)
        if (!/^[a-zA-Z\s]+$/.test(articleTitle)) {
            errorMessage = "article title must contain only characters."; // Set common error message
            displayError("update_article_title_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (articleTitle === "") {
            errorMessage = "article title is required."; // Set common error message
            displayError("update_article_title_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (Contenu === "") {
            errorMessage = "Contenu is required."; // Set common error message
            displayError("update_Contenu_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (auteur === "") {
            errorMessage = "auteur is required."; // Set common error message
            displayError("update_Auteur_error", errorMessage, true); // Display error message
        }

        // Check if any input field is empty
        if (category === "") {
            errorMessage = "category is required."; // Set common error message
            displayError("update_category_error", errorMessage, true); // Display error message
        }

        // Prevent form submission if there's an error message
        if (errorMessage !== "") {
            event.preventDefault();
        }
    });

    // Listen for input event on article title field
    document.getElementById("update_article_title").addEventListener("input", function(event) {
        var articleTitle = this.value.trim(); // Get value of article title field
        var articleTitleError = document.getElementById("update_article_title_error"); // Get error message element

        // Validate article title format (characters only)
        if (articleTitle === "") {
            displayError("update_article_title_error", "Title is required.", true); // Display error message for empty article title
        } else if (/^[a-zA-Z\s]+$/.test(articleTitle)) {
            displayError("update_article_title_error", "Valid article Title", false); // Display valid message for article title
        } else {
            displayError("update_article_title_error", "article title must contain only characters.", true); // Display error message for invalid article title
        }
    });

    // Listen for input event on Contenu field
    document.getElementById("update_Contenu").addEventListener("input", function(event) {
        var Contenu = this.value.trim(); // Get value of Contenu field
        var ContenuError = document.getElementById("update_Contenu_error"); // Get error message element

        // Validate if Contenu is empty
        if (Contenu === "") {
            displayError("update_Contenu_error", "Contenu is required.", true); // Display error message for empty Contenu
        } else {
            displayError("update_Contenu_error", "Valid Contenu", false); // Display valid message for Contenu
        }
    });

    // Listen for input event on Auteur field
    document.getElementById("update_Auteur").addEventListener("input", function(event) {
        var Auteur = this.value.trim(); // Get value of Auteur field
        var AuteurError = document.getElementById("update_Auteur_error"); // Get error message element

        // Validate if Auteur is empty
        if (Auteur === "") {
            displayError("update_Auteur_error", "Auteur is required.", true); // Display error message for empty Auteur
        } else {
            displayError("update_Auteur_error", "Valid Auteur", false); // Display valid message for Auteur
        }
    });

    // Listen for input event on category field
    document.getElementById("update_category").addEventListener("change", function(event) {
        var category = this.value.trim(); // Get value of category field

        // Validate if category is selected
        if (category === "") {
            displayError("update_category_error", "Category is required.", true); // Display error message for empty category
        } else {
            displayError("update_category_error", "Valid category", false); // Display valid message for category
        }
    });

    // Function to display error message
    function displayError(elementId, errorMessage, isError) {
        var errorElement = document.getElementById(elementId);
        errorElement.textContent = errorMessage;
        errorElement.classList.toggle("text-danger", isError);
        errorElement.classList.toggle("text-success", !isError);
    }
</script>

<!-- voice recognation -->
<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

<?php

    // fill forms if data exists
    // search by
    if (isset($_GET['sl_search_type'])) {
        // Retrieve and sanitize the error message
        $search_by = htmlspecialchars($_GET['sl_search_type']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('sl_search_type').value = '$search_by';</script>");
      }
    
      // search inp
      if (isset($_GET['search_inp'])) {
        // Retrieve and sanitize the error message
        $keyword = htmlspecialchars($_GET['search_inp']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('search_inp').value = '$keyword';</script>");
      }

?>


</body>

</html>