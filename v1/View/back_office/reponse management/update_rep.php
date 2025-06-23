<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HireUp Dashboard</title>
    <link rel="shortcut icon" type="../../../assets/image/png" href="../../../assets/images/logos/HireUp_icon.ico" />
    <link rel="stylesheet" href="../../../assets/css/styles.min.css" />

    <link rel="stylesheet" href="../../../assets/css/search_bar_style.css" />

    <style>
        .logo-img {
            margin: 0 auto;
            /* Center the image horizontally */
            display: block;
            /* Ensure the link occupies full width */
            padding-top: 5%;
        }
    </style>

        <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php

include_once __DIR__ . '/../../../Controller/reponse_con.php';
include_once __DIR__ . '/../../../Model/reponse.php';

// Création d'une instance du contrôleur des événements
$repC = new repCon("reponses");

// Création d'une instance de la classe Event
$reponse = null;

if (isset($_GET['id'])){
    $current_id = $_GET['id'];
}


if (
    isset($_POST["contenu"]) &&
    isset($_POST["date_reponse"]) &&
    isset($_POST["id_user"]) &&
    isset($_POST["id_reclamation"]) 
) {
    if (
        !empty($_POST['contenu']) &&
        !empty($_POST["date_reponse"]) &&
        !empty($_POST["id_user"]) &&
        !empty($_POST["id_reclamation"]) 
        
    ) {
       
        $reponse = new Reponse(
            $current_id,
            $_POST['contenu'],
            $_POST['date_reponse'],
            $_POST['id_user'],
            $_POST['id_reclamation'],
            
        );

        $repC->updateRep($reponse, $current_id);
        $success_message = "Answer Updated successfully!";
        header('Location: ../../../View\back_office\reponse management\reps_management.php?success_global=' . urlencode($success_message));
    } else {
        $error = "Missing information";
    }
} elseif (isset($_GET['id'])) {
    $current_id = $_GET['id'];
    $reponse = $repC->getRep($current_id);
    $id_recs_options = $repC->generateRecOptionsSelected($reponse['id_reclamation']);
}





?>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        
        <?php 
            $block_call_back = 'false';
            $active_page = "reponses";
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
                            <h5 class="card-title fw-semibold mb-4">Answers Management</h5>
                            <!-- Form for adding new job -->
                            <form action="./update.php?id=<?php echo $reponse['id']; ?>" method="post">
                                <!-- job Information -->
                                <div class="mb-3">
                                    <label for="contenu" class="form-label">Content</label>
                                    <textarea class="form-control" id="contenu" name="contenu" placeholder="Enter the content" required><?= $reponse['contenu']; ?></textarea>
                                    <div id="content_error" style="color: red;"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_reponse" class="form-label">Answer Date</label>
                                    <input type="date" class="form-control" id="date_reponse" value="<?= $reponse['date_reponse']; ?>" name="date_reponse" placeholder="Enter the answer date"
                                        required readonly>
                                    <div id="date_reponse_error" style="color: red;"></div>
                                </div>

                                

                                <div class="mb-3">
                                    <label for="id_user" class="form-label">User ID</label>
                                    <input type="text" class="form-control" value="<?= $reponse['id_user']; ?>" id="id_user" name="id_user" placeholder="Enter the user id"
                                        required readonly>
                                    <div id="id_user_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="id_reclamation" class="form-label"><b>ID reclamation</b></label>
                                        <select class="form-select" id="id_reclamation" name="id_reclamation" required>
                                            <option value="" selected disabled>choisir the id reclamation</option>
                                            <?php echo $id_recs_options; ?>
                                        </select>
                                    <div id="id_reclamation_error" style="color: red;"></div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary mb-3" onclick="return verif_reponse_managemet_inputs()">Update Answer</button>

                                <div class="mb-3" id="error_global" style="color: red; text-align: center;"></div>
                                <div class="mb-3" id="success_global" style="color: green; text-align: center;"></div>

                            </form>
                        </div>
                    </div>
                </div>

                
            </div>



        </div>
    </div>

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>
    <script src="../../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/sidebarmenu.js"></script>
    <script src="../../../assets/js/app.min.js"></script>
    <script src="../../../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../../../View/back_office/reclamations managment/recs_management_js.js"></script>

    <!-- php error check -->
  <?php

    //global error
    if (isset($_GET['error_global'])) {
        // Retrieve and sanitize the error message
        $error = htmlspecialchars($_GET['error_global']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('error_global').innerText = '$error';</script>");
    }
  
    //global success
    if (isset($_GET['success_global'])) {
        // Retrieve and sanitize the error message
        $error = htmlspecialchars($_GET['success_global']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('success_global').innerText = '$error';</script>");
    }

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
  
      // role
      if (isset($_GET['sl_role'])) {
        // Retrieve and sanitize the error message
        $role = htmlspecialchars($_GET['sl_role']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('sl_role').value = '$role';</script>");
      }

      // verified
      if (isset($_GET['sl_verified'])) {
        // Retrieve and sanitize the error message
        $verified = htmlspecialchars($_GET['sl_verified']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('sl_verified').value = '$verified';</script>");
      }

      // banned
      if (isset($_GET['sl_banned'])) {
        // Retrieve and sanitize the error message
        $banned = htmlspecialchars($_GET['sl_banned']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('sl_banned').value = '$banned';</script>");
      }

  ?>

<script src="./../finition.js"></script>

<!-- voice recognation -->
<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

</body>

</html>