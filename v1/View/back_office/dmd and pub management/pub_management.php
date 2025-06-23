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

        #chart .apexcharts-bar-series rect {
            cursor: pointer !important;
        }

    </style>

    <!-- chart  -->
    <script src="./../../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>

    <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php

include '../../../Controller/pub_con.php';
include '../../../Controller/dmd_con.php';
include '../../../Model/pub.php';

// Création d'une instance du contrôleur des événements
$pubb = new pubCon("pub");
$dmdd = new dmdCon("dmd");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

if (isset($_GET['search_inp'])) {
    $keyword = trim($_GET['search_inp']);
    $search_by = trim($_GET['sl_search_type']);

    // Vérifier quel bouton a été cliqué
    if (isset($_GET['search_btn'])) {
        // Bouton de recherche cliqué
        if (str_replace(' ', '', $keyword) == '') {
            $pubs = $pubb->listpub();
        } else {
            $pubs = $pubb->searchpub($search_by, $keyword);
        }
    } elseif (isset($_GET['sort_btn'])) {
        // Bouton de tri cliqué
        if (str_replace(' ', '', $keyword) == '') {
            $pubs = $pubb->listpub();
        } else {
            $pubs = $pubb->searchpubSorted($search_by, $keyword);
        }
    } else {
        // Ni le bouton de recherche ni le bouton de tri n'ont été cliqués
        $pubs = $pubb->listpub();
    }
} else {
    $pubs = $pubb->listpub();
}

$id_dmd_options = $pubb->generateDmdOptions();


// chart

$stats = $pubb->listAcceptedPubs();
$data = $stats->fetchAll(PDO::FETCH_ASSOC);

// Initialize an empty array to store the formatted data
$formattedData = [];

// Iterate through the fetched data and format it
foreach ($data as $row) {
    // Assuming 'name' and 'value' keys exist in your data rows
    $formattedData[] = [
        'name' => $row['titre'], // Assuming 'name' is the column name for ad names
        'value' => $row['clicked_times'], // Assuming 'value' is the column name for click counts
        'id' => $row['idpub'] // Assuming 'value' is the column name for click counts
    ];
}

