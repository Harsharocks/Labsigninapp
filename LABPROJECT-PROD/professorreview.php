<?php
// Database configuration (adjust these based on your setup)
$host = "127.0.0.1";
$username = "root";
$password = "root";
$database = "signup";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $professorName = $_POST["professorName"];
    $rating = $_POST["rating"];
    $feedbackText = $_POST["feedbackText"];

    // Insert data into the database
    $sql = "INSERT INTO professor_feedback (professor_name, rating, feedback_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $professorName, $rating, $feedbackText);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
