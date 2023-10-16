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

include 'chekcing.php';

// Get the R-number from the HTML input
if (isset($_POST['rNumber'])) {
    $rNumber = $_POST['rNumber'];

    // Sanitize the input to prevent SQL injection (you should use prepared statements for security)
    $rNumber = mysqli_real_escape_string($conn, $rNumber);

    // Query to check if the R-number exists in the users_test table
    $user_sql = "SELECT * FROM users_test WHERE r_number = '$rNumber'";

    $user_result = $conn->query($user_sql);

    if ($user_result->num_rows > 0) {
        // R-number exists in the users_test table
        $row = $user_result->fetch_assoc();
        $rNumber = $row['r_number'];
        $name = $row['first_name'];

        // Query to check if the R-number exists in the checkins_test table and checkout_time is '00:00:00'
        $checkin_sql = "SELECT * FROM checkins_test WHERE r_number = '$rNumber' AND checkout_time = '00:00:00'";
        $checkin_result = $conn->query($checkin_sql);

        if ($checkin_result->num_rows > 0) {
            // Update checkout_time with the current time
            $current_time = date("H:i:s", strtotime("-7 hours"));
            $update_sql = "UPDATE checkins_test SET checkout_time = '$current_time' WHERE r_number = '$rNumber' AND checkout_time = '00:00:00'";
            $conn->query($update_sql);
            $user_sql = "SELECT * FROM users_test WHERE r_number = '$rNumber'";
            $user_result = $conn->query($user_sql);
        
            if ($user_result->num_rows > 0) {
                $row = $user_result->fetch_assoc();
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
        
                // Redirect to checkout.php and pass the necessary query parameters
                header("Location: checkout.php?rNumber=$rNumber&firstName=$firstName&lastName=$lastName");
                exit;
            }
        } else {
            // Check if the user is checked-in with a non-zero checkout_time
            $checkin_sql = "SELECT * FROM checkins_test WHERE r_number = '$rNumber' AND checkout_time != '00:00:00'";
            $checkin_result = $conn->query($checkin_sql);

            if ($checkin_result->num_rows > 0) {
                // Redirect to login.html for check-in
                header("Location: login.html?rNumber=$rNumber");
                exit;
            } else {
                // Display a message indicating that the user is not checked-in
                $message = $name . ", you are not checked-in";
                echo "<script>displayResult('$message', 'red');</script>";
            }
        }
    } else {
        // R-number does not exist in the users_test table
        // Pass the R-number as a query parameter to signup.html
        header("Location: signup.html?rNumber=$rNumber");
        exit; // Exit PHP to prevent further execution of the PHP script
    }
}
?>