// Convert the formatted data to JSON
$data_json = json_encode($formattedData);


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
        include ('../../../View/back_office/dashboard_side_bar.php')
            ?>

        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">

                    <!--  login place -->
                    <?php include ('../../../View/back_office/header_bar.php') ?>

                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid">

                <!-- <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">publicite Management</h5>
                            
                            <form action="./add_pub.php" method="post">
                                
                                <div class="mb-3">
                                    <label for="titre" class="form-label">titre</label>
                                    <input type="text" class="form-control" id="titre" name="titre"
                                        placeholder="Enter the titre" required>
                                    <div id="titre_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="contenu" class="form-label">contenu</label>
                                    <input type="text" class="form-control" id="contenu" name="contenu"
                                        placeholder="Enter the contenu" required>
                                    <div id="contenu_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="dat" class="form-label">date</label>
                                    <input type="date" class="form-control" id="dat" name="dat" placeholder="Enter the date"
                                        required>
                                    <div id="dat_error" style="color: red;"></div>
                                </div>
                                
                               
                                <div class="mb-3">
                                    <label for="id_dmd" class="form-label">ID Demande</label>
                                    <select class="form-control" id="id_dmd" name="id_dmd" required>
                                        <option value="" selected disabled>choisir le type</option>
                                        <?php //echo $id_dmd_options; ?>
                                    </select>
                                    <div id="id_dmd_error" style="color: red;"></div>
                                </div>


                                
                                <button type="submit" class="btn btn-primary" onclick="return verif()">Add pub</button>

                                <div class="mb-3" id="error_global" style="color: red; text-align: center;"></div>
                                <div class="mb-3" id="success_global" style="color: green; text-align: center;"></div>

                            </form>
                        </div>
                    </div>
                </div> -->

                <!--  Page chart -->

                <div class="row">
                    <div class="col-xlg-8 d-flex align-items-strech">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                    <div class="mb-3 mb-sm-0">
                                        <h5 class="card-title fw-semibold">Ads Clicks Statistics</h5>
                                    </div>
                                </div>


                                <div id="chart" style="max-width: 650px; margin: 35px auto;">

                                </div>



                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- End page chart -->

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <!-- Table for displaying existing jobs -->
                            <h5 class="card-title fw-semibold mb-4">Ads Management</h5>
                            <div class="table-responsive">

                                <div>
                                    <form action="" method="">
                                        <div class="mb-3">

                                            <div class="search-container">
                                                <div class="search-by">
                                                    <label for="search_type">Search By:</label>
                                                    <select class="form-select" id="sl_search_type"
                                                        name="sl_search_type">
                                                        <option value="everything">Everything</option>
                                                        <option value="idpub">ID</option>
                                                        <option value="titre">title</option>
                                                        <option value="contenu">content</option>
                                                        <option value="dat">date</option>
                                                        <option value="id_demande">Demand ID</option>
                                                    </select>
                                                </div>
                                                <div class="search-input">
                                                    <label for="search_inp">Search:</label>
                                                    <input type="text" class="form-control" id="search_inp"
                                                        name="search_inp" placeholder="Search">
                                                </div>

                                                <div>
                                                    <label for="search_btn"></label> <br>
                                                    <button type="submit" class="btn btn-primary" id="search_btn"
                                                        name="search_btn" value="search">Search</button>
                                                    <button type="submit" class="btn btn-primary" id="sort_btn"
                                                        name="sort_btn" value="sort">Sort</button>

                                                </div>

                                            </div>

                                            <div id="search_error" style="color: red;"></div>

                                        </div>
                                    </form>

                                    <table class="table text-nowrap mb-0 align-middle">
                                        <thead class="text-dark fs-4">
                                            <tr>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">ID</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">titre</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">contenu</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">link</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">date</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">ID Demande</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Demande Status</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">actions</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- job rows will be dynamically added here -->
                                            <!-- Example row (replace with dynamic data from database) -->
                                            <?php
                                            foreach ($pubs as $pub) {
                                                ?>
                                                <tr>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['idpub']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['titre']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['contenu']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['pub_link']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['dat']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $pub['id_demande']; ?></h6>
                                                    </td>
                                                    <?php
                                                    $dmd = $dmdd->getdmd($pub['id_demande']);
                                                    ?>
                                                    <td class="border-bottom-0">
                                                        <?php if ($dmd['status'] == 'accepted') { ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check"
                                                                    style="color: green;"></i></h6>
                                                        <?php } else if ($dmd['status'] == 'pending') { ?>
                                                                <h6 class="fw-semibold mb-0"><i class="fa-solid fa-clock"
                                                                        style="color: orange;"></i></h6>
                                                        <?php } else { ?>
                                                                <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-xmark"
                                                                        style="color: red;"></i></h6>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="border-bottom-0">

                                                        <!-- <button type="button" class="btn btn-primary btn-sm me-2" onclick="window.location.href = './update_pub.php?id=<?= $pub['idpub']; ?>';">update</button> -->
                                                        <button type="button" class="btn btn-warning btn-sm me-2"
                                                            onclick="window.location.href = './view_pub.php?id=<?= $pub['idpub']; ?>';">view</button>
                                                        <button type="button" class="btn btn-primary btn-sm me-2"
                                                            onclick="window.location.href = './view_dmd.php?id=<?php echo $pub['id_demande']; ?>';">view
                                                            request</button>
                                                        <!-- <button type="button" class="btn btn-danger btn-sm me-2" onclick="window.location.href = './delete_pub.php?id=<?= $pub['idpub']; ?>';">Delete</button> -->
                                                        <button type="button" class="btn btn-danger btn-sm me-2"
                                                            onclick="window.location.href = './decline.php?id=<?= $pub['id_demande']; ?>';">decline</button>


                                                    </td>
                                                </tr>

                                                <?php
                                            }
                                            ?>
                                            <!-- Add more rows dynamically here -->
                                        </tbody>
                                    </table>
                                </div>
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
        <script src="../../../View/back_office/ads managment/pubjs.js"></script>

        <script src="./../finition.js"></script>

        <!-- chart js -->
        <script src="ads_chart_bar.js" data-json="<?php echo htmlspecialchars($data_json); ?>"></script>

        <!-- voice recognation -->
	    <script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

        <!-- php error check -->
        <?php
        // Check if there's an error message in the URL
        // user name
        if (isset($_GET['error_user_name'])) {
            // Retrieve and sanitize the error message
            $error = htmlspecialchars($_GET['error_user_name']);
            // Inject the error message into the div element
            echo ("<script>document.getElementById('user_name_error').innerText = '$error';</script>");
        }

        // email
        if (isset($_GET['error_email'])) {
            // Retrieve and sanitize the error message
            $error = htmlspecialchars($_GET['error_email']);
            // Inject the error message into the div element
            echo "<script>document.getElementById('user_email_error').innerText = '$error';</script>";
        }

        // fill forms if data exists
        // user name
        if (isset($_GET['user_name'])) {
            // Retrieve and sanitize the error message
            $user_name = htmlspecialchars($_GET['user_name']);
            // Inject the error message into the div element
            echo ("<script>document.getElementById('user_name').value = '$user_name';</script>");
        }

        // email
        if (isset($_GET['email'])) {
            // Retrieve and sanitize the error message
            $email = htmlspecialchars($_GET['email']);
            // Inject the error message into the div element
            echo ("<script>document.getElementById('email').value = '$email';</script>");
        }

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

</body>

</html>