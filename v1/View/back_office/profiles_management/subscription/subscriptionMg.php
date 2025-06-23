<?php

require_once __DIR__ . '/../../../../Controller/subscriptionControls.php';
require_once __DIR__ . '/../../../../Controller/subsFeaturesControls.php';


$subsController = new SubscriptionControls();

$feat = new SubsFeaturesControls();

//test
$test = '924714';

$Subscriptions = $subsController->getAllSubscriptions();
$features = $feat->getAllFeatures();

if(isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
  
    $user_profile_id = $userC->get_user_profile_id_by_id($user_id);
  
  }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HireUp Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="./../../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" id="stylesheet" href="./../../../../assets/css/styles.min.css" />

    <link rel="stylesheet" href="./../css/subs_inputC.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />

    <!-- Add the intlTelInput CSS -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <style>
        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #featuresModalBody p {
            font-size: large;
        }
    </style>

    <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body>

<?php 
$block_call_back = 'false';
      $access_level = "admin";
      include('./../../../../View/callback.php')  
    ?>

    <script>
        //toggle add subs form
        function toggleFormVisibility() {
            var form = document.getElementById('addSubsForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php 
            $active_page = "profile";
            $nb_adds_for_link = 4;
            include('../../../../View/back_office/dashboard_side_bar.php') 
        ?>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <!--  login place -->
                    <?php include('../../../../View/back_office/header_bar.php') ?>
            
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title fw-semibold mb-4" style="font-size: xx-large;">Subscription Management</h2>
                            <hr><br>

                            <!-- Button with an icon and a form below it -->
                            <button type="button" id="toggleFormButton" class="btn btn-primary mb-3" style="font-size: large;" onclick="toggleFormVisibility()">
                                <i class="ti ti-user-plus me-2"></i>Add Subscription Plan
                            </button>
                            <a class="btn btn-success mb-3 ms-3" href="./feature/featureMg.php?profile_id=<?php echo $test ?>" style="font-size: large;">
                                <i class="ti ti-user-check me-2"></i>Features Management
                            </a>
                            <br>
                            <hr>
                            <div id="addSubsForm" style="display: none;">
                                <!-- Form for adding new subscription -->
                                <form id="subsForm" action="./addSubs.php" method="POST">
                                    <!-- Login Information -->
                                    <div class="mb-3">
                                        <label for="plan_name" class="form-label">Plan Name *</label>
                                        <input type="text" onkeyup="validateName()" class="form-control" id="plan_name" name="plan_name" placeholder="Enter Plan Name"  />
                                        <span id="name_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">Duration *</label>
                                        <input type="text" onkeyup="validateDuration()" class="form-control" id="duration" name="duration" placeholder="Enter Duration"  />
                                        <span id="dur_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" onkeyup="validatePrice()" class="form-control" id="price" name="price" placeholder="Enter Price" />
                                        <span id="price_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="card" class="form-label">Card Type</label>
                                        <select class="form-select" onchange="validateCard()" id="card" name="card" >
                                            <option value="" disabled selected>Select Card Type</option>
                                            <option value="basic">Basic</option>
                                            <option value="advanced">Advanced</option>
                                            <option value="premium">Premium</option>
                                            <option value="limited">Limited</option>
                                            <option value="sale">Sale</option>
                                        </select>
                                        <span id="card_error"></span> <!-- Corrected the id to match the JavaScript function -->
                                    </div>

                                    <div class="mb-3">
                                        <label for="subscription_status" class="form-label">Status</label>
                                        <select class="form-select" onchange="validateStatus()" id="subscription_status" name="subscription_status" >
                                            <option value="" selected disabled>Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="pending">Pending</option>
                                            <option value="expired">Expired</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                        <span id="status_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" onkeyup="validateDescription()" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                                        <span id="desc_error"></span>
                                    </div><br>

                                    <!-- Submit Button -->
                                    <button onclick="return validateForm()" id="submit_button" class="btn btn-primary rounded-5" style="font-size: x-large;">
                                        <a class="ti ti-plus text-white"></a>
                                    </button>
                                    <span id="submit_error"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success btn-sm me-2" id="scrollToTopBtn" style="font-size: large;"><a class="ti ti-arrow-up text-white"></a></button>

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Search bar -->
                                    <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search Subscriptions...">
                                </div>
                                <div class="col-md-6 text-end">
                                    <!-- Filter button -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sortModal">Filter</button>
                                </div>
                            </div>
                            <hr>

                            <!-- Table for displaying existing subscriptions -->

                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Actions</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Features</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">ID</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Plan Name</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Duration</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Price</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Status</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Card Type</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Description</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="subsTableBody">
                                        <?php foreach ($Subscriptions as $Subs) : ?>
                                            <tr>
                                                <td class="border-bottom-0">
                                                    <button type="button" style="font-size: medium;" class="btn btn-primary btn-sm me-2" onclick="window.location.href='./updateSubs.php?subscription_id=<?php echo $Subs['subscription_id']; ?>'"><a class="ti ti-edit text-white"></a></button>
                                                    <button type="button" style="font-size: medium;" class="btn btn-danger btn-sm" onclick="window.location.href='./deleteSubs.php?subscription_id=<?php echo $Subs['subscription_id']; ?>'"><a class="ti ti-x text-white"></a></button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" onclick="showFeaturesModal('<?php echo $Subs['plan_name']; ?>')">
                                                        <i class="ti ti-icons text-white"></i>
                                                    </button>
                                                </td>


                                                <td><?php echo isset($Subs['subscription_id']) ? $Subs['subscription_id'] : ''; ?></td>
                                                <td><?php echo isset($Subs['plan_name']) ? $Subs['plan_name'] : ''; ?></td>
                                                <td><?php echo isset($Subs['duration']) ? $Subs['duration'] : ''; ?></td>
                                                <td><?php echo isset($Subs['price']) ? $Subs['price'] : ''; ?></td>
                                                <td><?php echo isset($Subs['subscription_status']) ? $Subs['subscription_status'] : ''; ?></td>
                                                <td><?php echo isset($Subs['card']) ? $Subs['card'] : ''; ?></td>
                                                <td><?php echo isset($Subs['description']) ? $Subs['description'] : ''; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="featuresModal" tabindex="-1" aria-labelledby="featuresModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="featuresModalLabel"></h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <h5><b>Feature Name</b></h5>
                                    </div>
                                    <div class="col">
                                        <h5><b>Description</b></h5>
                                    </div>
                                </div>
                                <div id="featuresModalBody">
                                    <!-- Features will be dynamically added here -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Filter Modal -->
                <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sortModalLabel">Filter subscriptions</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Subscription options -->
                                <div class="mb-3">
                                    <h6>Subscription</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="basic" id="subscriptionBasic">
                                        <label class="form-check-label" for="subscriptionBasic">
                                            Basic
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="advanced" id="subscriptionAdvanced">
                                        <label class="form-check-label" for="subscriptionAdvanced">
                                            Advanced
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="premium" id="subscriptionPremium">
                                        <label class="form-check-label" for="subscriptionPremium">
                                            Premium
                                        </label>
                                    </div>
                                </div>
                                <!-- Authentication Type options -->
                                <div class="mb-3">
                                    <h6>Authentication Type</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="login" id="authTypeLogin">
                                        <label class="form-check-label" for="authTypeLogin">
                                            Login Credentials
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="social" id="authTypeSocial">
                                        <label class="form-check-label" for="authTypeSocial">
                                            Social Login
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="multi" id="authTypeMulti">
                                        <label class="form-check-label" for="authTypeMulti">
                                            Multi-Factor Authentication (MFA)
                                        </label>
                                    </div>
                                </div>
                                <!-- Account Verification options -->
                                <div class="mb-3">
                                    <h6>Account Verification</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="verified" id="accountVerified">
                                        <label class="form-check-label" for="accountVerified">
                                            Verified
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="notverified" id="accountNotVerified">
                                        <label class="form-check-label" for="accountNotVerified">
                                            Not Verified
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./../../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./../../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../../assets/js/sidebarmenu.js"></script>
    <script src="./../../../../assets/js/app.min.js"></script>
    <script src="./../../../../assets/libs/simplebar/dist/simplebar.js"></script>
    

    <script src="./../js/finition.js"></script>
    <script src="./../js/inputC_subsAdd.js"></script>


    <script>
        // Listen for changes in the search input field
        document.getElementById('searchInput').addEventListener('input', function() {
            // Get the search query
            var query = this.value.trim();

            // Send the query to the server
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the table with the search results
                    document.getElementById('subsTableBody').innerHTML = this.responseText;
                }
            };
            xhttp.open('GET', 'searchSubs.php?query=' + query, true);
            xhttp.send();
        });


        function showFeaturesModal(planName) {
            // Set the title of the modal
            document.getElementById('featuresModalLabel').innerText = planName;

            // Get the modal body element
            var modalBody = document.getElementById('featuresModalBody');

            // Clear any previous content
            modalBody.innerHTML = '';

            // Loop through the features and add them to the modal body
            <?php foreach ($features as $feature) : ?>
                var featureName = "<?php echo $feature['feature_name']; ?>";
                var description = "<?php echo $feature['description']; ?>";
                var featureElement = document.createElement('p');
                // Apply styling to feature name
                featureElement.innerHTML = "<br><i class='ti ti-circle-check text-success' style='font-size: 26px;'></i><strong style='color: green;'>" + featureName + "</strong>: " + description;
                modalBody.appendChild(featureElement);
            <?php endforeach; ?>

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('featuresModal'));
            myModal.show();
        }
    </script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

</body>

</html>