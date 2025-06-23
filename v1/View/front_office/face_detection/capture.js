const video_from_page = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('captureBtn');

// Function to capture a frame from the video
function captureFrame() {
    // Draw the current frame of the video onto the canvas
    canvas.width = video_from_page.videoWidth;
    canvas.height = video_from_page.videoHeight;
    canvas.getContext('2d').drawImage(video_from_page, 0, 0, canvas.width, canvas.height);

    // Convert the canvas content to a Blob object
    canvas.toBlob(blob => {
        // Create a FormData object to send the Blob to PHP
        const formData = new FormData();
        formData.append('face_image', blob, 'captured_image.png');

        // Send the Blob to PHP using an AJAX request
        $.ajax({
            type: 'POST',
            url: 'add_a_face.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Image uploaded successfully:', response);
                window.location.href = './capture_resualt_ui.php'
            },
            error: function(xhr, status, error) {
                console.error('Error uploading image:', error);
            }
        });
    }, 'image/png');

}

// Event listener for the capture button
captureBtn.addEventListener('click', captureFrame);
