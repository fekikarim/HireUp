<?php

require_once __DIR__ . '/../../../Controller/categoryC.php';

// Include the controller file
$catController = new categoryController();

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add") {
        // Add new category
        $name = $_POST["name_category"];
        $description = $_POST["description_category"];
        $category_id = $catController->generateCategoryId(7);

        // Only echo the result if the category creation is successful
        $result = $catController->createCategory($category_id, $name, $description);
        if ($result !== "New category created successfully") {
            $_SESSION["error_message"] = $result; // Store error message in session variable
        } else {
            // Redirect using GET to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        }
    } elseif ($_POST["action"] == "update") {
        // Update existing category
        $category_id = $_POST["category_id"];
        $name = $_POST["category_name"];
        $description = $_POST["category_description"];

        // Only echo the result if the category update is successful
        $result = $catController->updateCategory($category_id, $name, $description);
        if ($result !== "Category updated successfully") {
            $_SESSION["update_error_message"] = $result; // Store error message in session variable
        } else {
            // Redirect using GET to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        }
    } elseif ($_POST["action"] == "delete" && isset($_POST["category_id"])) {
        // Delete category
        $category_id = $_POST["category_id"];
        $deleted = $catController->deletecategory($category_id);
        if ($deleted === "Job deleted successfully") {
            // Redirect using GET to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        } else {
            $_SESSION["error_message"] = "Error deleting category."; // Store error message in session variable
        }
    }
}

// Display error message if it exists
if (isset($_SESSION["error_message"])) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var errorMessageSpan = document.createElement("span");
                errorMessageSpan.innerText = "' . $_SESSION["error_message"] . '";
                errorMessageSpan.classList.add("text-danger", "text-center"); // Add text-center class
                errorMessageSpan.style.display = "block"; // Ensure the span is displayed as a block element
                errorMessageSpan.style.marginTop = "10px"; // Add top margin for spacing
                document.getElementById("addCategoryForm").appendChild(errorMessageSpan);
                
                // Remove the error message after 1 seconds
                setTimeout(function() {
                    errorMessageSpan.remove();
                }, 2000);
            });
          </script>';

    unset($_SESSION["error_message"]); // Clear session variable after displaying error message
}

// Display error message if it exists for update form
if (isset($_SESSION["update_error_message"])) {
    echo '<script>
    // Remove the alert after 5 seconds
    setTimeout(function() {
        alert("' . $_SESSION["update_error_message"] . '");
    }, 1000);
  </script>';

    unset($_SESSION["update_error_message"]); // Clear session variable after displaying error message
}

