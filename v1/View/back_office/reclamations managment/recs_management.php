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

include_once __DIR__ . '/../../../Controller/reclamation_con.php';
include_once __DIR__ . '/../../../Model/reclamation.php';

// Création d'une instance du contrôleur des événements
$recC = new recCon("reclamations");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

if (isset($_GET['search_inp'])) {
    $keyword = trim($_GET['search_inp']);
    $search_by = trim($_GET['sl_search_type']);
    $status = trim($_GET['sl_status']);

    // Vérifier quel bouton a été cliqué
    if (isset($_GET['search_btn'])) {
        // Bouton de recherche cliqué
        if (str_replace(' ', '', $keyword) == '') {
            if ( ($status == "none") ){
                $recs = $recC->listRect();
            }
            else{
                $recs = $recC->searchRec($search_by, $keyword, $status);
            }
        } else {
            $recs = $recC->searchRec($search_by, $keyword, $status);
        }
    } elseif (isset($_GET['sort_btn'])) {
        // Bouton de tri cliqué
        if (str_replace(' ', '', $keyword) == '') {
            $recs = $recC->listRect();
        } else {
            $recs = $recC->searchRecSorted($search_by, $keyword, $status);
        }
    } else {
        // Ni le bouton de recherche ni le bouton de tri n'ont été cliqués
        $recs = $recC->listRect();
    }
} else {
    $recs = $recC->listRect();
}


?>


<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        
        <?php 
            $block_call_back = 'false';
            $active_page = "reclamations";
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
                    <div class="card">
                        <div class="card-body">
                            <!-- Table for displaying existing jobs -->
                            <div class="table-responsive">

                                    <form action="" method="">
                                        <div class="mb-3">
                                            
                                            <div class="search-container">
                                                <div class="search-by">
                                                    <label for="search_type">Search By:</label>
                                                    <select class="form-select" id="sl_search_type" name="sl_search_type">
                                                        <option value="everything">Everything</option>
                                                        <option value="id">ID</option>
                                                        <option value="id_user">Profile ID</option>
                                                        <option value="statut">Status</option>
                                                        <option value="description">Description</option>
                                                        <option value="sujet">Subject</option>
                                                    </select>
                                                </div>
                                                <div class="search-input">
                                                    <label for="search_inp">Search:</label>
                                                    <input type="text" class="form-control" id="search_inp" name="search_inp" placeholder="Search">
                                                </div>

                                                <div class="search-status">
                                                    <label for="status">Status:</label>
                                                    <select class="form-select" id="sl_status" name="sl_status">
                                                        <option value="none">None</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="solved">Solved</option>
                                                    </select>
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

                                    
                                <table id="resultTable" class="table text-nowrap mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0" id="sortdate" class="sort" onclick="sortTable(0)">
                                                <h6 class="fw-semibold mb-0" >ID</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Subject</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Description</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Creation Date</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Status</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">User ID</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Actions</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- job rows will be dynamically added here -->
                                        <!-- Example row (replace with dynamic data from database) -->
                                        <?php
                                           if(empty($recsearch)) { foreach ($recs as $rec) {
                                        ?>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0"><?= $rec['id']; ?></h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0"><?= $rec['sujet']; ?></h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0"><?= $rec['description']; ?></h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0"><?= $rec['date_creation']; ?></h6>
                                            </td>
                                            <td class="border-bottom-0">
                                                <?php if ($rec['statut'] == "pending"): ?>
                                                    <h6 class="fw-semibold mb-0"><i class="fa-solid fa-clock" style="color: orange;"></i></h6>

                                                <?php else: ?>
                                                    <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check" style="color: green;"></i></h6>
                                                <?php endif; ?>
                                            </td>
                                            <td class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0"><?= $rec['id_user']; ?></h6>
                                            </td>
                                            
                                            <td class="border-bottom-0">
                                                <button type="button" class="btn btn-primary btn-sm me-2" onclick="window.location.href = './update_rec.php?id=<?= $rec['id']; ?>';">Update</button>
                                                <button type="button" class="btn btn-danger btn-sm me-2" onclick="window.location.href = './delete_rec.php?id=<?= $rec['id']; ?>';">Delete</button>
                                                <button type="button" class="btn btn-success btn-sm me-2" onclick="window.location.href = '../reponse management/rep_view_and_answer.php?id=<?php echo $rec['id']; ?>&id_user=<?php echo $rec['id_user']; ?>';">answer</button>
                                                <button type="button" class="btn btn-danger btn-sm me-2" onclick="window.location.href = './generate_pdf.php?id=<?= $rec['id']; ?>';">PDF</button>

                                            </td>
                                        </tr>

                                        <?php
                                            }
                                        }
                                           
                                            else {foreach ($recs as $rec) {
                                         ?>
                                         <tr>
                                             <td class="border-bottom-0">
                                                 <h6 class="fw-semibold mb-0"><?= $rec['id']; ?></h6>
                                             </td>
                                             <td class="border-bottom-0">
                                                 <h6 class="fw-semibold mb-0"><?= $rec['sujet']; ?></h6>
                                             </td>
                                             <td class="border-bottom-0">
                                                 <h6 class="fw-semibold mb-0"><?= $rec['description']; ?></h6>
                                             </td>
                                             <td class="border-bottom-0">
                                                 <h6 class="fw-semibold mb-0"><?= $rec['date_creation']; ?></h6>
                                             </td>
                                             <td class="border-bottom-0">
                                                 <?php if ($rec['statut'] == "pending request"): ?>
                                                     <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-xmark" style="color: red;"></i></h6>
                                                
                                                 <?php else: ?>
                                                     <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check" style="color: green;"></i></h6>
                                                 <?php endif; ?>
                                             </td>
                                             <td class="border-bottom-0">
                                                 <h6 class="fw-semibold mb-0"><?= $rec['id_user']; ?></h6>
                                             </td>
                                             
                                             <td class="border-bottom-0">
                                                 <button type="button" class="btn btn-primary btn-sm me-2" onclick="window.location.href = './update_rec.php?id=<?= $rec['id']; ?>';">Update</button>
                                                 <button type="button" class="btn btn-danger btn-sm me-2" onclick="window.location.href = './delete_rec.php?id=<?= $rec['id']; ?>';">Delete</button>
                                                 <button type="button" class="btn btn-success btn-sm me-2" onclick="window.location.href = '../reponse management/reps_management.php?id=<?= $rec['id']; ?>';">answer</button>
                                                 <button type="button" class="btn btn-danger btn-sm me-2" onclick="window.location.href = './generate_pdf.php?id=<?= $rec['id']; ?>';">PDF</button>
 
                                             </td>
                                         </tr>
                                         <?php
                                         
                                        }
                                    }

                                        ?>
                                        <!-- Add more rows dynamically here -->
                                    </tbody>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
    </table>
                               
    <script>var originalRows; // Pour stocker les lignes originales du tableau

