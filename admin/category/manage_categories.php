<?php
// Include the database connection file
require_once('../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_category']) && !empty($_POST['new_category'])) {
        // Create a new category
        $newCategory = $_POST['new_category'];
        $sql = "INSERT INTO categories (category_name) VALUES ('$newCategory')";
        $conn->query($sql);
        header("Location: ad_cat.php");

    } elseif (isset($_POST['edit_category']) && !empty($_POST['edit_category'])) {
        // Edit or Delete a category
        $editCategory = $_POST['edit_category'];
        if (isset($_POST['edit'])) {
            // Edit category
            // Your logic to update the category in the database
            echo "Category '$editCategory' edited successfully!";
        } elseif (isset($_POST['delete'])) {
            // Delete category
            // Your logic to delete the category from the database
            echo "Category '$editCategory' deleted successfully!";
        }
    }
}

$conn->close();
?>
