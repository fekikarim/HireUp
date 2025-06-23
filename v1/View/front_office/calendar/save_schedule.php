<?php 
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Controller/schedualC.php';

include_once __DIR__ . '/../../../Controller/user_con.php';
require_once __DIR__ . '/../../../Controller/profileController.php';

$userC = new userCon("user");
$profileController = new ProfileC();

$user_id = null;

if (session_status() == PHP_SESSION_NONE) {
	session_set_cookie_params(0, '/', '', true, true);
	session_start();
}

if (isset($_SESSION['user id'])) {
	$user_id = htmlspecialchars($_SESSION['user id']);

	// Get profile ID from the URL
	$profile_id = $profileController->getProfileIdByUserId($user_id);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    exit;
}

$scheduleController = new ScheduleController();
extract($_POST);

if (empty($id)) {
    // Create a new schedule
    $id_sch = $scheduleController->generateScheduleId(5);
    $response = $scheduleController->createSchedule($id_sch, $title, $description, $start_datetime, $end_datetime, $profile_id, '');
} else {
    // Update existing schedule
    $response = $scheduleController->updateSchedule($id, $title, $description, $start_datetime, $end_datetime);
}

if (strpos($response, 'successfully') !== false) {
    header('Location: ./calendar.php');
} else {
    echo "<pre>";
    echo "An Error occurred.<br>";
    echo "Error: ".$response."<br>";
    echo "</pre>";
    header('Location: ./calendar.php');
}
?>
