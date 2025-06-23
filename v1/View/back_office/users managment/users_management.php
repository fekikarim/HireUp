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

    <!-- chart  -->
    <script src="./../../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>

    <!-- signin buttons icons -->
    <link rel="stylesheet" href="social_style.css" />

    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>

    <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<!-- chart  -->


<!-- /chart  -->

<?php

include '../../../Controller/user_con.php';
include '../../../Model/user.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// charts
include '../../../Controller/stats_con.php';

// Création d'une instance du contrôleur des événements
$statsC = new StatsCon("stats");

$stats = $statsC->listStats();
$data = $stats->fetchAll(PDO::FETCH_ASSOC);

//$data_json = json_encode(array_column($data, 'accounts_created'));
$data_json = json_encode($data);

//=======================================================================================

if (isset($_GET['search_inp'])) {
    $clickedBtn = $_GET['search_btn'];
    if ($clickedBtn == "search") {
        $keyword = trim($_GET['search_inp']);
        $search_by = trim($_GET['sl_search_type']);
        $role = trim($_GET['sl_role']);
        $verified = trim($_GET['sl_verified']);
        $banned = trim($_GET['sl_banned']);
        $need_pass_chn = trim($_GET['sl_pass_chn']);


        // Récupération de la liste des événements
        if (str_replace(' ', '', $keyword) == '') {
            if (($role == "none") && ($verified == "none") && ($banned == "none") && ($need_pass_chn == "none")) {
                $users = $userC->listUsers();
            } else {
                $users = $userC->searchUser($search_by, $keyword, $role, $verified, $banned, $need_pass_chn);
            }
        } else {
            $users = $userC->searchUser($search_by, $keyword, $role, $verified, $banned, $need_pass_chn);
        }
    } elseif ($clickedBtn == "sort") {
        $keyword = trim($_GET['search_inp']);
        $search_by = trim($_GET['sl_search_type']);
        $role = trim($_GET['sl_role']);
        $verified = trim($_GET['sl_verified']);
        $banned = trim($_GET['sl_banned']);
        $need_pass_chn = trim($_GET['sl_pass_chn']);


        // Récupération de la liste des événements
        if (str_replace(' ', '', $keyword) == '') {
            if (($role == "none") && ($verified == "none") && ($banned == "none")) {
                $users = $userC->sortUser($search_by);
            } else {
                $users = $userC->searchUserSorted($search_by, $keyword, $role, $verified, $banned, $need_pass_chn);
            }
        } else {
            $users = $userC->searchUserSorted($search_by, $keyword, $role, $verified, $banned, $need_pass_chn);
        }
    } else {
        $users = $userC->listUsers();
    }
} else {
    $users = $userC->listUsers();
}

?>

