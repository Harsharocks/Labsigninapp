<?php
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "signup";

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating = $_POST["rating"];
    $feedbackText = $_POST["feedbackText"];

    // Insert the feedback data into the database
    // You should use prepared statements to prevent SQL injection

    // Assuming you have a table named "feed" with columns "rating," "feedback_text," and "created_at"
    $sql = "INSERT INTO feed (rating, feedback_text) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rating, $feedbackText]);

    // Display a success message
    echo "Feedback submitted successfully!";
} else {
    // Handle invalid form submission
    echo "Invalid request.";
}

// Close the PDO database connection
$pdo = null;
?>
