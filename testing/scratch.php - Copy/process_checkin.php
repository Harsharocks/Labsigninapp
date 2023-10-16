<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the R-number, purpose, and captured image data from the form
    $rNumber = isset($_POST["rNumber"]) ? $_POST["rNumber"] : "";
    $purpose = isset($_POST["purpose"]) ? $_POST["purpose"] : "";
    $capturedImage = isset($_POST["captured_image"]) ? $_POST["captured_image"] : "";

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

    if ($rNumber !== "" && $purpose !== "" && $capturedImage !== "") {
        // Generate a unique filename based on the user's rNumber and current date/time
        $dateTime = date("Ymd_His");
        $imageFileName = "C:/xampp/htdocs/cmll-project/testing/camera/{$rNumber}_{$dateTime}.jpg";

        // Process the image data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $capturedImage));

        // Save the captured image to the specified folder
        if (file_put_contents($imageFileName, $imageData)) {
            // Image saved successfully
            // You can also add additional database logic here if needed

            // Query to check if the user with the given 8-digit R-number exists in the database
            $sql = "SELECT first_name, last_name FROM users_test WHERE r_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $rNumber);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User exists, retrieve first name and last name
                $stmt->bind_result($firstName, $lastName);
                $stmt->fetch();

                // Insert data into the 'checkins_test' table using prepared statement
                $insertSql = "INSERT INTO checkins_test (r_number, purpose, first_name, last_name, checkin_date, checkin_time, checkin_preview) VALUES (?, ?, ?, ?, CURDATE(), CURTIME(), ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("sssss", $rNumber, $purpose, $firstName, $lastName, $imageFileName);

                if ($insertStmt->execute()) {
                    // Redirect to success page with the R-number as a parameter
                header("Location: sucess.php?rId=" . urlencode($rNumber));

                    exit;
                } else {
                    echo "Error inserting data into 'checkins_test' table: " . $insertStmt->error;
                }
            } else {
                // User not found, display an error message
                echo "User with R-number $rNumber not found in the database.";
            }
        } else {
            echo "Error saving the image.";
        }
    } else {
        echo "Invalid input data.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
