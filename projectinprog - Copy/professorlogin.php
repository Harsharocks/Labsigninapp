<?php
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "signup";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the professor's name from the URL parameter
if (isset($_GET['name'])) {
    $professorName = $_GET['name'];

    // Sanitize the input to prevent SQL injection (you should use prepared statements for security)
    $professorName = mysqli_real_escape_string($conn, $professorName);

    // Query to check if the professor's name exists in the professors table
    $professor_sql = "SELECT * FROM professors WHERE professor_name = '$professorName'";
    $professor_result = $conn->query($professor_sql);

    if ($professor_result->num_rows > 0) {
        // Professor's name exists in the professors table
        $row = $professor_result->fetch_assoc();
        $professorName = $row['professor_name'];

        // Query to check if the professor has a check-in record in the professor_checkins table with checkout_time = '00:00:00'
        $checkin_sql = "SELECT * FROM professor_checkins WHERE professor_name = '$professorName' AND checkout_time = '00:00:00'";
        $checkin_result = $conn->query($checkin_sql);

        if ($checkin_result->num_rows > 0) {
            // Professor is already checked in, update the checkout time
            $current_time = date("H:i:s");
            $current_date = date("Y-m-d");
            $update_sql = "UPDATE professor_checkins SET checkout_time = '$current_time' WHERE professor_name = '$professorName' AND checkout_time = '00:00:00'";
            
            if ($conn->query($update_sql) === TRUE) {
                // Check-out was successful, redirect to professor_review.php
                header("Location: professorreview.html?name=$professorName");
                exit;
            } else {
                // Handle the case where the check-out failed
                echo "Error updating checkout time: " . $conn->error;
            }
        } else {
            
            // Professor is not checked in, redirect to professor_visit.php
            header("Location: professorvisit.html?name=" . urlencode($professorName));
            exit;
        }
    } else {
        // Professor's name does not exist in the professors table
        echo "Professor $professorName not found in the database.";
    }
}

// Close the database connection
$conn->close();
?>
