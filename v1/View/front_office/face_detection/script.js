var scriptSrc = document.currentScript.src;
var scriptDirectory = scriptSrc.substring(0, scriptSrc.lastIndexOf("/"));

const video = document.getElementById("video");
cap_btn = document.getElementById("captureBtn");


Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri(scriptDirectory+"/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri(scriptDirectory+"/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri(scriptDirectory+"/models"),
  faceapi.nets.faceExpressionNet.loadFromUri(scriptDirectory+"/models"),
  faceapi.nets.ageGenderNet.loadFromUri(scriptDirectory+"/models"),
]).then(webCam);

function webCam() {
  navigator.mediaDevices
    .getUserMedia({
      video: true,
      audio: false,
    })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((error) => {
      console.log(error);
    });
}

video.addEventListener("play", () => {
  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);

  faceapi.matchDimensions(canvas, { height: video.height, width: video.width });

  setInterval(async () => {
    const detection = await faceapi
      .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceExpressions().withAgeAndGender();
    canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

    const resizedWindow = faceapi.resizeResults(detection, {
      height: video.height,
      width: video.width,
    });

    faceapi.draw.drawDetections(canvas, resizedWindow);
    faceapi.draw.drawFaceLandmarks(canvas, resizedWindow);
    faceapi.draw.drawFaceExpressions(canvas, resizedWindow);

    resizedWindow.forEach((detection) => {
      const box = detection.detection.box;
      const drawBox = new faceapi.draw.DrawBox(box, {
        label: Math.round(detection.age) + " year old " + detection.gender,
      });
      drawBox.draw(canvas);
    });

    // console.log(detection);
    console.log(detection);

    //change the capteur btn state
    if (detection.length > 0){
      // face detected
      cap_btn.disabled = false;
    } else {
      cap_btn.disabled = true;
    }
  }, 100);
});
