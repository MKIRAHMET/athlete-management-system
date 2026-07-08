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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $path_to_file = '../../' . $filename; // Constructing the path
    
    // Delete the video file from the server
    if (unlink($path_to_file)) {
        // Delete the video file entry from the database
        $sql = "DELETE FROM videos WHERE video_path = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $filename);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: adminv.php");
            exit();
        } else {
            echo "Failed to delete the file entry from the database.";
        }
    } else {
        echo "Failed to delete the file from the server.";
    }
} else {
    echo "Invalid request.";
}
?>
