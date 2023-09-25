<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the R-number and purpose from the form
    $rNumber = isset($_POST["rNumber"]) ? $_POST["rNumber"] : "";
    $purpose = isset($_POST["purpose"]) ? $_POST["purpose"] : "";
    
    // Get the current date and time
    $checkinDate = date("Y-m-d"); // Date in YYYY-MM-DD format
    $checkinTime = date("H:i:s"); // Time in HH:MM:SS format
    $checkoutTime = date("H:i:s"); // Current date and time
    
    // Define your database connection parameters
    $servername = "localhost"; // Change to your database server name
    $username = "root"; // Change to your database username
    $password = "root"; // Change to your database password
    $dbname = "signup"; // Change to your database name
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if (isset($_POST['rNumber']) && isset($_POST['purpose'])) {
        $rNumber = $_POST['rNumber'];
        $purpose = $_POST['purpose'];
    // Query to check if the user with the given 8-digit R-number exists in the database
    $sql = "SELECT first_name, last_name FROM users WHERE r_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rNumber);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // User exists, retrieve first name and last name
            $row = $result->fetch_assoc();
            $firstName = $row["first_name"];
            $lastName = $row["last_name"];
            
            // Insert data into the 'checkins' table using prepared statement
            $insertSql = "INSERT INTO checkins (r_number, purpose, first_name, last_name, checkin_date, checkin_time) VALUES (?, ?, ?, ?, CURDATE(), CURTIME())";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssss", $rNumber, $purpose, $firstName, $lastName); // "ssssss" indicates six string parameters
             
            if ($insertStmt->execute()) {
                echo "Check-in successful. Welcome, $firstName $lastName!";
               exit; 
           
            }          
            if ($insertStmt->execute()) {
                // Start a PHP session
                session_start();
                
                // Store user's first name and last name in session variables
                $_SESSION["firstName"] = $firstName;
                $_SESSION["lastName"] = $lastName;
                echo "First Name: " . $_SESSION["firstName"] . "<br>";
                echo "Last Name: " . $_SESSION["lastName"] . "<br>";               
                
                // Redirect to success.html
                header("Location: sucess.php");
                exit;   
            } else {
                echo "Error inserting data into 'checkins' table: " . $insertStmt->error;
            }
        } else {
            // User not found, display an error message
            echo "User with R-number $rNumber not found in the database.";
        }
    } else {
        echo "Error executing SQL query: " . $stmt->error;
    }
    
    // Close the database connection
    $stmt->close();
    $conn->close();
}}
?>