// Fetch all jobs
$categorys = $catController->getCategory();

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HireUp Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="./../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" href="./../../../assets/css/styles.min.css" />

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
    </style>

    <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

    <?php
    $block_call_back = 'false';
    $access_level = "admin";
    include ('./../../../View/callback.php')
        ?>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php
        $active_page = "jobs";
        $nb_adds_for_link = 3;
        include ('../../../View/back_office/dashboard_side_bar.php')
            ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">

            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <?php include ('../../../View/back_office/header_bar.php') ?>
                </nav>
            </header>            
            <!--  Header End -->

            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h1>Category Management</h1>
                            <hr> <br>
                            <h2>Add Category</h2><br>
                            <!-- Form for adding new job -->
                            <form id="addCategoryForm" method="post">
                                <input type="hidden" name="action" value="add">
                                <div class="mb-3">
                                    <label for="name_category" class="form-label">name_category *</label>
                                    <input type="text" class="form-control" id="name_category" name="name_category"
                                        placeholder="Enter  name category">
                                    <span id="name_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="description_category" class="form-label">description *</label>
                                    <input type="text" class="form-control" id="description_category"
                                        name="description_category" placeholder="Enter description category">
                                    <span id="description_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>

                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </form>
                        </div>
                    </div>


                    <!-- Popup Form for Updating Job -->
                    <div id="updateCategoryModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><a class="ti ti-edit" style="color: white;"></a> Update category</h2>
                            <hr><br>
                            <form id="updateCategoryForm" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="mb-3">
                                    <label for="update_category_id" class="form-label">Job ID *</label>
                                    <input type="text" class="form-control" id="update_category_id" name="category_id"
                                        readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="update_name_category" class="form-label">name_category *</label>
                                    <input type="text" class="form-control" id="update_name_category"
                                        name="category_name" placeholder="Enter  name category">
                                    <span id="update_name_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->
                                </div>
                                <div class="mb-3">
                                    <label for="update_description_category" class="form-label">Description *</label>
                                    <input type="text" class="form-control" id="update_description_category"
                                        name="category_description" placeholder="Enter description">
                                    <span id="update_description_category_error" class="text-danger"></span>
                                    <!-- Error message placeholder -->

                                </div>


                                <button type="submit" class="btn btn-primary" id="updateJobBtn">Update category</button>
                                <button type="button" class="btn btn-secondary cancel-btn"
                                    id="cancelUpdateBtn">Cancel</button>
                            </form>
                        </div>
                    </div>



                </div>
            </div>

            <button type="button" class="btn btn-success btn-sm me-2" id="scrollToTopBtn" style="font-size: large;"><a
                    class="ti ti-arrow-up text-white"></a></button>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 ">
                                <a class="btn btn-primary" href="./job_management.php"><i
                                        class="ti ti-pin text-white"></i> Job Management</a>
                                <a class="btn btn-success mx-3" href="./skills.php"><i
                                        class="ti ti-pin text-white"></i> Skills Management</a>
                            </div>

                            <div class="col-md-6">
                                <form class="d-flex">
                                    <input class="form-control me-2" type="search" placeholder="Search Category"
                                        aria-label="Search" id="searchInput">
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Table for displaying existing jobs -->
                            <table class="table text-nowrap mb-0 align-middle" id="jobs-table">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">ID</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">name</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">description</h6>
                                        </th>


                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Actions</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categorys as $cat): ?>
                                        <tr>
                                            <td><?= $cat['id_category'] ?></td>
                                            <td><?= $cat['name_category'] ?></td>
                                            <td><?= $cat['description_category'] ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn"
                                                    data-category-id="<?= $cat['id_category'] ?>"
                                                    data-category-name="<?= $cat['name_category'] ?>"
                                                    data-description="<?= $cat['description_category'] ?>">Edit</button>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="category_id"
                                                        value="<?= $cat['id_category'] ?>">
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                                </form>
                                            </td>
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
    <script src="./script.js"></script>

    <script src="./../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../assets/js/sidebarmenu.js"></script>
    <script src="./../../../assets/js/app.min.js"></script>
    <script src="./../../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="./../finition.js"></script>

    <script>
        // Get the input field
        var input = document.getElementById("searchInput");

        // Add an event listener to detect input changes
        input.addEventListener("input", function () {
            var filter, table, tr, td, i, txtValue;
            filter = input.value.toUpperCase();
            table = document.getElementById("jobs-table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change the index if the column for job title is different
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
    <!-- pop up JS -->
    <script>
        // Get the modal
        var modal = document.getElementById("updateCategoryModal");

        // Get the button that opens the modal
        var editButtons = document.querySelectorAll(".edit-btn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // JavaScript to handle edit button click event
        document.addEventListener("DOMContentLoaded", function () {
            editButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    // Get category details from data attributes
                    const id = this.getAttribute("data-category-id");
                    const name = this.getAttribute("data-category-name");
                    const description = this.getAttribute("data-description");
                    // Populate update form inputs with category details
                    console.log(name);
                    document.getElementById("update_category_id").value = id;
                    document.getElementById("update_name_category").value = name;
                    document.getElementById("update_description_category").value = description;

                    // Show the update form modal
                    document.getElementById("updateCategoryForm").style.display = "block";
                });
            });
        });





        // When the user clicks on the edit button, open the modal
        editButtons.forEach(function (button) {
            button.onclick = function () {
                modal.style.display = "block";
                modal.style.display = "flex";
                // Populate form fields with job details here using JavaScript
            };
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };

        // When the user clicks on cancel button, close the modal
        document.querySelector(".cancel-btn").onclick = function () {
            modal.style.display = "none";
            document.getElementById("updateCategoryForm").reset();
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
    <!-- add JS -->

    <script>
        document.getElementById("addCategoryForm").addEventListener("submit", function (event) {
            // Reset previous error messages
            document.getElementById("description_category_error").textContent = ""; // Reset error message for description
            document.getElementById("name_category_error").textContent = ""; // Reset error message for name_category

            // Get input values
            var description = document.getElementById("description_category").value.trim();
            var name = document.getElementById("name_category").value.trim();

            // Variable to store the common error message
            var errorMessage = "";

            // Validate name_category (characters only)
            if (!/^[a-zA-Z\s]+$/.test(name)) {
                errorMessage = "Name category must contain only characters."; // Set common error message
                displayError("name_category_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (name === "") {
                errorMessage = "Name category is required."; // Set common error message
                displayError("name_category_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (description === "") {
                errorMessage = "Description is required."; // Set common error message
                displayError("description_category_error", errorMessage, true); // Display error message
            }

            // If there are no errors, submit the form
            if (errorMessage === "") {
                this.submit(); // Submit the form
            }

            event.preventDefault(); // Prevent default form submission
        });

        // Listen for input event on name_category field
        document.getElementById("name_category").addEventListener("input", function (event) {
            var name = this.value.trim(); // Get value of name_category field

            // Validate name_category format (characters only)
            if (name === "") {
                displayError("name_category_error", "Name is required.", true); // Display error message for empty name
            } else if (/^[a-zA-Z\s]+$/.test(name)) {
                displayError("name_category_error", "Valid name category", false); // Display valid message for name category
            } else {
                displayError("name_category_error", "Name category must contain only characters.", true); // Display error message for invalid name category
            }
        });

        // Listen for input event on description_category field
        document.getElementById("description_category").addEventListener("input", function (event) {
            var description = this.value.trim(); // Get value of description_category field

            // Validate if description is empty
            if (description === "") {
                displayError("description_category_error", "Description is required.", true); // Display error message for empty description
            } else {
                displayError("description_category_error", "Valid description", false); // Display valid message for description
            }
        });

        // Function to display error message
        function displayError(elementId, errorMessage, isError) {
            var errorElement = document.getElementById(elementId);
            errorElement.textContent = errorMessage;
            errorElement.classList.toggle("text-danger", isError);
            errorElement.classList.toggle("text-success", !isError);
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Function to check if all input fields are populated
            function checkInputFields() {
                var inputs = document.querySelectorAll("#addCategoryForm input");
                var allPopulated = true;
                inputs.forEach(function (input) {
                    if (input.value.trim() === "") {
                        allPopulated = false;
                    }
                });
                return allPopulated;
            }

            // Function to enable/disable submit button based on input fields
            function toggleSubmitButton() {
                var submitButton = document.querySelector("#addCategoryForm button[type='submit']");
                submitButton.disabled = !checkInputFields();
            }

            // Listen for input event on each input field
            var inputs = document.querySelectorAll("#addCategoryForm input");
            inputs.forEach(function (input) {
                input.addEventListener("input", function () {
                    toggleSubmitButton();
                });
            });

            // Initial call to toggleSubmitButton to set initial state
            toggleSubmitButton();
        });
    </script>


    <!-- update JS -->
    <script>
        document.getElementById("updateCategoryForm").addEventListener("submit", function (event) {
            // Reset previous error messages
            document.getElementById("update_description_category_error").textContent = ""; // Reset error message for description
            document.getElementById("update_name_category_error").textContent = ""; // Reset error message for name_category

            // Get input values
            var description = document.getElementById("update_description_category").value.trim();
            var name = document.getElementById("update_name_category").value.trim();

            // Variable to store the common error message
            var errorMessage = "";

            // Validate name_category (characters only)
            if (!/^[a-zA-Z\s]+$/.test(name)) {
                errorMessage = "Name category must contain only characters."; // Set common error message
                displayError("update_name_category_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (name === "") {
                errorMessage = "Name category is required."; // Set common error message
                displayError("update_name_category_error", errorMessage, true); // Display error message
            }

            // Check if any input field is empty
            if (description === "") {
                errorMessage = "Description is required."; // Set common error message
                displayError("update_description_category_error", errorMessage, true); // Display error message
            }

            // If there are no errors, submit the form
            if (errorMessage === "") {
                this.submit(); // Submit the form
            }

            event.preventDefault(); // Prevent default form submission
        });

        // Listen for input event on name_category field
        document.getElementById("update_name_category").addEventListener("input", function (event) {
            var name = this.value.trim(); // Get value of name_category field

            // Validate name_category format (characters only)
            if (name === "") {
                displayError("update_name_category_error", "Name is required.", true); // Display error message for empty name
            } else if (/^[a-zA-Z\s]+$/.test(name)) {
                displayError("update_name_category_error", "Valid name category", false); // Display valid message for name category
            } else {
                displayError("update_name_category_error", "Name category must contain only characters.", true); // Display error message for invalid name category
            }
        });

        // Listen for input event on description_category field
        document.getElementById("update_description_category").addEventListener("input", function (event) {
            var description = this.value.trim(); // Get value of description_category field

            // Validate if description is empty
            if (description === "") {
                displayError("update_description_category_error", "Description is required.", true); // Display error message for empty description
            } else {
                displayError("update_description_category_error", "Valid description", false); // Display valid message for description
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


</body>

</html>