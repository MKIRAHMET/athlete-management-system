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
    <title>ΕΠΕΞΕΡΓΑΣΙΑ ΑΝΑΚΟΙΝΩΣΗΣ</title>
    <script src="../../ckeditor/ckeditor.js"></script>
    
<link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">

        <h1>ΕΠΕΞΕΡΓΑΣΙΑ ΑΝΑΚΟΙΝΩΣΗΣ</h1>
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM announcements WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
        ?>
        <form action="update_announ.php" method="post">
    <!-- Form fields -->
    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <label for='title'>ΤΙΤΛΟΣ:</label><br>
    <input type='text' id='title' name='title' value='<?= $row["title"] ?>'><br>

    <label for='status'>ΚΑΤΑΣΤΑΣΗ:</label><br>
    <select id='status' name='status'>
        <option value='draft' <?= ($row['status'] == 'draft') ? 'selected' : '' ?>>ΠΡΟΧΕΙΡΟ</option>
        <option value='published' <?= ($row['status'] == 'published') ? 'selected' : '' ?>>ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
        <option value='unpublished' <?= ($row['status'] == 'unpublished') ? 'selected' : '' ?>>ΜΗ ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
    </select><br>

    <label for='publication_date'>ΗΜΕΡΟΜΗΝΙΑ ΔΗΜΟΣΙΕΥΣΗΣ:</label><br>
    <input type='date' id='publication_date' name='publication_date' value='<?= $row["publication_date"] ?>'><br>
               <label for="category">ΚΑΤΗΓΟΡΙΑ:</label><br>
    <select id="category" name="category">
        <?php
        $categoryQuery = "SELECT category_name FROM categories";
        $categoryResult = $conn->query($categoryQuery);

        if ($categoryResult->num_rows > 0) {
            while ($catRow = $categoryResult->fetch_assoc()) {
                echo "<option value='" . $catRow['category_name'] . "'";
                if ($row['category'] === $catRow['category_name']) {
                    echo " selected";
                }
                echo ">" . $catRow['category_name'] . "</option>";
            }
        }
        ?>
        <option value="other">Άλλο</option> <!-- Add the 'Other' option -->
    </select>
                
            <label for="new_category">Νέα Κατηγορία:</label><br>
            <input type="text" id="new_category" name="new_category" placeholder="Νέα Κατηγορία"><br>
            <!-- Add a textarea for CKEditor -->
            <label for="content">Περιεχόμενο:</label><br>
            <textarea name="content" id="content" rows="10" cols="80"><?= $row['content'] ?></textarea><br>                <!-- Other input fields -->
            <input type="submit" value="Save">
            </form>
        <?php
            } else {
                echo "ΔΕΝ ΒΡΕΘΗΚΑΝ ΑΝΑΚΟΙΝΩΣΕΙΣ.";
            }
            $stmt->close();
        } else {
            echo "Invalid article ID.";
        }
        $conn->close();
        ?>
        <script>
 CKEDITOR.replace( 'content', {
  height: 300,
  filebrowserUploadUrl: "upload_a.php"
 });
</script> 
    </div>
</body>
</html>
