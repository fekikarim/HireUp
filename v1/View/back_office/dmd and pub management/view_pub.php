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
include '../../../Controller/pub_con.php';
include '../../../Controller/dmd_con.php';
include '../../../Model/pub.php';

// Création d'une instance du contrôleur des événements
$pubb = new pubCon("pubs");
$dmdd = new dmdCon("demande");


if (isset($_GET['id'])) {
    $current_id = $_GET['id'];
    $pub = $pubb->getpub($current_id);

    $pubs = $pubb->listpub();
    $id_dmd_options = $pubb->generateDmdOptionsSelected($pub['id_demande']);

    $dmd = $dmdd->getdmd($pub['id_demande']);
    $accepted = $dmd['status'];
}


?>


<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        
        <?php 
            $block_call_back = 'false';
            $active_page = "ads";
            $nb_adds_for_link = 3;
            include('../../../View/back_office/dashboard_side_bar.php') 
        ?>

        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <!--  login place -->
                 <?php include('../../../View/back_office/header_bar.php') ?>  
            
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">publicite Management</h5>
                            
                            <?php 
                                if ($accepted == "accepted") {
                            ?>
                            <div class="alert alert-success" role="alert">
                                This request has been accepted!
                            </div>
                            <?php 
                                } else if ($accepted == "pending") {
                            ?>
                            <div class="alert alert-warning" role="alert">
                                This request is still pending!
                            </div>
                            <?php 
                                }  else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                This request has been declined!
                            </div>
                            <?php 
                                } 
                            ?>
                            <!-- Form for adding new job -->
                            <form action="update_pup_action.php?id=<?= $current_id ?>" method="post">
                                <!-- job Information -->
                                <div class="mb-3">
                                    <label for="titre" class="form-label">titre</label>
                                    <input type="text" class="form-control" value="<?= $pub['titre']; ?>" id="titre" name="titre"
                                        placeholder="Enter the titre" readonly required>
                                    <div id="titre_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="contenu" class="form-label">contenu</label>
                                    <input type="text" class="form-control" value="<?= $pub['contenu']; ?>" id="contenu" name="contenu"
                                        placeholder="Enter the contenu" readonly required>
                                    <div id="contenu_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="pub_link" class="form-label">link</label>
                                    <input type="text" class="form-control" value="<?= $pub['pub_link']; ?>" id="pub_link" name="pub_link"
                                        placeholder="Enter the link" readonly required>
                                    <div id="pub_link_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="dat" class="form-label">date</label>
                                    <input type="date" class="form-control" value="<?= $pub['dat']; ?>" id="dat" name="dat" placeholder="Enter the date"
                                    readonly required>
                                    <div id="objectif_error" style="color: red;"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="id_dmd" class="form-label">ID Demande</label>
                                    <select disabled class="form-control" id="id_dmd" name="id_dmd" readonly required>
                                        <option value="" selected disabled>choisir le type</option>
                                        <?php echo $id_dmd_options; ?>
                                    </select>
                                    <div id="id_dmd_error" style="color: red;"></div>
                                </div>
                                
                                


                                <!-- Submit Button -->
                                <!-- <button type="submit" class="btn btn-primary" onclick="return verif_pub_manaet_inputs()">Update pub</button> -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary mt-3 text-center" onclick="window.location.href='pub_management.php'">go back</button>   
                                    <button type="button" class="btn btn-danger mt-3 text-center" onclick="window.location.href = './delete_pub.php?id=<?= $pub['idpub']; ?>';">Delete</button>
                                    <button type="button" class="btn btn-warning mt-3 text-center" onclick="window.location.href = './view_dmd.php?id=<?php echo $pub['id_demande']; ?>';">View Request</button>
                                </div>

                                <div class="mb-3" id="error_global" style="color: red; text-align: center;"></div>
                                <div class="mb-3" id="success_global" style="color: green; text-align: center;"></div>

                            </form>
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
    <script src="../../../View/back_office/ads managment/pub_management.js"></script>

    <script src="./../finition.js"></script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>


</body>

</html>
