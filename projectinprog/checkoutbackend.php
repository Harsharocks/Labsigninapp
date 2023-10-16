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

// Get the R-number from the query parameter
if (isset($_GET['rNumber'])) {
    $rNumber = $_GET['rNumber'];

    // Get the rating, feedback, and star count from the form
    if (isset($_POST['rating']) && isset($_POST['feedback']) && isset($_POST['starCount'])) {
        $rating = $_POST['rating'];
        $feedback = $_POST['feedback'];
        $starCount = $_POST['starCount'];

        // Sanitize the inputs to prevent SQL injection (you should use prepared statements for security)
        $rating = mysqli_real_escape_string($conn, $rating);
        $feedback = mysqli_real_escape_string($conn, $feedback);
        $starCount = mysqli_real_escape_string($conn, $starCount);


        // Insert the data into the database
        $insert_sql = "INSERT INTO feedback (r_number, rating, feedback, star_count) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        
        // Bind parameters and execute the statement
        $stmt->bind_param("siss", $rNumber, $rating, $feedback, $starCount);
        
        if ($stmt->execute()) {
            // Successfully inserted into the database
            // Redirect to a success page
            echo "R-number: " . $rNumber . "<br>";
            echo "Rating: " . $rating . "<br>";
            echo "Feedback: " . $feedback . "<br>";
            echo "Star Count: " . $starCount . "<br>";
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
