<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HireUp Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="../../../assets/images/logos/HireUp_icon.ico" />
  <link rel="stylesheet" href="../../../assets/css/styles.min.css" />

  <style>
    .logo-img {
      margin: 0 auto; /* Center the image horizontally */
      display: block; /* Ensure the link occupies full width */
    }
  </style>

  <!-- signin buttons icons -->
  <link rel="stylesheet" href="social_style.css" />

  <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>

  <!-- voice recognation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>

<?php

include '../../../Controller/user_con.php';
include '../../../Model/user.php';

// Création d'une instance du contrôleur des événements
$userC = new userCon("user");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

//MARK: important cz it checks if the user_id is set or not


?>

<body>

<?php 
$block_call_back = 'false';
$access_level = "none";
include('./../../../View/callback.php')  
?>

  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a title="#" href="./../../../index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../../../assets/images/logos/HireUp_lightMode.png" alt="" width="175" height="73">
                </a>
                <p class="text-center">Your Social Campaigns</p>
                <form method="post" action="./signup.php">
                  <div class="mb-3">
                    <label for="user_name" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" aria-describedby="textHelp">
                    <div id="user_name_error" style="color: red;"></div>
                  </div>
                  <div class="mb-3">
                    <label for="user_email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" aria-describedby="emailHelp">
                    <div id="user_email_error" style="color: red;"></div>
                  </div>
                  <div class="mb-4 password-container">
                    <label for="user_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="user_password" name="user_password">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fa-solid fa-eye-slash" id="eye-icon"></i>
                    </span>
                    <div id="user_password_error" style="color: red;"></div>
                  </div>
                  <div class="mb-4 password-container">
                    <label for="user_con_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="user_con_password" name="user_con_password">
                    <span class="toggle-password" onclick="togglePasswordVisibility2()">
                        <i class="fa-solid fa-eye-slash" id="eye-icon2"></i>
                    </span>
                    <div id="user_con_password_error" style="color: red;"></div>
                  </div>
                  <input type="submit" id="login_btn" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" value="Sign Up" onclick="return sign_up_verif()">

                  <div class="mt-4 social-container">
                      <button class="social-button google" onclick="window.location.href = './google_signup.php'; return false;">
                        <svg
                          class="svg"
                          xmlns="http://www.w3.org/2000/svg"
                          height="1em"
                          viewBox="0 0 488 512"
                        >
                          <path
                            d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"
                          ></path>
                        </svg>
                      </button>
                      <button class="social-button github" onclick="window.location.href = '<?php echo $userC->get_github_link(); ?>'; return false;">
                        <svg
                          class="svg"
                          xmlns="http://www.w3.org/2000/svg"
                          x="0px"
                          y="0px"
                          width="20"
                          height="20"
                          viewBox="0 0 64 64"
                        >
                          <path
                            d="M32 6C17.641 6 6 17.641 6 32c0 12.277 8.512 22.56 19.955 25.286-.592-.141-1.179-.299-1.755-.479V50.85c0 0-.975.325-2.275.325-3.637 0-5.148-3.245-5.525-4.875-.229-.993-.827-1.934-1.469-2.509-.767-.684-1.126-.686-1.131-.92-.01-.491.658-.471.975-.471 1.625 0 2.857 1.729 3.429 2.623 1.417 2.207 2.938 2.577 3.721 2.577.975 0 1.817-.146 2.397-.426.268-1.888 1.108-3.57 2.478-4.774-6.097-1.219-10.4-4.716-10.4-10.4 0-2.928 1.175-5.619 3.133-7.792C19.333 23.641 19 22.494 19 20.625c0-1.235.086-2.751.65-4.225 0 0 3.708.026 7.205 3.338C28.469 19.268 30.196 19 32 19s3.531.268 5.145.738c3.497-3.312 7.205-3.338 7.205-3.338.567 1.474.65 2.99.65 4.225 0 2.015-.268 3.19-.432 3.697C46.466 26.475 47.6 29.124 47.6 32c0 5.684-4.303 9.181-10.4 10.4 1.628 1.43 2.6 3.513 2.6 5.85v8.557c-.576.181-1.162.338-1.755.479C49.488 54.56 58 44.277 58 32 58 17.641 46.359 6 32 6zM33.813 57.93C33.214 57.972 32.61 58 32 58 32.61 58 33.213 57.971 33.813 57.93zM37.786 57.346c-1.164.265-2.357.451-3.575.554C35.429 57.797 36.622 57.61 37.786 57.346zM32 58c-.61 0-1.214-.028-1.813-.07C30.787 57.971 31.39 58 32 58zM29.788 57.9c-1.217-.103-2.411-.289-3.574-.554C27.378 57.61 28.571 57.797 29.788 57.9z"
                          ></path>
                        </svg>
                      </button>
                  </div>

                  <div class="mb-3" id="temp_space"></div>

                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                    <a class="text-primary fw-bold ms-2" href="./authentication-login.php">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../../../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  
  <script src="./SignIn_SignUp.js"></script>

  <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>

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
      echo ("<script>document.getElementById('user_email').value = '$email';</script>");
    }
  ?>

</body>

</html>