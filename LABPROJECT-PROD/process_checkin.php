<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the R-number and purpose from the form
    $rNumber = isset($_POST["rNumber"]) ? $_POST["rNumber"] : "";
    $purpose = isset($_POST["purpose"]) ? $_POST["purpose"] : "";

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

        if ($stmt->num_rows > 0) {
            // User exists, retrieve first name and last name
            $stmt->bind_result($firstName, $lastName);
            $stmt->fetch();

            // Insert data into the 'checkins' table using prepared statement
            $insertSql = "INSERT INTO checkins (r_number, purpose, first_name, last_name, checkin_date, checkin_time) VALUES (?, ?, ?, ?, CURDATE(), CURTIME())";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssss", $rNumber, $purpose, $firstName, $lastName);

            if ($insertStmt->execute()) {
                // Check-in successful
                echo " Welcome, $firstName $lastName! You are checked-in.";
                exit;
            } else {
                echo "Error inserting data into 'checkins' table: " . $insertStmt->error;
            }
        } else {
            // User not found, display an error message
            echo "User with R-number $rNumber not found in the database.";
        }
    } else {
        echo "Invalid input data.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
