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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $publication_date = $_POST['publication_date'];
    $category = $_POST['category'];
    $tags = $_POST['tags'];
    $image_url = $_POST['image_url'];
    $new_category = $_POST['new_category'];
    
    if ($category === 'other' && !empty($new_category)) {
        // Insert the new category into the database
        $insertCategoryQuery = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = $conn->prepare($insertCategoryQuery);
        $stmt->bind_param("s", $new_category);
        $stmt->execute();
        $stmt->close();

        $category = $new_category;
    }
       // Update the article with the selected or new category
       $stmt = $conn->prepare("UPDATE announcements SET 
       title=?, 
       content=?, 
       status=?, 
       publication_date=?, 
       category=? 
       WHERE id=?");

$stmt->bind_param("sssssi", $title, $content, $status, $publication_date, $category, $id);

if ($stmt->execute()) {
   // Redirect to list_articles.php upon successful update
   header("Location: announcement_a.php");
   exit();
} else {
   // Log error and show a generic error message
   error_log("Error updating record: " . $stmt->error);
   echo "Error updating record. Please try again later.";
}

$stmt->close();
} else {
echo "Invalid request";
}

$conn->close();
?>