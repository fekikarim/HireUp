
<?php 
/*
require_once __DIR__ . '/../../../Controller/faces_con.php';

// if (session_status() == PHP_SESSION_NONE) {
//     session_set_cookie_params(0, '/', '', true, true);
//     session_start();
// }

$facesC = new FaceController();

$facesC->loadAllFacesImages();

$output = '';

$output = exec("python " . __DIR__ . "/../../../Controller/py_face_recognation/face_rec.py");

if ($output == "closed") { // closed
    header('Location: ./../../../index.php');
} else if (strpos($output, "detected :") === 0) {
    echo $output;
    $user_id = $facesC->extract_id($output);

    $_SESSION['user id'] = $user_id;
    //header('Location: ./../../../index.php');

} else {
    echo 'Error from python : ' . $output;
    //header('Location: ./../../../index.php');
}

*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .full-page-iframe {
            position: fixed;
            top: 0;
            left: 0;
            width: 640;
            height: 480;
            border: none; /* Remove the border to make it appear flat */
        }
    </style>

</head>
<body>
<iframe src="http://127.0.0.1:5000" frameborder="0" class="full-page-iframe"></iframe>
</body>
</html>