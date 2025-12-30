<?php
// capture_lead.php

// 1. DATABASE CONNECTION (Update these details!)
$servername = "localhost";
$username = "mastissx_lead";     // Replace with your cPanel DB Username
$password = "Obsera@1234"; // Replace with your cPanel DB Password
$dbname = "mastissx_lead";       // Replace with your cPanel DB Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

// 2. GET DATA FROM JAVASCRIPT
$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['name']) && isset($data['phone'])) {
    
    $name = $conn->real_escape_string($data['name']);
    $phone = $conn->real_escape_string($data['phone']);
    $level = isset($data['level']) ? $conn->real_escape_string($data['level']) : "Unknown";
    $score = isset($data['score']) ? $conn->real_escape_string($data['score']) : "0";

    // 3. INSERT INTO DATABASE
    $sql = "INSERT INTO exam_leads (name, phone, exam_level, score) VALUES ('$name', '$phone', '$level', '$score')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
}

$conn->close();
?>