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
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Get the article ID from the URL
    $article_id = $_GET['id'];

    // Perform deletion query
    $sql = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $article_id);
    
    if ($stmt->execute()) {
        // Deletion successful, redirect to the article list page
        header("Location: announcement_a.php");
        exit();
    } else {
        echo "Error deleting article: " . $conn->error;
    }
} else {
    // Redirect to a suitable page if the ID is not provided or the request method is incorrect
    header("Location: announcement_a.php");
    exit();
}

$conn->close();
?>
