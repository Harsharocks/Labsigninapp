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
            background-image: url('Pic.png');
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
        <h1>You are Checked-in Successfully</h1>
        
    <?php
    // Check if the 'rId' parameter exists in the $_GET array
    if (isset($_GET['rId'])) {
        // Define your database connection parameters
        $servername = "127.0.0.1";
        $username = "root";
        $password = "root";
        $dbname = "signup"; // Change to your database name

        // Create a database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the 'rId' value from the $_GET array
        if (isset($_GET['rId'])) {
            $rNumber = $_GET['rId'];

        // SQL query to select first name and last name from the 'users_test' table
        $sql = "SELECT first_name, last_name FROM users WHERE r_number = '$rNumber'";

        // Execute the query
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch the first row (assuming there's only one user)
            $row = $result->fetch_assoc();
            $firstName = $row["first_name"];
            $lastName = $row["last_name"];

            // Display a welcome message with the user's first name and last name
            echo "<p id='welcomeMessage'>Welcome, $firstName $lastName!</p>";
        } else {
            echo "No records found in the 'users' table.";
        }
    }
        else{
            echo "No 'rId' parameter found in the URL.";
        }

        // Close the database connection
        $conn->close();
    }
    ?>
</div>
<script>
    // Use JavaScript to redirect to log.html after 3 seconds
    setTimeout(function () {
        window.location.href = "log.html";
    }, 3000);
</script>
</body>
</html>
