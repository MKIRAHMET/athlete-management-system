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

    // Update title and description in the database based on the filename
    $sql = "UPDATE images SET title = ?, description = ? WHERE image_path = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $filename);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to update image details.";
    }
} else {
    echo "Invalid request.";
}
?>

