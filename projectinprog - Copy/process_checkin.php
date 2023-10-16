<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        $sql = "SELECT first_name, last_name FROM users_test WHERE r_number = ?";
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

                // Check if base64-encoded image data is provided
                if (isset($_POST["userImage"])) {
                    $base64ImageData = $_POST["userImage"];

                    // Decode base64 and insert the image into the 'images_test' table
                    $imageData = base64_decode($base64ImageData);

                    // Insert the base64 image data into the 'images_test' table
                    $imageSql = "INSERT INTO images_test (r_number, image_data) VALUES (?, ?)";
                    $imageStmt = $conn->prepare($imageSql);
                    $imageStmt->bind_param("ss", $rNumber, $base64ImageData);

                    if ($imageStmt->execute()) {
                        // Insert data into the 'checkins_test' table
                        $checkinSql = "INSERT INTO checkins_test (r_number, purpose, first_name, last_name, checkin_date, checkin_time) VALUES (?, ?, ?, ?, CURDATE(), CURTIME())";
                        $checkinStmt = $conn->prepare($checkinSql);
                        $checkinStmt->bind_param("ssss", $rNumber, $purpose, $firstName, $lastName);

                        if ($checkinStmt->execute()) {
                            echo "Image stored successfully. Check-in successful. Welcome, $firstName $lastName!";
                            exit;
                        } else {
                            echo "Error inserting data into 'checkins_test' table: " . $checkinStmt->error;
                        }
                    } else {
                        echo "Error storing image: " . $imageStmt->error;
                    }
                } else {
                    // No image data provided
 // No image data provided
                  // Add this line for debugging
                  
                  echo " Welcome, $firstName $lastName! You are checked-in.";
                exit;

                }
            } else {
                // User not found, display an error message
                echo "User with R-number $rNumber not found in the database.";
            }
        } else {
            echo "Error executing SQL query: " . $stmt->error;
        }
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
