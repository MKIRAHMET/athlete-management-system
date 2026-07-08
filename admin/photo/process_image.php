<?php
// Include the database connection script
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
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather image details from the form
    $title = $_POST['title'];
    $description = $_POST['description'];

    // File upload handling
    $targetDirectory = "uploads/"; // Directory where images will be stored
    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Insert image details into the database
        $sql = "INSERT INTO images (title, description, image_path) VALUES ('$title', '$description', '$targetFile')";
        
        if ($conn->query($sql) === TRUE) {
            // Redirect to the admin dashboard after successful upload
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    // Redirect back to the add_image.php if accessed without submitting the form
    header("Location: add_image.php");
    exit();
}
?>

