<?php
require_once __DIR__ . '/../../../../Controller/vendor/autoload.php';
require_once __DIR__ . '/../../../../Controller/subscriptionControls.php';

$folder_name = "/hireup/v1/";
$current_url = "http://{$_SERVER['HTTP_HOST']}{$folder_name}";

$stripe_secret_key = "sk_test_51PDZSUBnbkiTDTq9yrrjpOhjVYnvlAV4KZMcwG3vm1KGY1yubUXzvUmkCMNr6U8CvlLp2K5YDDzYecbO3p2lG47j00hJF7511m";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$subscriptionController = new SubscriptionControls();

// Fetch subscription data dynamically
$subscription_id = $_POST['subscription_id'];
$subscription = $subscriptionController->getSubscriptionById($subscription_id);

if (!$subscription) {
    http_response_code(404);
    exit();
}

//redirection based on sub

// Extract the price from the second character onwards
$price = substr($subscription['price'], 1);

// Convert the price to cents and remove leading zeros
$unit_amount = floatval(ltrim($price, '0')) * 100;


$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "$current_url" . "View/front_office/profiles_management/subscription/sub_profile_apply.php?sub=". $subscription['subscription_id'],
    "cancel_url" => "$current_url" . "View/front_office/profiles_management/profile.php",
    "locale" => "auto",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => $unit_amount,
                "product_data" => [
                    "name" => $subscription['plan_name']
                ]
            ]
        ]
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);

?>
