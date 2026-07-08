<?php
include '../db.php'; // Include your database connection script
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Check if the request method is GET and filename is set
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filename'])) {
    $filename = $_GET['filename'];

    // Fetch image data based on filename
    $sql = "SELECT * FROM images WHERE image_path = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $imageData = $result->fetch_assoc();
        // Display image details or edit form for the image based on $imageData
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>ΕΠΕΞΕΡΓΑΣΙΑ ΦΩΤΟΓΡΑΦΙΑΣ</title>
            <link rel="stylesheet" href="../essentials/admin.css">
        </head>
        <body>
            <?php include '../essentials/adminmenu.php'; ?>
            <div style="margin-left: 250px; padding: 20px;">
                <?php
                // Check if image data is available
                if ($imageData) {
                    // Adjust the image path to move it one directory level above
                    $imagePath = '../../' . $imageData['image_path']; // Prepending '../' to go one directory above
                    ?>
                    <h2>ΕΠΕΞΕΡΓΑΣΙΑ ΦΩΤΟΓΡΑΦΙΑΣ</h2>
                    <img src="<?php echo $imagePath; ?>" class="image" alt="Image">

                    <form action="update_image.php" method="POST">
                        <div class="form-group">
                            <label for="title">ΤΙΤΛΟΣ:</label>
                            <input type="text" id="title" name="title" value="<?php echo $imageData['title']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">ΠΕΡΙΓΡΑΦΗ:</label>
                            <textarea id="description" name="description"><?php echo $imageData['description']; ?></textarea>
                        </div>
                        <input type="hidden" name="filename" value="<?php echo $filename; ?>">
                        <input type="submit" value="ΑΠΟΘΗΚΕΥΣΗ">
                    </form>

                    <!-- Form for deleting the image -->
                    <form action="delete_image.php" method="GET">
                        <input type="hidden" name="filename" value="<?php echo $filename; ?>">
                        <input type="submit" value="ΔΙΑΓΡΑΦΗ ΦΩΤΟΓΡΑΦΙΑΣ">
                    </form>
                    </div>
                </body>
                </html>
                <?php
            } else {
                echo "Η ΦΩΤΟΓΡΑΦΙΑ ΔΕΝ ΒΡΕΘΗΚΕ";
            }
            $stmt->close();
        } else {
            echo "Invalid request.";
        }
    }
    ?>
