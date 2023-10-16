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
            background-image: url('img_cmll (1).jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #333;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Add a semi-transparent white background to improve readability */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        }
  
       .content-box {
           background-color: white;
           padding: 20px;
           border-radius: 10px;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
           text-align: center;
       }

        label {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .rating {
            font-size: 36px; /* Larger star size */
            cursor: pointer;
            color: #ccc; /* Default star color */
        }

        .selected,
        .rating:hover {
            color: #FFD700; /* Gold star color on hover and selection */
        }

        input[type="number"],
        textarea {
            display: none; /* Hide the actual input element */
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

        /* Style the feedback text area */
        textarea {
            padding: 10px;
            width: 300px;
            font-size: 18px; /* Increase font size */
            border: 2px solid #333;
            border-radius: 5px;
            outline: none;
            display: block;
            margin: 10px auto;
            height: 150px; /* Increase height to make it visible */
        }

        /* Style the success message */
        #successMessage {
            display: none;
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Check Out and Feedback</h1>
    
    <!-- Display a message to the user indicating that they are checked out -->
    <p>Hello <?php echo $_GET['firstName'] . ' ' . $_GET['lastName']; ?>, you are checked out.</p>
    
    <!-- Feedback Form -->
    <h2>Rate your experience</h2>
    <form id="feedbackForm" method="post" action="checkoutbackend.php?rNumber=<?php echo $_GET['rNumber']; ?>">
        <label for="rating">Rate your experience:</label><br>
        
        <!-- Star Rating -->
        <span class="rating" id="star1">&#9733</span>
        <span class="rating" id="star2">&#9733</span>
        <span class="rating" id="star3">&#9733</span>
        <span class="rating" id="star4">&#9733</span>
        <span class="rating" id="star5">&#9733</span>
        
        <input type="number" id="rating" name="rating" style="display: none;" required>
        
        <br>
        
        <label for="feedback">Feedback:</label><br>
        <textarea id="feedback" name="feedback" placeholder="Your feedback is valuable to us to grow" required></textarea><br>
        
        <!-- Hidden input to store the star count -->
        <input type="hidden" id="starCountInput" name="starCount" value="0">
        
        <button type="submit">Submit Feedback</button>
    </form>
    

    <!-- Success message -->
    <div id="successMessage">
        Feedback submitted successfully.
    </div>
    </div>

    <script>
        // JavaScript to handle the star rating
        const stars = document.querySelectorAll('.rating');
        const ratingInput = document.getElementById('rating');
        const starCountInput = document.getElementById('starCountInput');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                // Set the selected rating
                ratingInput.value = index + 1;

                // Update star colors based on the selected rating
                updateStars(index);
                // Update star count input
                starCountInput.value = index + 1;
            });

            star.addEventListener('mouseover', () => {
                // Highlight stars on hover
                updateStars(index);
            });

            star.addEventListener('mouseout', () => {
                // Restore star colors based on the selected rating
                const selectedRating = ratingInput.value || 0;
                updateStars(selectedRating - 1);
            });
        });

        function updateStars(index) {
            stars.forEach((star, i) => {
                if (i <= index) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        // JavaScript to handle the form submission
        const feedbackForm = document.getElementById('feedbackForm');
        const successMessage = document.getElementById('successMessage');

        feedbackForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent the form from submitting immediately

            // Simulate a successful submission (you can remove this code in your actual implementation)
            // Here, we're just showing the success message and then redirecting after a delay
            setTimeout(() => {
                successMessage.style.display = 'block';

                // After 3 seconds, redirect to log.html
                setTimeout(() => {
                    window.location.href = 'log.html';
                }, 3000); // 3000 milliseconds (3 seconds)
            }); // 1000 milliseconds (1 second) - Adjust the timing as needed
        });
    </script>
</body>
</html>
