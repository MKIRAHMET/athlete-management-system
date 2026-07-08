<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $description = $_POST['description'];
    $filename = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    $folder = "../images/banner/";

    // Move uploaded image to destination folder
    if (move_uploaded_file($temp_name, $folder.$filename)) {
        // Check if there are any existing banners
        $sql_count = "SELECT COUNT(*) AS count FROM banners";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $banner_count = $row_count['count'];

        if ($banner_count == 0) {
            // Insert new banner into the database
            $sql_insert = "INSERT INTO banners (filename, description) VALUES ('$filename', '$description')";
            
            if ($conn->query($sql_insert) === TRUE) {
                echo "Banner added successfully";
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            // Update existing banner (you can use the same UPDATE query as before)
            $sql_update = "UPDATE banners SET filename = '$filename', description = '$description'";
            
            if ($conn->query($sql_update) === TRUE) {
                echo "Banner updated successfully";
            } else {
                echo "Error: " . $sql_update . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Error moving uploaded file.";
    }
}

// Fetch the latest banner data from the database
$sql = "SELECT * FROM banners ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$description = $row['description']; // Retrieve the description for pre-filling the form
?>

<!DOCTYPE html>
<html>
<head>
    <title>ΔΙΑΧΕΙΡΗΣΗ BANNER</title>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
    <?php include '../essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">
        <h2>ΑΝΕΒΑΣΕ BANNER</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <br><br>
            ΤΙΤΛΟΣ: <input type="text" name="title">
            <br><br>
            ΠΕΡΙΓΡΑΦΗ: <textarea name="description"><?php echo $description; ?></textarea>
            <br><br>
            <input type="submit" value="Upload Image" name="submit">
        </form>

        <!-- Display existing banner -->
        <div class="container">
            <?php
            if ($result->num_rows > 0) {
                $imagePath = 'images/banner/' . $row['filename']; // Adjusted path
                ?>
                <div class="image">
                    <img src="<?php echo $imagePath; ?>" class="image" alt="Image">
                </div>
            <?php
            } else {
                echo "Δεν βρέθηκε banner";
            }
            ?>
        </div>
    </div>
</body>
</html>
