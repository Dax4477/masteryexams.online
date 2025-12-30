<?php
// 1. DATABASE CREDENTIALS (UPDATE THESE!)
$servername = "localhost";
$username = "mastissx_admin";   // The username you created in Step 1
$password = "vyjszIQuw494"; // The password you copied
$dbname = "mastissx_newsletter"; // The database name from Step 1

// 2. CONNECT TO DATABASE
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. CHECK IF FORM WAS SUBMITTED
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Simple validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // Prepare statement (Prevents hacking)
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            // Success! Redirect back to home with a success message
            echo "<script>alert('Thank you for subscribing!'); window.location.href='index.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid email format";
    }
}

$conn->close();
?>