<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["main_poster"]) && isset($_POST["id"])) {
    $main_poster = $_POST["main_poster"];
    $id = $_POST["id"];

    // Check if the main poster exists before attempting to delete it
    if (file_exists($main_poster)) {
        // Delete the image file from the server
        if (unlink($main_poster)) {
            // Delete the image path from the database
            // Define the SQL query to update the main poster path to NULL
            $sql_delete_main_poster = "UPDATE events SET main_poster = NULL WHERE id = ?";
            
            // Prepare the SQL statement
            $stmt_delete_main_poster = $conn->prepare($sql_delete_main_poster);
            
            // Bind parameters
            $stmt_delete_main_poster->bind_param("i", $id);
            
            // Execute the statement
            if ($stmt_delete_main_poster->execute()) {
                header("Location: events.php?id=" . $event_id);
                exit();
                        } else {
                echo "Error deleting main poster from database: " . $stmt_delete_main_poster->error;
            }
            
            $stmt_delete_main_poster->close();
        } else {
            echo "Error deleting main poster file from server.";
        }
    } else {
        echo "Main poster file does not exist.";
    }
} else {
    echo "Invalid request.";
}
?>
