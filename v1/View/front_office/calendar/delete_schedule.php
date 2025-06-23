<?php 
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Controller/schedualC.php';

if (!isset($_GET['id'])) {
    echo "<script> alert('Undefined Schedule ID.'); location.replace('./') </script>";
    exit;
}

$scheduleController = new ScheduleController();
$response = $scheduleController->deleteSchedule($_GET['id']);

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
