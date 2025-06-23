<?php

include_once  __DIR__ . '/../../../Controller/user_con.php';
include_once  __DIR__ . '/../../../Model/user.php';

include_once __DIR__ . './../../../Controller/stats_con.php';

$statsC = new StatsCon("stats");
$userC = new userCon('user');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

require_once __DIR__ . './../../../Controller/vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

// Your GitHub OAuth application credentials
$client_id = 'd037c92b841fb74c1517';
$client_secret = '96b9503f0afad35d38f231ba6ec4b8edb3c86769';

// Step 1: Redirect users to GitHub for authorization
function get_github_link($client_id) {
    $link_to_follow = "https://github.com/login/oauth/authorize?client_id=$client_id&scope=user:read,user:email";
    return $link_to_follow;
}

// Step 2: Handle the GitHub callback
if (isset($_GET['error']) || !isset($_GET['code'])) {
    $error_global = "Error logging in tho GitHub.";
    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
    exit();
}

$authCode = $_GET['code'];

// Step 3: Exchange the code for an access token
function exchangeCode($data, $apiUrl) {
    $client = new Client();

    try {
        $response = $client->post($apiUrl, [
            'form_params' => $data,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents());
        }
        return false;
    } catch (RequestException $e) {
        return false;
    }
}

$data = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $authCode,
];

$apiUrl = "https://github.com/login/oauth/access_token";

$tokenData = exchangeCode($data, $apiUrl);

if ($tokenData === false) {
    $error_global = "Error logging in tho GitHub [Error getting token].";
    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
    exit('Error getting token');
}

if (!empty($tokenData->error)) {
    $error_global = "Error logging in tho GitHub [" . $tokenData->error . "].";
    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
    exit($tokenData->error);
}

// Step 4: Fetch the user's email address
$apiUrlEmails = "https://api.github.com/user/emails";
$headers = [
    'Authorization' => 'token ' . $tokenData->access_token,
    'User-Agent' => 'HireUp' // Replace with your app name
];

$client = new Client();
$response = $client->get($apiUrlEmails, [
    'headers' => $headers
]);

$emailData = json_decode($response->getBody()->getContents());

// Find the primary email address
$primaryEmail = null;
foreach ($emailData as $email) {
    if ($email->primary == true && $email->verified == true) {
        $primaryEmail = $email->email;
        break;
    }
}

if ($primaryEmail === null) {
    $error_global = "Error logging in tho GitHub [" . 'Primary email not found or not verified' . "].";
    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
    exit('Primary email not found or not verified');
}

// Now you have the user's primary email address
// You can use this information to complete the sign-in process

// Assuming you have the access token stored in a variable named $accessToken
$accessToken = $tokenData->access_token; // Replace with your actual access token

$apiUrl = "https://api.github.com/user";
$client = new Client();

try {
    $response = $client->get($apiUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
            'User-Agent' => 'HireUp' // Replace with your app name
        ]
    ]);

    if ($response->getStatusCode() == 200) {

        $user = json_decode($response->getBody()->getContents());
        
        echo '<pre>';
        var_dump($user);
        echo '</pre>';
        // Redirect to your action page
        echo $primaryEmail;
        #header('Location: github_signin_action.php');

        // MARK: getting all the data needed for the sign in/sign up process

        $email = $primaryEmail;
        $name = $user->login;
        
        $is_verified = 'true';

        $userC = new userCon('user');
        $user_name = $userC->get_user_name_out_of_google_name($name);

        if ($userC->emailExists($email)){
            $user_id = $userC->get_user_id_by_username_or_email($email);
            $user_account_type = $userC->get_account_type_by_id($user_id);

            if ($user_account_type == 'github') {
                
                echo("Your verified ");
                echo("Welcome user id : " . $user_id);
                $_SESSION['user id'] = $user_id;

                header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php');


            } else {
                # check email connected to a google account
                $error_user_name_email = "Sorry, this email is associated with an existing account but is not linked to a GitHub account. Please sign in using your existing credentials or register with a Google account.";
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
                'github',
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


        exit();

    } else {
        
        $data_error = 'Failed to fetch user details';
        
        $error_global = "Error logging in tho GitHub [" . $data_error . "].";
        header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
    }
} catch (RequestException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    $data_error = 'Failed to fetch user details';
        
    $error_global = "Error logging in tho GitHub [" . $data_error . "].";
    header('Location: ../../../View/front_office/Sign In & Sign Up/authentication-login.php?error_global=' . urlencode($error_global));
}

?>
