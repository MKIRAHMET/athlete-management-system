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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve the ID and updated category details from the form
    $id = $_POST['id'];
    $category_name = $_POST['category_name'];

    // Prepare and execute the UPDATE query
    $stmt = $conn->prepare("UPDATE categories SET category_name=? WHERE id=?");
    $stmt->bind_param("si", $category_name, $id);

    if ($stmt->execute()) {
        // Redirect to list_categories.php upon successful update
        header("Location: ad_cat.php");
        exit();
    } else {
        // Log error and show a generic error message
        error_log("Error updating record: " . $stmt->error);
        echo "Error updating record. Please try again later.";
    }

    $stmt->close();
}

$conn->close();
?>
