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
   ?>
<!DOCTYPE html>
<html>
<head>
    <title>Category Management</title>
    <script src="https://thalatta.umc.gr/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">
    <h2>Create New Category:</h2>
    <form method="POST" action="manage_categories.php">
        <label for="new_category">New Category Name:</label>
        <input type="text" id="new_category" name="new_category">
        <input type="submit" value="Create">
    </form>

    <h2>Existing Categories:</h2>
    <table border="1">
        <tr>
            <th>Category Name</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        // Include db.php to establish database connection
        require_once('../db.php');

        // Fetch existing categories from the database
        $sql = "SELECT * FROM categories";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["category_name"] . "</td>";
                echo "<td><form method='POST' action='edit_category.php?id={$row["id"]}'>
                <input type='hidden' name='id' value='" . $row["id"] . "'>
                <input type='submit' name='edit' value='Edit'></form></td>";

                echo "<td><form method='POST' action='delete_category.php'>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                    <input type='submit' name='delete' value='Delete'></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No categories found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>
</body>
</html>