function sortTable(column, ascending) {
    var table = document.getElementById("resultTable").getElementsByTagName('tbody')[0];
    var rows = Array.from(table.rows);

    rows.sort(function(a, b) {
        var valueA = parseInt(a.cells[column].textContent);
        var valueB = parseInt(b.cells[column].textContent);
        
        return ascending ? valueA - valueB : valueB - valueA;
    });

    for (var i = 0; i < rows.length; i++) {
        table.appendChild(rows[i]);
    }
}

document.getElementById("sortAscButton").addEventListener("click", function() {
    sortTable(0, true); // Tri ascendant par la colonne 'note' (index 2)
});
document.getElementById("resetButton").addEventListener("click", function() {
    var table = document.getElementById("resultTable").getElementsByTagName('tbody')[0];
    table.innerHTML = ""; // Efface le contenu du tbody
    originalRows.forEach(function(row) {
        table.appendChild(row);
    });
});
document.addEventListener("DOMContentLoaded", function() {
    var table = document.getElementById("resultTable").getElementsByTagName('tbody')[0];
    originalRows = Array.from(table.rows);
});</script>
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
  
      // status
      if (isset($_GET['sl_status'])) {
        // Retrieve and sanitize the error message
        $status = htmlspecialchars($_GET['sl_status']);
        // Inject the error message into the div element
        echo ("<script>document.getElementById('sl_status').value = '$status';</script>");
      }

  ?>

<script src="./../finition.js"></script>

<!-- voice recognation -->
<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

</body>

</html>