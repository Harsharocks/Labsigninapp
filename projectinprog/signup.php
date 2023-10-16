<?php
header("Content-Type: text/html; charset=UTF-8");

$successMessage = ""; // Initialize an empty success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $rNumber = $_POST["R-Id"];

    // Define regular expressions for validation
    $namePattern = "/^[a-zA-Z]+$/"; // Only alphabets for first name and last name without spaces
    $rNumberPattern = "/^\d{8}$/";  // Exactly 8 digits for R-Number

    // Check if the data meets the validation criteria
    if (preg_match($namePattern, $firstName) && preg_match($namePattern, $lastName) && preg_match($rNumberPattern, $rNumber)) {
        // Database connection parameters
        $servername = "127.0.0.1";
        $username = "root";
        $password = "root";
        $dbname = "signup";

        // Create a database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the data into the database using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, r_number) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $firstName, $lastName, $rNumber);

        // Execute the prepared statement
        if ($stmt->execute()) {
            $successMessage = "Account created successfully!";
            header("Location: log.html");
        } else {
            $successMessage = "Error: Account creation failed.";
        }

        // Close the statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    } else {
        $successMessage = "Error: Invalid data. Please check your input.";
    }
}
?>
<!-- Rest of your HTML code remains the same -->



