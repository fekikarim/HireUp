<?php
require_once __DIR__ . '/../../../../Controller/subscriptionControls.php';

include_once __DIR__ . '/../../../../Controller/user_con.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");




// Check if the request method is GET and if id_emp is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['subscription_id'])) {
    // Retrieve the subscriptions information from the database
    $id = $_GET['subscription_id'];

    // Create an instance of the controller
    $subsController = new SubscriptionControls();

    // Get the subscriptions details by ID
    $subscriptions = $subsController->getSubscriptionById($id);


    // Check if profile is set and not null
    
        // subscriptions details are available, proceed with displaying the form
    
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>Update Profile</title>
            <link rel="shortcut icon" type="image/png" href="./../../../../assets/images/logos/HireUp_icon.ico" />
            <link rel="stylesheet" href="./../../../../assets/css/styles.min.css" />
            <link rel="stylesheet" href="./../css/subs_inputC.css" />

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />        

            <!-- voice recognation -->
            <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

        </head>

        <body>

        <?php 
        $block_call_back = 'false';
      $access_level = "admin";
      include('./../../../../View/callback.php')  
    ?>

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
                                    <h2 class="card-title fw-semibold mb-4" style="font-size: xx-large;"><a class="ti ti-edit" style="color: #212529;"></a>Update <?php echo $subscriptions['plan_name'] ?> Subscription</h2>
                                    <hr><br>
                                    <!-- Form for adding new subscription -->
                                    <form id="subsForm" action="./update_Subs.php" method="POST">
                                        <!-- Login Information -->
                                        <div class="mb-3">
                                            <label for="subscription_id" class="form-label">Subscription ID *</label>
                                            <input type="text" class="form-control" id="subscription_id" name="subscription_id" value="<?php echo isset($subscriptions['subscription_id']) ? $subscriptions['subscription_id'] : ''; ?>" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="plan_name" class="form-label">Plan Name *</label>
                                            <input type="text" class="form-control" onkeyup="validateName()" id="plan_name" name="plan_name" placeholder="Enter Plan Name" value="<?php echo isset($subscriptions['plan_name']) ? $subscriptions['plan_name'] : ''; ?>" />
                                            <span id="name_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration *</label>
                                            <input type="text" class="form-control" onkeyup="validateDuration()" id="duration" name="duration" placeholder="Enter Duration" value="<?php echo isset($subscriptions['duration']) ? $subscriptions['duration'] : ''; ?>" />
                                            <span id="dur_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control" onkeyup="validatePrice()" id="price" name="price" placeholder="Enter Price" value="<?php echo isset($subscriptions['price']) ? $subscriptions['price'] : ''; ?>" />
                                            <span id="price_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="card" class="form-label">Card Type</label>
                                            <select class="form-select" onkeyup="validateCard()" id="card" name="card">
                                                <option value="" selected disabled>Select Card Type</option>
                                                <option value="basic" <?php echo isset($subscriptions['card']) && strtolower($subscriptions['card']) === 'basic' ? 'selected' : ''; ?>>Basic</option>
                                                <option value="advanced" <?php echo isset($subscriptions['card']) && strtolower($subscriptions['card']) === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                                                <option value="premium" <?php echo isset($subscriptions['card']) && strtolower($subscriptions['card']) === 'premium' ? 'selected' : ''; ?>>Premium</option>
                                                <option value="limited" <?php echo isset($subscriptions['card']) && strtolower($subscriptions['card']) === 'limited' ? 'selected' : ''; ?>>Limited</option>
                                                <option value="sale" <?php echo isset($subscriptions['card']) && strtolower($subscriptions['card']) === 'sale' ? 'selected' : ''; ?>>Sale</option>
                                            </select>
                                            <span id="card_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="subscription_status" class="form-label">Status</label>
                                            <select class="form-select" onkeyup="validateStatus()" id="subscription_status" name="subscription_status">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="active" <?php echo isset($subscriptions['subscription_status']) && strtolower($subscriptions['subscription_status']) === 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo isset($subscriptions['subscription_status']) && strtolower($subscriptions['subscription_status']) === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="pending" <?php echo isset($subscriptions['subscription_status']) && strtolower($subscriptions['subscription_status']) === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="expired" <?php echo isset($subscriptions['subscription_status']) && strtolower($subscriptions['subscription_status']) === 'expired' ? 'selected' : ''; ?>>Expired</option>
                                                <option value="suspended" <?php echo isset($subscriptions['subscription_status']) && strtolower($subscriptions['subscription_status']) === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                            </select>
                                            <span id="status_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" onkeyup="validateDescription()" id="description" name="description" rows="3"><?php echo isset($subscriptions['description']) ? $subscriptions['description'] : ''; ?></textarea>
                                            <span id="desc_error"></span>
                                        </div><br>


                                        <!-- Submit Button -->
                                        <button type="submit" onclick="return validateForm()" id="submit_button" class="btn btn-primary rounded-5" style="font-size: x-large;">
                                            <span class="ti ti-plus text-white"></span>
                                        </button>
                                        <span id="submit_error"></span>
                                    </form>
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

            <!-- voice recognation -->
	        <script type="text/javascript" src="./../../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

        </body>

        </html>

<?php

} else {
    // Invalid request, handle this case
    echo "Invalid request";
}
?>