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
    <title>ΑΡΘΡΑ</title>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>

<div style="margin-left: 250px;">
    
    <?
    // Pagination variables
$results_per_page = 5; //number of shown announcements/articles
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $results_per_page;

// Query to retrieve total number of articles
$count_sql = "SELECT COUNT(*) AS total FROM articles";
$total_result = $conn->query($count_sql);
$total_row = $total_result->fetch_assoc();
$total_announcements = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_announcements / $results_per_page);
?>
    <h1>ΑΡΘΡΑ</h1>
    <a href="articleadmin.php" style="background-color: #3498db; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px margin-bottom: 10px; display: inline-block;">ΝΕΟ ΑΡΘΡΟ</a>

    <form action="?page=<?php echo $current_page; ?>" method="GET">
        <label for="sort">ΤΑΞΙΝΟΜΗΣΗ:</label>
        <select id="sort" name="sort">
            <option value="latest">ΝΕΩΤΕΡΑ</option>
            <option value="oldest">ΠΙΟ ΠΑΛΙΑ</option>
            <option value="title_asc">ΤΙΤΛΟΣ(A-Z)</option>
            <option value="title_desc">ΤΙΤΛΟΣ (Z-A)</option>
            <option value="published">ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
            <option value="draft">ΠΡΟΧΕΙΡΟ</option>
            <option value="unpublished">ΜΗ ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
            <!-- Add more options as needed -->
        </select>
        <input type="submit" value="ΤΑΞΙΝΟΜΗΣΗ">
    </form>

    <?php

    $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

    $sql = "SELECT id, title, publication_date, status FROM articles";

    // Adjust SQL query based on the selected sorting option
    switch ($sort_by) {
        case 'oldest':
            $sql .= " ORDER BY publication_date ASC";
            break;
        case 'title_asc':
            $sql .= " ORDER BY title ASC";
            break;
        case 'title_desc':
            $sql .= " ORDER BY title DESC";
            break;
        case 'published':
            $sql .= " WHERE status = 'published'";
            break;
        case 'draft':
            $sql .= " WHERE status = 'draft'";
            break;
        case 'unpublished':
            $sql .= " WHERE status = 'unpublished'";
            break;
        default:
            $sql .= " ORDER BY publication_date DESC"; // Default to latest
            break;
    }
    $sql .= " LIMIT $start_from, $results_per_page";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;'>";
            echo "<p style='font-size: 20px; margin-bottom: 8px;'><strong>Title:</strong> <a href='edit_article.php?id={$row["id"]}' style='text-decoration: none; color: #3498db; font-weight: bold;'>{$row["title"]}</a></p>";
            echo "<p style='margin-bottom: 5px;'><strong>Date:</strong> {$row["publication_date"]} <span style='margin-left: 20px;'><strong>Status:</strong> {$row["status"]}</span></p>";
            echo "</div>";
            echo "<p><a href='edit_article.php?id={$row["id"]}' style='background-color: #3498db; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-bottom: 10px; display: inline-block;'>ΕΠΕΞΕΡΓΑΣΙΑ</a>";
            echo "<a href='delete_article.php?id={$row["id"]}' style='background-color: #e74c3c; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Delete</a></p>";
            echo "<hr>";
        }
    } else {
        echo "ΔΕΝ ΒΡΕΘΗΚΑΝ ΑΡΘΡΑ.";
    }

    $conn->close();
    ?>
    
    <div class='pagination'>
        <?php
        $sort_param = isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '';
    
        for ($page = 1; $page <= $total_pages; $page++) {
            $class = $page == $current_page ? 'current' : '';
            echo "<a href='?page=$page$sort_param' class='$class'>$page</a>";
        }
        ?>
    </div>
    
</body>
</html>