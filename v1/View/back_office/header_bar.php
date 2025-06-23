<link rel="stylesheet" href="../../../assets/css/styles.min.css" />


<?php

include_once __DIR__ . '/../../Controller/user_con.php';
include_once __DIR__ . '/../../Controller/profileController.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

$profileC = new ProfileC();

//MARK: unset the user id if the user is not in the database
if(isset($_SESSION['user id'])) {
  $user_id = htmlspecialchars($_SESSION['user id']);

  if ($userC->userExistsInDataBase($user_id) == false){
    unset($_SESSION['user id']);
  }
}


if(isset($_SESSION['user id'])) {
  $user_id = htmlspecialchars($_SESSION['user id']);

  $user_banned = $userC->get_user_banned_by_id($user_id);

  $user_profile_id = $userC->get_user_profile_id_by_id($user_id);

  $user_inofs = $userC->getUser($user_id);

  $profile = $profileC->getProfileById($user_profile_id);

  if ($user_banned == "false"){

?>


          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a title="#" class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="#">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

              <li class="nav-item dropdown">
                <a title="<?php echo $user_inofs['user_name'] ; ?>" class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>" alt="Profile Picture" class="rounded-circle" width="45" height="45" />
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="../../front_office/profiles_management/profile.php?profile_id=<?php echo $user_profile_id ?>" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">My Account</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a class="d-flex align-items-center gap-2 dropdown-item" href="#">
                      <i class="ti ti-settings fs-6"></i>
                      <p class="mb-0 fs-3">Settings</p>
                    </a>
                    <a href="">
                      <label class="d-flex align-items-center gap-2 dropdown-item" for="darkModeToggle">
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="darkModeToggle">
                        </div>
                        <p class="mb-0 fs-3">Appearance</p>
                      </label>
                    </a>
                    <a href="../../../View/front_office/Sign In & Sign Up/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <?php
            }
            else{
          ?>

          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a title="<?php echo $user_inofs['user_name'] ; ?>" class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="../../../View/front_office/Sign In & Sign Up/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <?php
          }
          ?>

<?php
}
else{

?>

          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
              <button type="button" class="btn btn-outline-primary m-1" onclick="window.location.href='../../../View/front_office/Sign In & Sign Up/authentication-login.php';">Sign in</button>
              <button type="button" class="btn btn-primary m-1" onclick="window.location.href='../../../View/front_office/Sign In & Sign Up/authentication-register.php';">Sign up</button>
              </li>
            </ul>
          </div>

<?php
}
?>

<script src="./profiles_management/js/finition.js"></script>

