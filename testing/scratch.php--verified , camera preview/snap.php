<?php
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "signup";

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the image data (base64 encoded)
    $imageData = $_POST["imageData"];

    // Decode the base64 image data
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = base64_decode($imageData);

    // Generate a unique file name using r_number and datetime
    $rNumber = $_GET['rNumber'];
    $fileName = "C:/xampp/htdocs/cmll-project/testing/camera/{$rNumber}_" . date('YmdHis') . ".jpg";

    // Save the image to the specified folder
    if (file_put_contents($fileName, $imageData)) {
        // Update the checkins_test table with the image URL
        $sql = "UPDATE checkins_test SET checkout_preview = ? WHERE r_number = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fileName, $rNumber]);

        // Respond with a success message
        echo "Snapshot captured and saved successfully!";
    } else {
        echo "Error saving the snapshot.";
    }
} else {
    echo "Invalid request.";
}

$pdo = null;
?>
