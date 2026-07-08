<?php
session_start();
include('includes/config.php');
include('../../admin/db.php'); // Include your database connection script

// Check if user is logged in as admin
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: ../../admin/login.php");
    exit; // Stop further execution
}

// Check if athlete ID is provided in the URL
if(isset($_GET['id'])) {
    $athlete_id = intval($_GET['id']);

    // Prepare SQL statement to delete athlete from athlete_requests table
    $delete_sql = "DELETE FROM athlete_requests WHERE id = :athlete_id";
    $delete_query = $dbh->prepare($delete_sql);
    $delete_query->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);

    // Execute the delete query
    if ($delete_query->execute()) {
        // Athlete deleted successfully
        $msg = "Athlete deleted successfully";
    } else {
        // Error occurred while deleting athlete
        $error_msg = "Error deleting athlete";
    }
} else {
    // No athlete ID provided in the URL
    $error_msg = "Athlete ID not provided";
}

// Redirect back to the manage athletes page with success or error message
header("Location: manage-bookings.php" . (isset($msg) ? "?success=" . urlencode($msg) : "?error=" . urlencode($error_msg)));
exit; // Stop further execution
?>
