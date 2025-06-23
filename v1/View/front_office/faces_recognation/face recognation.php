
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./../../../front office assets/images/HireUp_icon.ico" rel="icon">
    <title>HireUp Face Recognition</title>
</head>


<?php 

require_once __DIR__ . '/../../../Controller/faces_con.php';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
}


if( ! isset($_SESSION['user id'])) {
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
        header('Location: ./../../../index.php');

    } else {
        echo 'Error from python : ' . $output;
        //header('Location: ./../../../index.php');
    }

} else {
    header('Location: ./../../../index.php');
}




?>

<body>
    
</body>
</html>


