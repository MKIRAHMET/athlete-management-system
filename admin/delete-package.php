<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Check if $_SESSION['admin_logged_in'] is empty or if $_SESSION["admin_logged_in"] is not set or not equal to true
    // Redirect to the login page
    header("Location: ../../admin/login.php");
    exit; // Terminate the script to prevent further execution
}

// Check if the package ID is provided in the URL
if(isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    // Prepare SQL statement to delete the package
    $sql = "DELETE FROM tblathletes WHERE id = :pid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pid', $pid, PDO::PARAM_INT);

    // Execute the SQL statement
    $query->execute();

    // Check if the package is successfully deleted
    if($query->rowCount() > 0) {
        // Package is deleted successfully
        $_SESSION['success'] = "Package deleted successfully.";
    } else {
        // Package is not deleted
        $_SESSION['error'] = "Failed to delete package. Please try again.";
    }
} else {
    // Package ID is not provided in the URL
    $_SESSION['error'] = "Package ID not provided.";
}

// Redirect back to the manage-packages.php page
header("Location: manage-packages.php");
exit; // Terminate the script to prevent further execution
?>
