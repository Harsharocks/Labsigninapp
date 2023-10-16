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

// Query to select users who haven't checked out within 1 hour (checkout_time is '00:00:00')
$select_sql = "SELECT professor_name, checkin_time FROM professor_checkins WHERE checkout_time = '00:00:00'";

$result = $conn->query($select_sql);

// Use a flag variable to track if any users meet the condition
$usersToUpdateCheckout = false;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $professorName = $row['professor_name'];
        $checkinTime = $row['checkin_time'];
        
        // Ensure that $checkinTime is a valid time string
        if (strtotime($checkinTime) !== false) {
            $checkinTimestamp = strtotime($checkinTime);

            // Calculate the difference in seconds between checkin_time and current time
            $currentTimestamp = time() - (7 * 3600); // Adjust for your timezone if necessary
            $timeDifferenceSeconds = $currentTimestamp - $checkinTimestamp;

            // Check if the time difference is at least 1 hour (3 * 60 * 60 seconds)
            if ($timeDifferenceSeconds >= (3 * 60 * 60)) {
                // Calculate the new checkout time (checkin_time + 1 hour)
                $newCheckoutTimestamp = $checkinTimestamp + (3 * 60 * 60);
                $newCheckoutTime = date("H:i:s", $newCheckoutTimestamp);

                // Update the checkout time for this user
                $update_sql = "UPDATE professor_checkins SET checkout_time = '$newCheckoutTime' WHERE professor_name = '$professorName'";
                
                // Execute the update query
                if ($conn->query($update_sql) === TRUE) {
                   // echo "Checkout time updated for R-number: $rNumber<br>";
                    $usersToUpdateCheckout = true;
                } else {
                   // echo "Error updating checkout time for R-number: $rNumber - " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Invalid checkin_time value for R-number: $rNumber<br>";
        }
    }
}

// Check the flag variable to display the appropriate message
if (!$usersToUpdateCheckout) {
    //echo "No users found who need checkout time updates.<br>";
}
?>
