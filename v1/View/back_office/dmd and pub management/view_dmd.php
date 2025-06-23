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
include '../../../Controller/dmd_con.php';
include '../../../Model/dmd.php';

$dmdd = new dmdCon("dmds");
$dmd = null;

if (isset($_GET['id'])) {
    $current_id = $_GET['id'];
    $dmd = $dmdd->getdmd($current_id);
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
            $active_page = "dmd";
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
                            <h5 class="card-title fw-semibold mb-4">demande Management</h5>

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
                            <form action="update_action.php?id=<?php echo $current_id; ?>" method="post" enctype="multipart/form-data">
                                <!-- job Information -->
                                <div class="mb-3">
                                    <label for="titre" class="form-label">titre</label>
                                    <input type="text" class="form-control" value="<?= $dmd['titre']; ?>" id="titre" name="titre"
                                        placeholder="Enter the titre" required readonly>
                                    <div id="titre_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="contenu" class="form-label">contenu</label>
                                    <input type="text" class="form-control" value="<?= $dmd['contenu']; ?>" id="contenu" name="contenu"
                                        placeholder="Enter the contenu" required readonly>
                                    <div id="contenu_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="objectif" class="form-label">objectif</label>
                                    <input type="text" class="form-control" value="<?= $dmd['objectif']; ?>" id="objectif" name="objectif" placeholder="Enter the objectif"
                                        required readonly>
                                    <div id="objectif_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="dure" class="form-label">dure</label>
                                    <input type="text" class="form-control" value="<?= $dmd['dure']; ?>" id="dure" name="dure" placeholder="Enter the dure"
                                        required readonly>
                                    <div id="dure_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="budget" class="form-label">budget</label>
                                    <input type="text" class="form-control" value="<?= $dmd['budget']; ?>" id="budget" name="budget" placeholder="Enter the budget"
                                        required readonly>
                                    <div id="budget_error" style="color: red;"></div>
                                </div>

                                <!-- Add input field for publication photo -->
                                <div class="mb-3">
                                    <label for="publication photo" class="form-label">Image</label>
                                    <!-- <input type="file" class="form-control" id="image_publication" name="image_publication" onchange="handlePhotoChange(event)" accept="image/*"> -->
                                </div>

                            <!-- publication picture container -->
                            <div class="publication-picture-container" id="publication_pic_display">
                                <!-- Output the profile photo with appropriate MIME type -->
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($dmd['image']); ?>" alt="Publication Photo" class="img-fluid">
                            </div>

                                <!-- Hidden publication photo container -->
                            <div class="hidden-publication-pic-container" id="hiddenPublicationPhotoContainer" style="display: none;">
                                <img src="#" alt="Hidden Publication Photo" class="hidden-publication-image" id="hiddenPublicationPhoto">
                            </div>

                            <div id="image_publicationError" style="color: red;"></div>
                                


                                <!-- Submit Button -->
                                <!-- <button type="submit" class="btn btn-primary" onclick="return verif_pub_manaet_inputs()">Update demande</button> -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary mt-3 text-center" onclick="window.location.href='dmd_management.php'">go back</button>

                                    <?php if ( ($dmd['status'] == 'declined') || ($dmd['status'] == 'pending') ){ ?>
                                        <button type="button" class="btn btn-success mt-3 text-center" onclick="window.location.href = './accepter.php?id=<?= $dmd['iddemande']; ?>';">accept</button>
                                    <?php } if ( ($dmd['status'] == 'accepted') || ($dmd['status'] == 'pending') ) {?>
                                        <button type="button" class="btn btn-danger mt-3 text-center" onclick="window.location.href = './decline.php?id=<?= $dmd['iddemande']; ?>';">decline</button>
                                    <?php }?>
                                
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
    <script src="../../../View/back_office/ads managment/dmd_management.js"></script>
    <script src="../../../View/back_office/ads managment/dmd_management.js"></script>

    <script src="./../finition.js"></script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

</body>

</html>