<body>

    <?php
    $access_level = "admin";
    $block_call_back = 'false';
    include ('./../../../View/callback.php')
        ?>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->

        <?php
        $block_call_back = 'false';
        $active_page = "user";
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

                <!--  Stats Start -->
                <div class="container-fluid">
                    <!--  Row 1 -->
                    <div class="row">
                        <div class="col-xlg-8 d-flex align-items-strech">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                        <div class="mb-3 mb-sm-0">
                                            <h5 class="card-title fw-semibold">Account Creation Trends</h5>
                                        </div>
                                        <!-- <div>
                                <select title="#" class="form-select">
                                <option value="1">March 2023</option>
                                <option value="2">April 2023</option>
                                <option value="3">May 2023</option>
                                <option value="4">June 2023</option>
                                </select>
                            </div> -->
                                    </div>


                                    <div id="chart" style="max-width: 650px; margin: 35px auto;">

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  Stats End -->

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Users Management</h5>
                            <!-- Form for adding new job -->
                            <form action="./add_user.php" method="post">
                                <!-- job Information -->
                                <div class="mb-3">
                                    <label for="user_name" class="form-label">User Name</label>
                                    <input type="text" class="form-control" id="user_name" name="user_name"
                                        placeholder="Enter the user-name" required>
                                    <div id="user_name_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter the email" required>
                                    <div id="user_email_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3 password-container">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter the password" required>

                                    <span class="toggle-password" onclick="togglePasswordVisibility()">
                                        <i class="fa-solid fa-eye-slash" id="eye-icon"></i>
                                    </span>

                                    <div id="user_password_error" style="color: red;"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="" selected disabled>Select a role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                        <!-- Add more options as needed -->
                                    </select>
                                    <div id="roleError" style="color: red;"></div>
                                </div>


                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary"
                                    onclick="return verif_users_managemet_inputs()">Add User</button>

                                <div class="mb-3" id="error_global" style="color: red; text-align: center;"></div>
                                <div class="mb-3" id="success_global" style="color: green; text-align: center;"></div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <!-- Table for displaying existing jobs -->
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
                                                        <option value="id">ID</option>
                                                        <option value="user_name">Username</option>
                                                        <option value="email">Email</option>
                                                        <option value="date">Date</option>
                                                        <option value="account_type">Account Type</option>
                                                    </select>
                                                </div>
                                                <div class="search-input">
                                                    <label for="search_inp">Search:</label>
                                                    <input type="text" class="form-control" id="search_inp"
                                                        name="search_inp" placeholder="Search">
                                                </div>
                                                <div class="search-role">
                                                    <label for="role">Role:</label>
                                                    <select class="form-select" id="sl_role" name="sl_role">
                                                        <option value="none">None</option>
                                                        <option value="user">User</option>
                                                        <option value="admin">Admin</option>
                                                    </select>
                                                </div>
                                                <div class="search-verified">
                                                    <label for="verified">Verified:</label>
                                                    <select class="form-select" id="sl_verified" name="sl_verified">
                                                        <option value="none">None</option>
                                                        <option value="true">Verified</option>
                                                        <option value="false">Unverified</option>
                                                    </select>
                                                </div>
                                                <div class="search-banned">
                                                    <label for="banned">Authorization:</label>
                                                    <select class="form-select" id="sl_banned" name="sl_banned">
                                                        <option value="none">None</option>
                                                        <option value="true">Banned</option>
                                                        <option value="false">Unbanned</option>
                                                    </select>
                                                </div>

                                                <div class="search-need-pass-change">
                                                    <label for="pass_chn">Need Password Change:</label>
                                                    <select class="form-select" id="sl_pass_chn" name="sl_pass_chn">
                                                        <option value="none">None</option>
                                                        <option value="true">Needs</option>
                                                        <option value="false">Doesn't Need</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="search_btn"></label> <br>
                                                    <button type="submit" class="btn btn-primary" id="search_btn"
                                                        name="search_btn" value="search">Search</button>
                                                    <button type="submit" class="btn btn-primary" id="search_btn"
                                                        name="search_btn" value="sort">Sort</button>
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
                                                    <h6 class="fw-semibold mb-0">User Name</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Email</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Password</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Date</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Role</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Verified</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Authorized</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Password set</h6>
                                                </th>
                                                <th class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">Account Type</h6>
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
                                            foreach ($users as $user) {
                                                ?>
                                                <tr>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['id']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['user_name']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['email']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['password']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['date']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['role']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <?php if ($user['verified'] == "true"): ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check"
                                                                    style="color: green;"></i></h6>
                                                        <?php else: ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-xmark"
                                                                    style="color: red;"></i></h6>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <?php if ($user['banned'] == "false"): ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check"
                                                                    style="color: green;"></i></h6>
                                                        <?php else: ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-xmark"
                                                                    style="color: red;"></i></h6>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <?php if ($user['need_password_change'] == "false"): ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-check"
                                                                    style="color: green;"></i></h6>
                                                        <?php else: ?>
                                                            <h6 class="fw-semibold mb-0"><i class="fa-solid fa-circle-xmark"
                                                                    style="color: red;"></i></h6>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <h6 class="fw-semibold mb-0"><?= $user['account_type']; ?></h6>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <?php if ($user['role'] == "admin"): ?>
                                                            <button type="button" class="btn btn-primary btn-sm me-2"
                                                                style="display: inline;"
                                                                onclick="window.location.href = './set_role_to_user.php?id=<?= $user['id']; ?>';">Make
                                                                User</button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-primary btn-sm me-2"
                                                                style="display: inline;"
                                                                onclick="window.location.href = './set_role_to_admin.php?id=<?= $user['id']; ?>';">Make
                                                                Admin</button>
                                                        <?php endif; ?>

                                                        <?php if ($user['banned'] == "false"): ?>
                                                            <button type="button" class="btn btn-warning btn-sm me-2"
                                                                style="display: inline;"
                                                                onclick="window.location.href = './ban_user.php?id=<?= $user['id']; ?>';">Ban</button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-success btn-sm me-2"
                                                                style="display: inline;"
                                                                onclick="window.location.href = './unban_user.php?id=<?= $user['id']; ?>';">Unban</button>
                                                        <?php endif; ?>

                                                        <button type="button" class="btn btn-danger btn-sm me-2"
                                                            onclick="window.location.href = './delete_user.php?id=<?= $user['id']; ?>';">Delete</button>
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
        <script src="../../../View/back_office/users managment/users_management.js"></script>

        <!-- chart  -->
        <script src="user_charts.js" data-json="<?php echo htmlspecialchars($data_json); ?>"></script>

        <script src="./../finition.js"></script>

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

        // need password change
        if (isset($_GET['sl_pass_chn'])) {
            // Retrieve and sanitize the error message
            $need_pass_chn = htmlspecialchars($_GET['sl_pass_chn']);
            // Inject the error message into the div element
            echo ("<script>document.getElementById('sl_pass_chn').value = '$need_pass_chn';</script>");
        }

        ?>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation_dashboard.js"></script>

</body>

</html>