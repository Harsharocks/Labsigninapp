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

// Get the R-number from the HTML input
if (isset($_POST['rNumber'])) {
    $rNumber = $_POST['rNumber'];

    // Sanitize the input to prevent SQL injection (you should use prepared statements for security)
    $rNumber = mysqli_real_escape_string($conn, $rNumber);

    // Query to check if the R-number exists in the database
    $sql = "SELECT * FROM users WHERE r_number = '$rNumber'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // R-number exists in the database
        $row = $result->fetch_assoc();
        $rNumber = $row['r_number'];

    // Redirect to the login page with the R-number as a query parameter
        header("Location: login.html?rNumber=$rNumber");
    } else {
        // R-number does not exist in the database
        echo "User not found... Redirecting to signup page.";
        
        // PHP redirection to sig.html
        header("Location: signup.html");
        exit; // Exit PHP to prevent further execution of the PHP script
    }

    // No further code here, the script will exit and display only sig.html
}
?>
