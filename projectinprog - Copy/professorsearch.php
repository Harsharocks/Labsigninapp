<?php
// Database connection parameters
$host = "127.0.0.1";
$username = "root";
$password = "root";
$database = "signup";
// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the partial name from the AJAX request
$partialName = $_POST['partialName'];

// Sanitize input
$partialName = mysqli_real_escape_string($conn, $partialName);

// Query to retrieve users whose names match the partial name
$sql = "SELECT professor_name FROM professors WHERE professor_name LIKE '$partialName%' LIMIT 6";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Display matching suggestions as clickable options
while ($row = mysqli_fetch_assoc($result)) {
    echo "<p onclick='selectUser(\"" . $row['professor_name'] . "\")'>" . $row['professor_name'] . "</p>";
}

// Close the database connection
mysqli_close($conn);
?>





