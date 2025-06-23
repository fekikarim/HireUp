<?php

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

require_once __DIR__ . '/../../../Controller/schedualC.php';

include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

// Creating an instance of the profile controller
$profileController = new ProfileC();

$user_id = '';
$user_profile_id = '';

if (isset($_SESSION['user id'])) {

  $user_id = htmlspecialchars($_SESSION['user id']);

  // Get profile ID from the URL
  $user_profile_id = $profileController->getProfileIdByUserId($user_id);

  $user_profile = $profileController->getProfileById($user_profile_id);
}

$scheduleController = new ScheduleController();
//$schedules = $scheduleController->getAllSchedules();
$schedules = $scheduleController->getAllSchedulesWhereProfileId($user_profile_id);

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="./../../../front office assets/images/HireUp_icon.ico" />
  <title>HireUp Calendar</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
  <script src="./js/jquery-3.6.0.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <script src="./fullcalendar/lib/main.min.js"></script>
  <style>
    :root {
      --bs-success-rgb: 71, 222, 152 !important;
    }

    html,
    body {
      height: 100%;
      width: 100%;
      font-family: 'Apple Chancery', cursive;
    }

    .btn-info.text-light:hover,
    .btn-info.text-light:focus {
      background: #000;
    }

    table,
    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
      border-color: #ededed !important;
      border-style: solid;
      border-width: 1px !important;
    }

    .logo-img {
      width: 115px;
      height: 52px;
      margin: 0 auto;
      /* Center the image horizontally */
      display: block;
      /* Ensure the link occupies full width */
      padding-top: 2%;
      content: url("../profiles_management/assets/img/logos/HireUp_darkMode.png");
    }
  </style>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<body class="bg-light">

  <?php
  $block_call_back = false;
  $access_level = "else";
  include ('../../../View/callback.php');
  ?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
    <div class="container">
      <a class="navbar-brand" href="../../../index.php">
        <img class="logo-img" alt="HireUp">
      </a>

      <div class="dropdown">
        <!-- Profile Photo -->
        <a href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false"
          class="d-flex align-items-center justify-content-center mx-3" style="height: 100%;">
          <img src="data:image/jpeg;base64,<?= base64_encode($user_profile['profile_photo']) ?>" alt="Profile Photo"
            class="rounded-circle" width="50" height="50">
        </a>

        <!-- Profile Dropdown Menu -->
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <!-- Dropdown Header -->
          <h5 class="dropdown-header">Account</h5>
          <!-- Profile Link -->
          <li><a class="dropdown-item" href="./../profiles_management/profile.php">Profile</a></li>
          <?php
          if ($user_role == 'admin') {
            ?>
            <li><a class="dropdown-item text-success" href="./../../../View/back_office/main dashboard">Dashboard</a>
            </li>
            <?php
          }
          ?>

          <li><a class="dropdown-item"
              href="<?php echo $current_url . "/view/front_office/jobs management/career_explorers.php" ?>">Career
              Explorers</a>
            <!-- Divider -->
          <li>
            <hr class="dropdown-divider">
          </li>
          <!-- Try Premium -->
          <li><a class="dropdown-header"
              href="./../profiles_management/subscription/subscriptionCards.php?profile_id=<?php echo $profile['profile_id'] ?>">Try
              Premium
              for $0</a></li>
          <!-- Divider -->
          <li>
            <hr class="dropdown-divider">
          </li>
          <!-- Settings & Privacy -->
          <li><a class="dropdown-item"
              href="./../profiles_management/profile-settings-privacy.php?profile_id=<?php echo $profile['profile_id'] ?>">Settings
              &
              Privacy</a></li>
          <!-- Help Link -->
          <li><a class="dropdown-item" href="./../../../about.php">Help</a></li>
          <!-- Language Link -->
          <li><a class="dropdown-item"
              href="./../profiles_management/settings_privacy/language_settings.php">Language</a></li>
          <!-- Divider -->
          <li>
            <hr class="dropdown-divider">
          </li>
          <!-- Manage Header -->
          <h5 class="dropdown-header">Manage</h5>
          <!-- Posts & Activity Link -->
          <li><a class="dropdown-item" href="#">Posts & Activity</a></li>
          <!-- Jobs Link -->
          <li><a class="dropdown-item" href="./../jobs management/jobs_list.php">Jobs</a></li>
          <!-- Divider -->
          <li>
            <hr class="dropdown-divider">
          </li>
          <!-- Reporting Header -->
          <h5 class="dropdown-header">Report</h5>
          <li><a class="dropdown-item" href="javascript:void(0)" onclick="openPopup()">Give Feedback</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <!-- Logout Link -->
          <li><a class="dropdown-item" href="./../Sign In & Sign Up/logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5" id="page-container">
    <div class="row">
      <div class="col-md-9">
        <div id="calendar"></div>
      </div>
      <div class="col-md-3">
        <div class="cardt rounded-0 shadow">
          <div class="card-header bg-gradient bg-primary text-light">
            <h5 class="card-title">Schedule Form</h5>
          </div>
          <div class="card-body">
            <div class="container-fluid">
              <form action="save_schedule.php" method="post" id="schedule-form">
                <input type="hidden" name="id" value="">
                <div class="form-group mb-2">
                  <label for="title" class="control-label">Title</label>
                  <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                </div>
                <div class="form-group mb-2">
                  <label for="description" class="control-label">Description</label>
                  <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description"
                    required></textarea>
                </div>
                <div class="form-group mb-2">
                  <label for="start_datetime" class="control-label">Start</label>
                  <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime"
                    id="start_datetime" required>
                </div>
                <div class="form-group mb-2">
                  <label for="end_datetime" class="control-label">End</label>
                  <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime"
                    id="end_datetime" required>
                </div>
                <div class="card-footer">
                  <div class="text-center">
                    <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i
                        class="fa fa-save"></i> Save</button>
                    <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i
                        class="fa fa-reset"></i> Cancel</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Event Details Modal -->
  <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-0">
        <div class="modal-header rounded-0">
          <h5 class="modal-title">Schedule Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body rounded-0">
          <div class="container-fluid">
            <dl>
              <dt class="text-muted">Title</dt>
              <dd id="title" class="fw-bold fs-4"></dd>
              <dt class="text-muted">Description</dt>
              <dd id="description" class=""></dd>
              <dt class="text-muted">Start</dt>
              <dd id="start" class=""></dd>
              <dt class="text-muted">End</dt>
              <dd id="end" class=""></dd>
            </dl>
          </div>
        </div>
        <div class="modal-footer rounded-0">
          <div class="text-start">
            <!-- Add Join Meet button -->
            <button type="button" class="btn btn-success btn-sm rounded-0" id="join-meet" data-id="">Join Meet</button>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button>
            <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button>
            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Event Details Modal -->

  <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>


  <?php

  $sched_res = [];
  foreach ($schedules as $row) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
    $row['id_meet'] = $row['meeting_id'];
    $sched_res[$row['id']] = $row;
  }


  ?>

</body>
<script>
  var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>

<script src="./js/script.js"></script>

</html>