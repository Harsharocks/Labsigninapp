<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the R-number and purpose from the form
    $rNumber = isset($_POST["rNumber"]) ? $_POST["rNumber"] : "";
    $purpose = isset($_POST["purpose"]) ? $_POST["purpose"] : "";
    
    // Get the current date and time
    $checkinDate = date("Y-m-d"); // Date in YYYY-MM-DD format
    $checkinTime = date("H:i:s"); // Time in HH:MM:SS format
    $checkoutTime = date("Y-m-d H:i:s"); // Current date and time
    
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
    
    // Get the R-number from the form
    $rNumberInput = $_POST["rNumber"];
    
    // Extract only the first 8 digits from the R-number
    $rNumber = preg_replace("/[^0-9]/", "", $rNumberInput);
    $rNumber = substr($rNumber, 0, 8);
    
    // Query to check if the user with the given 8-digit R-number exists in the database
    $sql = "SELECT first_name, last_name FROM users WHERE r_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rNumber);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // User exists, retrieve first name and last name
            $row = $result->fetch_assoc();
            $firstName = $row["first_name"];
            $lastName = $row["last_name"];
            
            // Insert data into the 'checkins' table using prepared statement
            $insertSql = "INSERT INTO checkins (r_number, purpose, first_name, last_name, checkin_date, checkin_time, checkout_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("sssssss", $rNumber, $purpose, $firstName, $lastName, $checkinDate, $checkinTime, $checkoutTime); // "ssssss" indicates six string parameters
            
            if ($insertStmt->execute()) {
                echo "Check-in successful. Welcome, $firstName $lastName!";
                
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
}
?>
