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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $professorName = $_POST['professorName']; // Get professor's name from the hidden input field

    // Sanitize the input
    $professorName = mysqli_real_escape_string($conn, $professorName);
    $numStudents = $_POST['numStudents'];
    $purpose = $_POST['purpose'];
  

    // Get the current date and time
    $checkinDate = date("Y-m-d");
    $checkinTime = date("H:i:s");
    $checkoutTime = "00:00:00"; 
    // Check if a record for the professor already exists in the checkins table and is not checked out
    $check_existing_record_sql = "SELECT * FROM professors WHERE professor_name = '$professorName' ";
    $check_existing_record_result = $conn->query($check_existing_record_sql);

    if ($check_existing_record_result->num_rows > 0) {
        // Professor's record already exists and is not checked out
        // Update the existing record with the new visit data
        $insert_sql = "INSERT INTO professor_checkins (professor_name, purpose_of_visit, num_students, checkin_date, checkin_time, checkout_time)
                  VALUES ('$professorName', '$purpose', '$numStudents', CURDATE(), CURTIME(), '$checkoutTime')";
        
        if ($conn->query($insert_sql) === TRUE) {
            // Visit data updated successfully
            header("Location: professor_successpage.php?name=" . urlencode($professorName));
            exit;
        } else {
            // Handle the case where the update failed
            echo "Error: " . $conn->error;
        }
    } else {
        // Handle the case where the professor's record does not exist or is already checked out
        // You can add additional logic or error handling here if needed
        echo "Professor's record not found or is already checked out.";
    }
}

// Close the database connection
$conn->close();
?>
