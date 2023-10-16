<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .refresh-button-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            padding: 40px 0;
        }

        .refresh-button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 50px;
        }

        .refresh-button:hover {
            background-color: #45a049;
        }

        .user-list {
            list-style-type: none;
            padding: 0;
        }

        .user-item {
            margin: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #fff;
            display: flex;
            flex-direction: row;
        }

        .user-info {
            flex: 1;
        }

        .image-container {
            display: flex;
        }

        .image-container img {
            max-width: 140px;
            max-height: 140px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="refresh-button-container">
        <button class="refresh-button" onclick="location.reload()">Refresh</button>
    </div>

    <div style="padding: 20px;">
        <h1>Today's Active Users</h1>
        <ul class="user-list">
            <?php
            // Database connection information
            $servername = "127.0.0.1";
            $username = "root";
            $password = "root";
            $dbname = "signup";

            // Create a database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get the current date
            $currentDate = date("Y-m-d");

            // Query to retrieve today's active users with checkout_time = '00:00:00'
            $sql = "SELECT r_number, first_name, last_name, purpose, checkin_time FROM checkins_test where checkout_time ='00:00:00' ";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='user-item'>";
                    echo "<div class='user-info'>";
                    echo "R Number: " . $row["r_number"];
                    echo "</div>";
                    echo "<div class='user-info'>";
                    echo "Purpose: " . $row["purpose"];
                    echo "</div>";
                    echo "<div class='user-info'>";
                    echo "First Name: " . $row["first_name"];
                    echo "</div>";
                    echo "<div class='user-info'>";
                    echo "Last Name: " . $row["last_name"];
                    echo "</div>";
                    echo "<div class='user-info'>";
                    echo "Checkin_time: " . $row["checkin_time"];
                    echo "</div>";

                    // Fetching and displaying images based on the r_number
                    $r_number = $row["r_number"];
                    $imagePath = "C:/xampp/htdocs/cmll-project/testing/camera/";

                    // Get up to 4 images for the current r_number
                    $images = glob($imagePath . $r_number . "_*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                    if (count($images) > 0) {
                        echo "<div class='user-info'>";
                        echo "<h4>Images:</h4>";
                        echo "<div class='image-container'>";
                        $count = 0;
                        foreach ($images as $image) {
                            // Convert the image to base64 for inline display
                            $imageData = file_get_contents($image);
                            $base64Image = base64_encode($imageData);

                            // Display the image using base64 encoding
                            echo "<img src='data:image/jpeg;base64, $base64Image' alt='$image' />";
                            $count++;
                            if ($count >= 5) {
                                break; // Display up to 4 images
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<div class='user-info'>No images found for this user.</div>";
                    }

                    echo "</li>";
                }
            } else {
                echo "No active users found for today.";
            }

            // Close the database connection
            $conn->close();
            ?>
        </ul>
    </div>
</body>
</html>
