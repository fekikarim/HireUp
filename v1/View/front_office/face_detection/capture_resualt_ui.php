<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        #videoContainer {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 5px;
        }
        #captureBtn,
        #cancelBtn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        #captureBtn:not([disabled]) {
            background-color: #4CAF50;
            color: white;
        }

        #cancelBtn:not([disabled]) {
            background-color: red;
            color: white;
        }

        #captureBtn[disabled],
        #cancelBtn[disabled] {
            background-color: #dddddd;
            color: #aaaaaa;
        }

        #captureBtn:hover:not([disabled]) {
            background-color: #45a049;
        }

        #cancelBtn:hover:not([disabled]) {
            background-color: #830000;
        }
    </style>
</head>

<?php

require_once __DIR__ . '/../../../Controller/faces_con.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

$faceC = new FaceController();


if(isset($_SESSION['user id'])) {

  $user_id = htmlspecialchars($_SESSION['user id']);

  $face_data = $faceC->getFaceByUserId($user_id);
  
}


?>

<body>
    <div class="container">
    <img src="data:image/jpeg;base64,<?php echo base64_encode($face_data['content']); ?>" alt="Profile Photo">
    <br>
    <br>
    <button id="cancelBtn" onclick="tryAginClicked()">Try Again</button>
    <button id="captureBtn" onclick="confirmClicked()">Confirm</button>
    </div>

    <script>
        function tryAginClicked(){
            window.parent.postMessage('tryAgain', '*');
        }
        function confirmClicked(){
            window.parent.postMessage('accepted', '*');
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>