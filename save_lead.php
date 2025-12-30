<?php
// save_lead.php

$servername = "localhost";
$username = "mastissx_log_user"; // UPDATE THIS
$password = "crbY}~zsI9Fy"; // UPDATE THIS
$dbname = "mastissx_mastery_logs"; // UPDATE THIS

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { die("Connection failed"); }

// Get data from the form
$device_id = $conn->real_escape_string($_POST['device_id']);
$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);

// Check if this device already submitted (prevent duplicates)
$check = "SELECT * FROM leads WHERE device_id = '$device_id'";
$result = $conn->query($check);

if ($result->num_rows == 0) {
    // Save new lead
    $sql = "INSERT INTO leads (device_id, name, phone) VALUES ('$device_id', '$name', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Already Registered";
}

$conn->close();
?>