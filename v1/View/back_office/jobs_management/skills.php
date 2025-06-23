<?php

require_once __DIR__ . '/../../../Controller/wanted_skill_con.php';
require_once __DIR__ . '/../../../Model/wanted_skill.php';

// Include the controller file
$skillController = new WantedSkillController();

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] == "add") {
        // Add new category
        $id = $skillController->generateSkillId(7);

        $skill = new WantedSkill(
            $id,
            $_POST['category'], //category id
            $_POST['skill']
        );

        $skillController->addSkill($skill);

        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    } elseif ($_POST["action"] == "update") {

        $skill = new WantedSkill(
            $_POST["update_id"],
            $_POST['update_category'],
            $_POST['update_skill'] //category id
        );

        // Only echo the result if the category update is successful
        $skillController->updateSkill($skill, $_POST["update_id"]);

        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    } elseif ($_POST["action"] == "delete" && isset($_POST["id"])) {
        // Delete category
        $id = $_POST["id"];

        $skillController->deleteSkill($id);

        //header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}

// Fetch all jobs
$skills = $skillController->listSkills();

$id_category_options = $skillController->generateCategoryOptions();

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
                            <h1>Skills Management</h1>
                            <hr> <br>
                            <h2>Add Skill</h2><br>
                            <!-- Form for adding new skill -->
                            <form id="addSkillForm" method="post">
                                <input type="hidden" name="action" value="add">
                                <div class="mb-3">
                                    <label for="skill" class="form-label">Skill Name *</label>
                                    <input type="text" class="form-control" id="skill" name="skill"
                                        placeholder="Enter skill name">
                                    <span id="skill_error" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="category" class="form-label">Category *</label>
                                    <div class="input-group">
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="" selected disabled>Select Category</option>
                                            <?php echo $id_category_options; ?>
                                        </select>
                                    </div>
                                    <span id="job_category_error" class="text-danger"></span>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Add Skill</button>
                            </form>
                        </div>
                    </div>


                    <!-- Popup Form for Updating Skill -->
                    <div id="updateSkillModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><a class="ti ti-edit" style="color: white;"></a> Update Skill</h2>
                            <hr><br>
                            <form id="updateSkillForm" method="post">
                                <input type="hidden" name="action" value="update">
                                <div class="mb-3">
                                    <label for="update_id" class="form-label">Skill ID *</label>
                                    <input type="text" class="form-control" id="update_id" name="update_id"
                                        readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="update_skill" class="form-label">Skill Name *</label>
                                    <input type="text" class="form-control" id="update_skill" name="update_skill"
                                        placeholder="Enter skill name">
                                    <span id="update_skill_error" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="update_category" class="form-label">Category *</label>
                                    <select class="form-select" id="update_category" name="update_category">
                                        <option value="" selected disabled>Select Category</option>
                                        <?php echo $id_category_options; ?>
                                    </select>
                                    <span id="update_category_error" class="text-danger"></span>
                                </div>
                                <button type="submit" class="btn btn-primary" id="updateSkillBtn">Update Skill</button>
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
                                <a class="btn btn-success mx-3" href="./categorie.php"><i
                                        class="ti ti-pin text-white"></i> Category Management</a>
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
                                            <h6 class="fw-semibold mb-0">Actions</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">ID</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Skills</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Category</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($skills as $ski): ?>
                                        <tr>
                                            <td>
                                                <!-- Edit Button -->
                                                <button class="btn btn-primary btn-sm edit-btn"
                                                    data-skill-id="<?= $ski['id'] ?>" data-skill-name="<?= $ski['skill'] ?>"
                                                    data-category="<?= $ski['category_id'] ?>">
                                                    <a class="ti ti-edit text-white"></a>
                                                </button>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="category_id" value="<?= $ski['id'] ?>">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="window.location.href='delete_skill.php?id=<?php echo $ski['id']; ?>'"><a
                                                            class="ti ti-x text-white"></a></button>
                                                </form>
                                            </td>
                                            <td><?= $ski['id'] ?></td>
                                            <td><?= $ski['skill'] ?></td>
                                            <td><?php echo $skillController->getCategoryNameById($ski['category_id']) ?>
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



    <!-- Popup JS -->
    <script>
        // Get the modal
        var modal = document.getElementById("updateSkillModal");

        // Get the button that opens the modal
        var editButtons = document.querySelectorAll(".edit-btn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // JavaScript to handle edit button click event
        document.addEventListener("DOMContentLoaded", function () {
            editButtons.forEach(button => {
                button.addEventListener("click", function () {
                    // Get skill details from data attributes
                    const id = this.getAttribute("data-skill-id");
                    const name = this.getAttribute("data-skill-name");
                    const category = this.getAttribute("data-category");

                    // Populate update form inputs with skill details
                    populateUpdateForm(id, name, category);

                    // Show the update form modal
                    modal.style.display = "block";
                });
            });
        });

        // Function to populate the update form with skill details
        function populateUpdateForm(id, name, category) {
            document.getElementById("update_id").value = id;
            document.getElementById("update_skill").value = name;
            document.getElementById("update_category").value = category;
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };

        // When the user clicks on the cancel button, close the modal
        document.getElementById("cancelUpdateBtn").onclick = function () {
            modal.style.display = "none";
            document.getElementById("updateSkillForm").reset();
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>

    <!-- Popup JS -->
    <script>
        // Get the modal
        var modal = document.getElementById("updateSkillModal");

        // Get the buttons that open the modal
        var editButtons = document.querySelectorAll(".edit-btn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // JavaScript to handle edit button click event
        document.addEventListener("DOMContentLoaded", function () {
            editButtons.forEach(button => {
                button.addEventListener("click", function () {
                    // Get skill details from data attributes
                    const id = this.getAttribute("data-skill-id");
                    const name = this.getAttribute("data-skill-name");
                    const category = this.getAttribute("data-category");

                    // Populate update form inputs with skill details
                    populateUpdateForm(id, name, category);

                    // Show the update form modal
                    modal.style.display = "block";
                });
            });
        });

        // Function to populate the update form with skill details
        function populateUpdateForm(id, name, category) {
            document.getElementById("update_id").value = id;
            document.getElementById("update_skill").value = name;
            document.getElementById("update_category").value = category;
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        };

        // When the user clicks on the cancel button, close the modal
        document.getElementById("cancelUpdateBtn").onclick = function () {
            modal.style.display = "none";
            document.getElementById("updateSkillForm").reset();
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        document.getElementById("addSkillForm").addEventListener("submit", function (event) {
            // Reset previous error messages
            document.getElementById("skill_error").textContent = "";
            document.getElementById("job_category_error").textContent = "";

            // Get input values
            var skillName = document.getElementById("skill").value.trim();
            var category = document.getElementById("category").value.trim();

            // Variable to store the common error message
            var errorMessage = "";

            // Validate skill name (characters only)
            if (!/^[a-zA-Z\s]+$/.test(skillName)) {
                errorMessage = "Skill name must contain only characters.";
                displayError("skill_error", errorMessage, true);
            }

            // Check if any input field is empty
            if (skillName === "") {
                errorMessage = "Skill name is required.";
                displayError("skill_error", errorMessage, true);
            }

            if (category === "") {
                errorMessage = "Category is required.";
                displayError("job_category_error", errorMessage, true);
            }

            // Prevent form submission if there's an error message
            if (errorMessage !== "") {
                event.preventDefault();
            }
        });

        // Listen for input event on skill name field
        document.getElementById("skill").addEventListener("input", function (event) {
            var skillName = this.value.trim();

            // Validate skill name format (characters only)
            if (skillName === "") {
                displayError("skill_error", "Skill name is required.", true);
            } else if (/^[a-zA-Z\s]+$/.test(skillName)) {
                displayError("skill_error", "Valid Skill Name", false);
            } else {
                displayError("skill_error", "Skill name must contain only characters.", true);
            }
        });

        document.getElementById("category").addEventListener("input", function (event) {
            var category = this.value.trim();

            // Validate if category is empty
            if (category === "") {
                displayError("job_category_error", "Category is required.", true);
            } else {
                displayError("job_category_error", "Valid Category", false);
            }
        });

        document.getElementById("updateSkillForm").addEventListener("submit", function (event) {
            // Reset previous error messages
            document.getElementById("update_skill_error").textContent = "";
            document.getElementById("update_category_error").textContent = "";

            // Get input values
            var skillName = document.getElementById("update_skill").value.trim();
            var category = document.getElementById("update_category").value.trim();

            // Variable to store the common error message
            var errorMessage = "";

            // Validate skill name (characters only)
            if (!/^[a-zA-Z\s]+$/.test(skillName)) {
                errorMessage = "Skill name must contain only characters.";
                displayError("update_skill_error", errorMessage, true);
            }

            // Check if any input field is empty
            if (skillName === "") {
                errorMessage = "Skill name is required.";
                displayError("update_skill_error", errorMessage, true);
            }

            if (category === "") {
                errorMessage = "Category is required.";
                displayError("update_category_error", errorMessage, true);
            }

            // Prevent form submission if there's an error message
            if (errorMessage !== "") {
                event.preventDefault();
            }
        });

        // Listen for input event on skill name field
        document.getElementById("update_skill").addEventListener("input", function (event) {
            var skillName = this.value.trim();

            // Validate skill name format (characters only)
            if (skillName === "") {
                displayError("update_skill_error", "Skill name is required.", true);
            } else if (/^[a-zA-Z\s]+$/.test(skillName)) {
                displayError("update_skill_error", "Valid Skill Name", false);
            } else {
                displayError("update_skill_error", "Skill name must contain only characters.", true);
            }
        });

        document.getElementById("update_category").addEventListener("input", function (event) {
            var category = this.value.trim();

            // Validate if category is empty
            if (category === "") {
                displayError("update_category_error", "Category is required.", true);
            } else {
                displayError("update_category_error", "Valid Category", false);
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
                var inputs = document.querySelectorAll("#addSkillForm input, #category");
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
                var submitButton = document.querySelector("#addSkillForm button[type='submit']");
                submitButton.disabled = !checkInputFields();
            }

            // Listen for input event on each input field
            var inputs = document.querySelectorAll("#addSkillForm input, #category");
            inputs.forEach(function (input) {
                input.addEventListener("input", function () {
                    toggleSubmitButton();
                });
            });

            // Initial call to toggleSubmitButton to set initial state
            toggleSubmitButton();
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Function to check if all input fields are populated
            function checkInputFields() {
                var inputs = document.querySelectorAll("#updateSkillForm input, #update_category");
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
                var submitButton = document.querySelector("#updateSkillForm button[type='submit']");
                submitButton.disabled = !checkInputFields();
            }

            // Listen for input event on each input field
            var inputs = document.querySelectorAll("#updateSkillForm input, #update_category");
            inputs.forEach(function (input) {
                input.addEventListener("input", function () {
                    toggleSubmitButton();
                });
            });

            // Initial call to toggleSubmitButton to set initial state
            toggleSubmitButton();
        });
    </script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>


</body>

</html>