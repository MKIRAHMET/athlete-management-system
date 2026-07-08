<?php
// Include database connection
include '../db.php';

// Check if the form is submitted and if selected_photos array is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selected_photos"])) {
    // Retrieve the array of selected photo IDs
    $selected_photos = $_POST["selected_photos"];

    // Iterate through the array and delete each selected photo
    foreach ($selected_photos as $photo_id) {
        // Fetch the photo path from the database
        $sql_fetch_photo_path = "SELECT photo_path FROM event_photos WHERE id = ?";
        $stmt_fetch_photo_path = $conn->prepare($sql_fetch_photo_path);
        $stmt_fetch_photo_path->bind_param("i", $photo_id);
        $stmt_fetch_photo_path->execute();
        $result_fetch_photo_path = $stmt_fetch_photo_path->get_result();
        
        if ($result_fetch_photo_path->num_rows > 0) {
            $row = $result_fetch_photo_path->fetch_assoc();
            $photo_path = $row['photo_path'];

            // Delete the photo file from the server
            if (unlink($photo_path)) {
                // Prepare SQL statement to delete photo from database
                $sql_delete_photo = "DELETE FROM event_photos WHERE id = ?";
                $stmt_delete_photo = $conn->prepare($sql_delete_photo);
                $stmt_delete_photo->bind_param("i", $photo_id);

                // Execute the delete statement
                if ($stmt_delete_photo->execute()) {
                    // Deletion successful
                } else {
                    // Deletion failed
                    echo "Error deleting photo with ID $photo_id from the database.";
                }

                // Close statement
                $stmt_delete_photo->close();
            } else {
                echo "Error deleting photo file: $photo_path from the server.";
            }
        } else {
            echo "Photo with ID $photo_id not found in the database.";
        }
    }

    // Redirect back to the page where the form was submitted
    header("Location: events.php?id=" . $event_id);
    exit();
} else {
    // If the form was not submitted or selected_photos array is not set, redirect to an error page or display an error message
    echo "Invalid request.";
}
?>
