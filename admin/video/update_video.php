<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filename'])) {
    $filename = $_POST['filename'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Update video details in the database
    $sql = "UPDATE videos SET title = ?, description = ? WHERE video_path = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $filename);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the edit page with a success message or to another appropriate page
    header("Location: adminv.php");
    exit();
}
?>

