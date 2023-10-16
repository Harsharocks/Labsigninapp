<!DOCTYPE html>
<html>
<head>
    <title>Check-In Success</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            background-image: url('pic.png');
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .content-box {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 400px;
        }

        h1 {
            color: #333;
            font-size: 28px;
        }
    </style>
</head>
<body>
    <div class="content-box">
        <?php
        // Get the professor's name from the URL parameter
        if (isset($_GET['name'])) {
            $professorName = $_GET['name'];
            echo "<h1>Welcome, Professor $professorName!</h1>";
            echo "<h2>You are checked in successfully.</h2>";
        } else {
            echo "<h1>Welcome, Professor!</h1>";
            echo "<p>You are checked in successfully.</p>";
        }
        ?>
    </div>

    <script>
        // Redirect to log.html after 3 seconds
        setTimeout(function() {
            window.location.href = "log.html";
        }, 3000); // 3000 milliseconds = 3 seconds
    </script>
</body>
</html>
