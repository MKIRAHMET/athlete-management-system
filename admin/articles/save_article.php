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

// Fetch data from the form
$title = $_POST['title'];
$content = $_POST['content'];
$status = $_POST['status'];
$publication_date = $_POST['publication_date'];
$category = $_POST['category'];
$new_category = $_POST['new_category'];
$tags = $_POST['tags'];
$image_url = $_POST['image_url'];

// Check if the selected category is 'other' and a new category is provided
if ($category === 'other' && !empty($new_category)) {
    // Insert the new category into the categories table
    $insertCategoryQuery = "INSERT INTO categories (category_name) VALUES (?)";
    $stmt = $conn->prepare($insertCategoryQuery);
    $stmt->bind_param("s", $new_category);

    if ($stmt->execute()) {
        // Get the ID of the newly inserted category
        $category_id = $stmt->insert_id;
        $stmt->close();

        // Use the newly inserted category ID for the article
        $category = $category_id;
    } else {
        // Handle the case where the new category couldn't be inserted
        $stmt->close();
        // Redirect to an error page or display an error message
        header("Location: error_page.php");
        exit();
    }
}

// Prepare and execute SQL statement to insert the article into the database
$sql = "INSERT INTO articles (title, content, status, publication_date, category, tags, image_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sssssss", $title, $content, $status, $publication_date, $category, $tags, $image_url);

if ($stmt->execute()) {
    header("Location: list_articles.php");
    exit();
} else {
    echo "ΣΦΑΛΜΑ: " . $stmt->error;
}

$stmt->close();
$conn->close();