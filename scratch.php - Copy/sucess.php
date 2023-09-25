<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            flex-direction: column;
            background-image: url('pic.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        #container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
            margin-top: 50px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 20px;
            font-weight: bold;
            color: #007BFF;
        }
    </style>
</head>
<body>
<div id="container">
        <h1>You are Checked-in</h1>
        <p id="welcomeMessage">Welcome, loading...</p>
    </div>
    
    <script>
        // Use JavaScript to retrieve and display the welcome message
        document.addEventListener("DOMContentLoaded", function () {
            var firstName = "<?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''; ?>";
            var lastName = "<?php echo isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''; ?>";
            console.log("First Name:", firstName);
            console.log("Last Name:", lastName);            


            var welcomeMessage = document.getElementById("welcomeMessage");
            welcomeMessage.textContent = "Welcome, " + firstName + " " + lastName + "!";
        });

        // Redirect to log.html after 3 seconds
        setTimeout(function () {
          window.location.href = "log.html";
        }, 3000); // 3000 milliseconds (3 seconds)
    </script>



</body>
</html>
