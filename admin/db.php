<?php
$servername = "localhost";
$username = "umc";
$password = "800179869";
$dbname = "umc"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

