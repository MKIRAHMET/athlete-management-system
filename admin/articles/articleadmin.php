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
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ΕΓΓΡΑΦΗ ΑΡΘΡΟΥ</title>
<script src="../../ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">
<h1>ΚΑΙΝΟΥΡΙΟ ΑΡΘΡΟ</h1>

<form action="save_article.php" method="post">
	<label> Content </label> <br>
	<label for="title">Title:</label><br>
    <input type="text" id="title" name="title"><br> 
	<textarea id="content" name="content" rows="5" cols="80"></textarea><br>
 

    
    <label for="status">Status:</label><br>
    <select id="status" name="status">
      <option value="draft">ΠΡΟΧΕΙΡΟ</option>
      <option value="published">ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
      <option value="unpublished">ΜΗ ΔΗΜΟΣΙΕΥΜΕΝΟ</option>
    </select><br>

    <label for="publication_date">Publication Date:</label><br>
    <input type="date" id="publication_date" name="publication_date"><br>

    <label for="category">ΚΑΤΗΓΟΡΙΑ:</label><br>
                <select id="category" name="category">
                    <?php
                    $categoryQuery = "SELECT category_name FROM categories";
                    $categoryResult = $conn->query($categoryQuery);

                    if ($categoryResult->num_rows > 0) {
                        while ($catRow = $categoryResult->fetch_assoc()) {
                            echo "<option value='" . $catRow['category_name'] . "'>" . $catRow['category_name'] . "</option>";
                        }
                    }
                    ?>
                    <option value="other">Other</option>
                </select>
                
                <input type="text" id="new_category" name="new_category" placeholder="New Category"><br>

    <label for="tags">Tags:</label><br>
    <input type="text" id="tags" name="tags"><br>

    <label for="image_url">Image URL:</label><br>
    <input type="text" id="image_url" name="image_url"><br>

    <input type="submit" value="Submit">
  </form>

 
 <script>
 CKEDITOR.replace( 'content', {
  height: 300,
  filebrowserUploadUrl: "upload.php"
 });
</script>
</form>
</body>
</html>