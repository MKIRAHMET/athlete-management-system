<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ΕΠΕΞΕΡΓΑΣΙΑ Referee</title>
    <script src="https://thalatta.umc.gr/ckeditor/ckeditor.js"></script>

    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>

<div style="margin-left: 250px; padding: 20px;">
    <h1>ΕΠΕΞΕΡΓΑΣΙΑ Referee</h1>

    <?php
    // Check if 'id' parameter exists in the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        $category_name = ''; // Initialize $category_name to an empty string or appropriate default value
        // Prepare and execute the query to fetch category details
        $sql = "SELECT * FROM referees WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>
    
   <form action='update_referee.php' method='post'>
    
    <input type="hidden" name="id" value="<?= $id?>">
    <label for='category_name'>Referee Name:</label><br>
    <input type='text' id='category_name' name='category_name' value='<?= $row["category_name"] ?>'><br>
       <input type='submit' name='update' value='Update'>
</form>

    <?php
        } else {
            echo "ΔΕΝ ΒΡΕΘΗΚΑΝ Referees.";
        }
    } else {
        echo "Invalid category ID.";
    }
    ?>
</div>
</body>
</html>
