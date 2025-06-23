<?php 

include_once  __DIR__ . '/../../../Controller/user_con.php';

// $googleLogin = new GoogleLogin(redirect_url: "http://localhost/hireup/v1/View/callback.php");



// $googleLogin->get_client_infos();



require_once __DIR__ . './../../../Controller/vendor/autoload.php';
include_once  __DIR__ . '/../../../Controller/user_con.php';
include_once  __DIR__ . '/../../../Model/user.php';

include_once __DIR__ . './../../../Controller/stats_con.php';

$statsC = new StatsCon("stats");

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

$clientID = '894714799937-ern1o91j1vnalapk86b8fkerlqp41cik.apps.googleusercontent.com';
$clientSecret = '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';
//$redirectUri = 'http://localhost/tries/glogin/login.php';
$redirectUri = 'http://localhost/hireup/v1/View/front_office/Sign%20In%20&%20Sign%20Up/google_signin.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
  
    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    echo "aaaa";
    echo " ". $email ." ";
    echo " ". $name ." ";
    echo "<br>";
    echo "<pre>";
    print_r($google_account_info);

    $email = $google_account_info['email'];
    $name = $google_account_info['name'];
    $first_name = $google_account_info['givenName'];
    $last_name = $google_account_info['familyName'];
    $is_verified = $google_account_info['verifiedEmail'] == '1' ? 'true' : 'false';

    $userC = new userCon('user');
    $user_name = $userC->get_user_name_out_of_google_name($name);

    if ($userC->emailExists($email)){
        $user_id = $userC->get_user_id_by_username_or_email($email);
        $user_account_type = $userC->get_account_type_by_id($user_id);

        if ($user_account_type == 'google') {
            

            $_SESSION['user id'] = $user_id;

            header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php');
    


        } else {
            # check email connected to a google account
            $error_user_name_email = "Sorry, this email is associated with an existing account but is not linked to a Google account. Please sign in using your existing credentials or register with a Google account.";
            header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_user_name_email=' . urlencode($error_user_name_email) . '&user_name_email=' . urlencode($email) );
            exit(); // Make sure to stop further execution after redirection 
        }

    }else{
        # check email doesnt existence
        // $error_user_name_email = "This email does not exist in our records. Would you like to register instead?";
        // header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_user_name_email=' . urlencode($error_user_name_email) . '&user_name_email=' . urlencode($email) );
        
        // signing in instead
        // MARK: signing in instead
        $random_number = rand(1, 8);

        $generated_nb = $userC->generateId($random_number);

        $hashed_password = password_hash('change_me' . $generated_nb , PASSWORD_DEFAULT);

        // get current date
        $currentDate = date("Y-m-d");
        
        $user = new User(
            $userC->generateUserId(5),
            $user_name,
            $email,
            $hashed_password,
            "user",
            $is_verified,
            "false",
            $currentDate,
            'google',
            'true',
        );

        $userC->addUser($user);

        // add stats
        $currentDate = date("Y-m-d");

        $statsC->addUserAccountCreatedInStat($currentDate);

        // $success_message = "Account created successfully!";
        // header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?success_global=' . urlencode($success_message) . '&user_name_email=' . urlencode($user->get_user_name()));
        // exit(); // Make sure to stop further execution after redirection

        $user_id = $userC->get_user_id_by_username_or_email($email);
        $_SESSION['user id'] = $user_id;
        header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?success_global=' . urlencode($success_message) . '&user_name_email=' . urlencode($user->get_user_name()));
        exit(); // Make sure to stop further execution after redirection
        
    }

    //creating the account
    // $hashed_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
        
    //     // get current date
    //     $currentDate = date("Y-m-d");
        
    //     $user = new User(
    //         $userC->generateUserId(5),
    //         $_POST['user_name'],
    //         $_POST['user_email'],
    //         $hashed_password,
    //         "user",
    //         "false",
    //         "false",
    //         $currentDate
    //     );
  
    // now you can use this profile info to create account in your website and make user logged in.
  } else {
    //echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";

    $authUrl = $client->createAuthUrl();

    header('Location: ' . $authUrl);
    exit;
  }

?>
