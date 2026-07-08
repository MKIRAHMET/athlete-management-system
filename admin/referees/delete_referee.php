<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Retrieve the ID from the form using $_POST
    $id = $_POST['id'];

    // Perform deletion query
    $sql = "DELETE FROM referees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Deletion successful, redirect to the category list page
        header("Location: ad_ref.php");
        exit();
    } else {
        echo "Error deleting category: " . $conn->error;
    }
} else {
    // Redirect to a suitable page if the ID is not provided or the request method is incorrect
    header("Location: ad_ref.php");
    exit();
}

$conn->close();
?>
