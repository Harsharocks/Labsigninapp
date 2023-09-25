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
    </style>
</head>
<body>
<div class="container">
    <h2>Check-Out and Feedback</h2>

    <!-- Display a message to the user indicating that they are checked out -->
    <p>Hello <?php echo $_GET['firstName'] . ' ' . $_GET['lastName']; ?>, you are checked out.</p>
    <!-- Countdown Timer -->
    <p id="countdownMessage">Redirecting to login: <span id="countdown">12</span> sec</p>

    <!-- Feedback Form -->
    <div class="content-box">
        <h3>Feedback</h3>
        <form id="feedbackForm">
            <label for="rating">Rating:</label><br>
            <div class="rating-container">
                <span class="rating" onmouseover="pauseTimer()" onmouseout="resumeTimer()" onclick="setRating(1)">★</span>
                <span class="rating" onmouseover="pauseTimer()" onmouseout="resumeTimer()" onclick="setRating(2)">★</span>
                <span class="rating" onmouseover="pauseTimer()" onmouseout="resumeTimer()" onclick="setRating(3)">★</span>
                <span class="rating" onmouseover="pauseTimer()" onmouseout="resumeTimer()" onclick="setRating(4)">★</span>
                <span class="rating" onmouseover="pauseTimer()" onmouseout="resumeTimer()" onclick="setRating(5)">★</span>
            </div>
            <input type="hidden" name="rating" id="rating" value="0">
            <br><br>
            <label for="feedbackText">Feedback:</label><br>
            <textarea name="feedbackText" id="feedbackText" rows="4" cols="50" required oninput="checkFeedbackText()" onfocus="pauseTimer()" onblur="resumeTimer()" autocomplete="off"></textarea>
            <br><br>
            <button type="button" onclick="submitFeedback()">Submit</button>
        </form>
        <!-- Success message will be displayed here -->
        <div id="successMessage"></div>
    </div>
</div>

<script>
let countdown = 12; // Set the initial countdown time in seconds
let countdownInterval;
let lastActivityTime = Date.now();
let feedbackTextEntered = false;
let starHoverTimer;
let redirectTimer;

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

// Pause the countdown timer
function pauseTimer() {
    clearInterval(countdownInterval);
    clearTimeout(redirectTimer);
}

// Resume the countdown timer
function resumeTimer() {
    const timeSinceLastActivity = Date.now() - lastActivityTime;

    if (timeSinceLastActivity >= 10000 && !feedbackTextEntered) {
        // If there was no activity for 10 seconds and no feedback text entered, resume the countdown
        redirectAfterCountdown();
    }

    if (timeSinceLastActivity >= 50000) {
        // If there was no activity for 50 seconds, redirect to log.html
        window.location.href = 'log.html';
    } else {
        // Otherwise, restart the countdown
        clearTimeout(redirectTimer);
        redirectTimer = setTimeout(() => {
            window.location.href = 'log.html';
        }, 40000 - timeSinceLastActivity);
    }
}

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
    // Reset the countdown when a star is selected
    countdown = 12;
    resumeTimer();
}

// Check if feedback text has been entered
function checkFeedbackText() {
    const feedbackText = document.getElementById('feedbackText').value;
    feedbackTextEntered = feedbackText.trim().length > 0;
    // Reset the countdown when feedback text is entered
    countdown = 12;
    resumeTimer();
}

// Handle star hover inactivity and redirect if needed
function starHoverInactivity() {
    starHoverTimer = setTimeout(function () {
        const timeSinceLastActivity = Date.now() - lastActivityTime;
        if (timeSinceLastActivity >= 10000) {
            // If there was no activity for 10 seconds while hovering stars, resume the countdown
            redirectAfterCountdown();
        }
    }, 10000); // 10000 milliseconds = 10 seconds
}

// Clear the star hover inactivity timer when the mouse is over stars
function clearStarHoverTimer() {
    clearTimeout(starHoverTimer);
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
        // Display the success message with green color
        const successMessage = document.getElementById('successMessage');
        successMessage.textContent = data;
        successMessage.style.display = 'block';

        // Wait for 3 seconds and then redirect to log.html
        setTimeout(function () {
            window.location.href = 'log.html';
        }, 3000); // 3000 milliseconds = 3 seconds
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

</script>

</body>
</html>

