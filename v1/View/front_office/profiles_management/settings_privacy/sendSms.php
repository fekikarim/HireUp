<?php

function formatPhoneNumber($phoneNumber) {
    // Remove any spaces
    $phoneNumber = str_replace(" ", "", $phoneNumber);
    
    // Check if the string starts with "216"
    if (strpos($phoneNumber, "216") === 0) {
        // Add "+" at the beginning
        return "+" . $phoneNumber;
    } elseif (strpos($phoneNumber, "+216") !== 0) {
        // Add "+216" at the beginning
        return "+216" . $phoneNumber;
    } else {
        // If already in desired format, return the original string
        return $phoneNumber;
    }
}

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}

require_once __DIR__ . "/../../../../Controller/vendor/autoload.php";
require_once __DIR__ . "/../../../../Controller/user_con.php";

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

$userC = new userCon("user");


// Part One: Send the verification code to the phone number and display the popup form
    // Get the profile phone number from the POST data
    //$profilePhoneNumber = $_GET["phone_nb"];
    $profilePhoneNumber = formatPhoneNumber($_GET["phone_nb"]);
    //$profilePhoneNumber = "+21621802449";
    $code = $userC->generateId(4);
    $_SESSION['verif_code'] = $code;
    //$_SESSION['phone_nb'] = $_GET['phone_nb'];


    // Generate a 4-digit verification code
    if (isset($_SESSION['verif_code'])){
        $verificationCode = $_SESSION['verif_code'];
    }

    //$verificationCode = $_POST["verification_code"];

    // Create the message containing the verification code
    $message = "Your verification code for HireUp: $verificationCode";

    // Infobip API configuration
    $base_url = "https://k2lmje.api.infobip.com";
    $api_key = "ede2c85f709b3d0a7c9c6e4a231129e5-6ffa59b7-3969-4f89-bf20-c2db157b45b9";

    $configuration = new Configuration(host: $base_url, apiKey: $api_key);
    $api = new SmsApi(config: $configuration);

    // Set the destination phone number to the profile phone number
    $destination = new SmsDestination(to: $profilePhoneNumber);

    // Create the SMS message
    $message = new SmsTextualMessage(
        destinations: [$destination],
        text: $message,
        from: "HireUp"
    );

    // Create the request to send the SMS
    $request = new SmsAdvancedTextualRequest(messages: [$message]);

    // Send the SMS message
    $response = $api->sendSmsMessage($request);

   //return $response;
   echo $code;
   return $code;


?>