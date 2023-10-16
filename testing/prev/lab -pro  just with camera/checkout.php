<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out and Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            flex-direction: column;
            background-image: url('pic.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Make sure the container takes up the full viewport height */
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h2 {
            color: #333;
        }

        .content-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        /* Style the star rating container */
        .rating-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
        }

        /* Style the stars */
        .rating {
            font-size: 36px;
            cursor: pointer;
            color: #ccc;
            margin: 0 5px;
            transition: color 0.2s;
        }

        /* Style the selected stars */
        .selected,
        .rating:hover {
            color: #FFD700;
        }

        textarea,
        button {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border: 2px solid #333;
            border-radius: 5px;
            outline: none;
            margin-top: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Style the success message */
        #successMessage {
            display: none;
            color: green;
            margin-top: 10px;
        }

        /* Style the countdown message in red */
        #countdownMessage {
            color: red;
        }

        #camera-preview {
            position: absolute;
            top: 90px;
            right: 10px;
            width: 70%;
            max-width: 330px;
            height: auto;
            border: 2px solid #333;
            border-radius: 10px;
        }
        #checkoutpreview{

            color: green;
        }


    </style>
</head>
<body>
        <!-- Camera preview video element -->
        <video id="camera-preview" autoplay playsinline></video>


<div class="container">
<h1>CMLL</h1>
    <h2>Check-Out and Feedback</h2>

    <!-- Display a message to the user indicating that they are checked out -->
    <p>Hello <?php echo $_GET['firstName'] . ' ' . $_GET['lastName']; ?>, you are checked out.</p>
    <p id="checkoutpreview"> NOTE: Feedback is anonymus, camera preview is for checkout </p>
    <!-- Countdown Timer -->
    <p id="countdownMessage">Redirecting to login: <span id="countdown">15</span> sec</p>

    <!-- Feedback Form -->
    <div class="content-box">
        <h3>Feedback</h3>
        <form id="feedbackForm">
            <label for="rating">Rating:</label><br>
            <div class="rating-container">
                <span class="rating" onclick="setRating(1)">★</span>
                <span class="rating" onclick="setRating(2)">★</span>
                <span class="rating" onclick="setRating(3)">★</span>
                <span class="rating" onclick="setRating(4)">★</span>
                <span class="rating" onclick="setRating(5)">★</span>
            </div>
            <input type="hidden" name="rating" id="rating" value="0">
            <br><br>
            <label for="feedbackText">Feedback:</label><br>
            <textarea name="feedbackText" id="feedbackText" rows="4" cols="50" required autocomplete="off"></textarea>
            <br><br>
            <button type="button" onclick="submitFeedback()">Submit</button>
        </form>
        <!-- Success message will be displayed here -->
        <div id="successMessage"></div>
    </div>
</div>

<script>
        const rNumber = <?php echo json_encode($_GET['rNumber']); ?>;

        let countdown = 15; // Set the initial countdown time in seconds
        let countdownInterval;

        // JavaScript function to redirect to log.html after a countdown
        function redirectAfterCountdown() {
            const countdownElement = document.getElementById('countdown');

            function updateCountdown() {
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(countdownInterval); // Stop the countdown
                    window.location.href = 'log.html'; // Redirect to log.html
                }
                countdown--; // Decrement the countdown
            }

            countdownInterval = setInterval(updateCountdown, 1000); // Update the countdown every 1 second
        }

        // Call the redirectAfterCountdown function to start the countdown
        redirectAfterCountdown();

        // JavaScript function to set the selected rating
        function setRating(rating) {
            document.getElementById('rating').value = rating;
            const stars = document.querySelectorAll('.rating');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
            // Stop the countdown when a star is selected
            clearInterval(countdownInterval);
        }

// JavaScript function to submit feedback
function submitFeedback() {
    const rating = document.getElementById('rating').value;
    const feedbackText = document.getElementById('feedbackText').value;

    // Disable the submit button to prevent multiple submissions
    document.querySelector('button').disabled = true;

    // Make an asynchronous POST request to checkoutbackend.php
    fetch('checkoutbackend.php', {
        method: 'POST',
        body: new URLSearchParams({ rating, feedbackText }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
    .then(response => response.text())
    .then(data => {
      
        
            window.location.href = 'feedback_sucess.html';
         
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

        // Add event listeners to stop the timer when the user interacts with the feedback form
        const ratingElements = document.querySelectorAll('.rating');
        ratingElements.forEach(element => {
            element.addEventListener('click', () => {
                clearInterval(countdownInterval);
            });
        });

        const feedbackTextArea = document.getElementById('feedbackText');
        feedbackTextArea.addEventListener('input', () => {
            clearInterval(countdownInterval);
        });



        async function startCamera() {
            try {
                // Get user media (camera) with constraints
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });

                // Get the video element and set the stream as its source
                const videoElement = document.getElementById('camera-preview');
                videoElement.srcObject = stream;
            } catch (error) {
                console.error('Error accessing the camera:', error);
            }
        }

        // Call the startCamera function to initiate camera access
        startCamera();


        // JavaScript function to automatically capture a camera snapshot and send it to the server

        async function captureSnapshotAndSubmit() {
    try {
        // Get user media (camera) with constraints
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });

        // Get the video element and set the stream as its source
        const videoElement = document.getElementById('camera-preview');
        videoElement.srcObject = stream;

        // Capture a snapshot after a short delay (e.g., 2 seconds)
        setTimeout(async () => {
            const canvas = document.createElement('canvas');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

            // Convert the snapshot to a data URL (base64)
            const dataURL = canvas.toDataURL('image/jpeg');

           // Send the captured image data to the server using fetch
                const response = await fetch('snap.php?rNumber=' + rNumber, {
                    method: 'POST',
                    body: new URLSearchParams({ imageData: dataURL, rNumber: rNumber }),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                });


            if (response.ok) {
                // Successfully saved the snapshot on the server
                console.log('Snapshot saved on the server');
            } else {
                // Handle errors if needed
                console.error('Error saving snapshot on the server');
            }
        }, 1500); // Adjust the delay as needed (2 seconds in this example)
    } catch (error) {
        console.error('Error accessing the camera:', error);
    }
}

// Call the captureSnapshotAndSubmit function to initiate camera access and snapshot capture
captureSnapshotAndSubmit();




    </script>
</body>
</html>
