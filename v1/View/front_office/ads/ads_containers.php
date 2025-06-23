

<!-- Container for Ads -->

<script>
const postUrl = 'http://localhost/hireup/v1/view/front_office/ads/jobClicked.php';
function invokePhpFunction(pub_id) {
	console.log('job Clicked');
    // Make an AJAX request to your PHP script to execute the desired function
    // Example using jQuery AJAX:
    $.ajax({
       // url: 'jobClicked.php?id='+pub_id, // Replace 'your_php_script.php' with the path to your PHP script
        url: postUrl+'?id='+pub_id, // Replace 'your_php_script.php' with the path to your PHP script
        type: 'POST',
        data: { action: 'jobClicked' }, // Pass any necessary data to your PHP function
        success: function(response) {
            // Handle the response if needed
            console.log(response);
            if (response == "1 records UPDATED successfully <br>true") {
                return true;
            } else {
                return false;
            }
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(xhr.responseText);
            return false;
        }
    });
}
</script>

<?php 

    require_once __DIR__ . '/../../../Controller/profileController.php';


    if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
    }


    $profileController = new ProfileC();


    $user_id = '';
    $user_profile_id = '';

    //get user_profile id
    if (isset($_SESSION['user id'])) {
    $user_id = htmlspecialchars($_SESSION['user id']);
    $user_profile_id = $profileController->getProfileIdByUserId($user_id);
    $profile = $profileController->getProfileById($user_profile_id);

    }

  
  require_once __DIR__ . "/../../../Controller/pub_con.php";

  $pubC = new pubCon("dmd");

  //fetch subscription
    $subs_type = array(
        "1-ADVANCED-SUBS" => "advanced",
        "1-BASIC-SUBS" => "basic",
        "1-PREMIUM-SUBS" => "premium",
        "else" => "limited"
    );
    
    $current_profile_sub = "";
    if (array_key_exists($profile['profile_subscription'], $subs_type)) {
        // If it exists, return the corresponding value
        $current_profile_sub = $subs_type[$profile['profile_subscription']];
    } else {
        // If not, return 'bb'
        $current_profile_sub = $subs_type['else'];
    }



?>


<?php

if ($add_type == "center" && $current_profile_sub == "limited") {

?>


<!-- Center -->
<div id="ad-container" style="margin: 20px; text-align: center;">
<?php 
	$pubC->generate_pub(); // affichage des pubs
?>
</div>
<!-- End Center -->

<?php

}

?>

<?php

if ($add_type == "left" && $current_profile_sub == "limited") {

?>

<!-- Left -->
<div id="ad-container" style="margin: 20px;">
<?php 
	$pubC->generate_pub(); // affichage des pubs
?>
</div>
<!-- End Left -->

<?php

}

?>

<?php

if ($add_type == "right" && $current_profile_sub == "limited") {

?>

<!-- Right -->
<div id="ad-container" style="margin: 20px; text-align: end;">
<?php 
	$pubC->generate_pub(); // affichage des pubs
?>
</div>
<!-- End Right -->

<?php

}

?>



<!-- End Container for Ads -->


