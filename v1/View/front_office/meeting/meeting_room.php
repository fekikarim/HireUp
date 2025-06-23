<script src='https://8x8.vc/external_api.js'></script>


<?php

require __DIR__ . './../../../Controller/meeting_con.php';

$meetingC = new MeetingCon('meetings');

$secretKey = 'HireUp by be.net';

if (isset($_GET['jwt'])) {
    $jwt = htmlspecialchars($_GET['jwt']);
} else {
    $jwt = 'none';
}

try {
    $decoded = $meetingC->decodeJWT($jwt, $secretKey);
    
    //echo "Decoded Array: \n";
    //print_r($decoded);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    header('Location: ./../../../View/front_office/404/404.php');
}



$jwt_token = $meetingC->generateJWT(
    $decoded['email'],
    $decoded['username'],
    $decoded['moderator'],
    $decoded['userid'],
    $decoded['room'],
);


?>

<!-- <script type="text/javascript">
  let api;

  const initIframeAPI = () => {
    const domain = '8x8.vc';
    const options = {
      roomName: 'vpaas-magic-cookie-1fc542a3e4414a44b2611668195e2bfe/ExampleRoom',
      jwt: 'eyJhbGciOiJSUzI1NiIsImtpZCI6InZwYWFzLW1hZ2ljLWNvb2tpZS1kZjBjNWI3NzM5ZDU0YjBjODU2NjliNGNhZTFiMzg1My9lMjNjZDciLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJjaGF0IiwiYXVkIjoiaml0c2kiLCJleHAiOjE3MTYzMjY1NTAsIm5iZiI6MTcxNjMxOTM0MCwicm9vbSI6IioiLCJzdWIiOiJ2cGFhcy1tYWdpYy1jb29raWUtZGYwYzViNzczOWQ1NGIwYzg1NjY5YjRjYWUxYjM4NTMiLCJjb250ZXh0Ijp7InVzZXIiOnsibW9kZXJhdG9yIjoidHJ1ZSIsImVtYWlsIjoibXllbWFpbEBlbWFpbC5jb20iLCJuYW1lIjoiS2FyaW0iLCJhdmF0YXIiOiIiLCJpZCI6ImIyYzk0YTUwLWU1M2ItNGFmYy04YmVmLTMxMzJmM2VjMjdkYyJ9LCJmZWF0dXJlcyI6eyJyZWNvcmRpbmciOiJ0cnVlIiwibGl2ZXN0cmVhbWluZyI6ImZhbHNlIiwidHJhbnNjcmlwdGlvbiI6ImZhbHNlIiwib3V0Ym91bmQtY2FsbCI6ImZhbHNlIn19fQ.gFHR70vu2wxvfoiJlBh7Ek9owt4mHLyy8tjtc0zovvreqAhr9iekgzX85il6yhGnVITzUkxdwyJPXslwCozkclqegqOJRsUEK0TpC0ZezCtg8XNu0OwH26rCxF42Wch145LRszoeuCE-DjLprtj8hKF58uBwy9msuU9KIFpBjq5Uk3oMMxMPNgrQjjouyo7nSl3frgOyrPdOq3K5fJ3TQxH0jEzF0A6HFcw5czwYFm0GxzqWNsJiFXBlPI82fRjtMoFp2V67-cJe_az2-8Qm1mnZB-EZTa8IcT65xSJEr2N14VNVxmToDuu8Hlf4RIIMlnaxHjJJ2lCgweluKTBeGw',
      width: 700,
      height: 700,
      parentNode: document.querySelector('#meet')
    };
    api = new JitsiMeetExternalAPI(domain, options);
  }

  window.onload = () => {
    initIframeAPI();
  }
</script> -->





<!DOCTYPE html>
    <html>
      <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" type="image/png" href="./../../../front office assets/images/HireUp_icon.ico" />
      <title>HireUp Meet</title>
        <style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body{
                height: 100vh;
                background: #000;
            }
        </style>
        <script src='https://8x8.vc/vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853/external_api.js' async></script>
        <style>html, body, #jaas-container { height: 100%; }</style>
        <script type="text/javascript">
          window.onload = () => {
            const api = new JitsiMeetExternalAPI("8x8.vc", {
              //roomName: "vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853/SampleAppWorthyPhasesPackageLongTerm",
              roomName: '<?php echo $decoded['cookies'] . "/" . $decoded['room_name'] ; ?>', 
              parentNode: document.querySelector('#jaas-container'),
              jwt: '<?php echo $jwt_token;?>',
							// Make sure to include a JWT if you intend to record,
							// make outbound calls or use any other premium features!
							// jwt: "eyJraWQiOiJ2cGFhcy1tYWdpYy1jb29raWUtZGYwYzViNzczOWQ1NGIwYzg1NjY5YjRjYWUxYjM4NTMvMzdmZmM3LVNBTVBMRV9BUFAiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJqaXRzaSIsImlzcyI6ImNoYXQiLCJpYXQiOjE3MTYzMjEwMjUsImV4cCI6MTcxNjMyODIyNSwibmJmIjoxNzE2MzIxMDIwLCJzdWIiOiJ2cGFhcy1tYWdpYy1jb29raWUtZGYwYzViNzczOWQ1NGIwYzg1NjY5YjRjYWUxYjM4NTMiLCJjb250ZXh0Ijp7ImZlYXR1cmVzIjp7ImxpdmVzdHJlYW1pbmciOmZhbHNlLCJvdXRib3VuZC1jYWxsIjpmYWxzZSwic2lwLW91dGJvdW5kLWNhbGwiOmZhbHNlLCJ0cmFuc2NyaXB0aW9uIjpmYWxzZSwicmVjb3JkaW5nIjpmYWxzZX0sInVzZXIiOnsiaGlkZGVuLWZyb20tcmVjb3JkZXIiOmZhbHNlLCJtb2RlcmF0b3IiOnRydWUsIm5hbWUiOiJUZXN0IFVzZXIiLCJpZCI6Imdvb2dsZS1vYXV0aDJ8MTExMTExMjY2Nzk4MjYyODg2OTkxIiwiYXZhdGFyIjoiIiwiZW1haWwiOiJ0ZXN0LnVzZXJAY29tcGFueS5jb20ifX0sInJvb20iOiIqIn0.nVwPOyFIz_rk2m_4_as65UpFCHgnTQk16NbAQUOEU0r453ukCF3UZP2rx8ozI3cbZPiFDjCQaysRsOv_RZ3nANZ3X5MS8rj83mtNImzNIKOPjT_OR9bSGEctXQ_HUUkpqQ6JYK-j_Q03mAnCvm5TdiYOcWGpanjsFpqsG1a44k2xzJfcoTz_0avFSbV4d7ClzZ4b7i9mezPmvgZky6cgKFLN4qSBFquTnNTK0ERACVbLs8KoHpyg4bAO8hpmJY6O2ccjUXMCI1Yy4dOZs1M8Ctp-xdtvGaUABC4a0ARHntjbczNyMw0dkt7xXYUevX-uXj-kPEn2UwYaZuVfM0NjBA"
            });
          }
        </script>
      </head>
      <body>
        <div id="jaas-container"></div>
    </body>
    </html>
  
