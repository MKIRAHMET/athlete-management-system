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

$videoPath = $_GET['video_path'] ?? '';

// Use $videoPath to perform operations or retrieve the video details

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    
    // Fetch video details based on filename
    $sql = "SELECT * FROM videos WHERE video_path = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result = $stmt->get_result();
    $video = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ΕΠΕΞΕΡΓΑΣΙΑ ΒΙΝΤΕΟ</title>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">

    <h2>Video Player</h2>
    <video controls>
    <source src="<?php echo '../../' . $video['video_path']; ?>">
    Your browser does not support the video tag.
</video>

    <h2>ΕΠΕΞΕΡΓΑΣΙΑ ΒΙΝΤΕΟ</h2>
    <form action="update_video.php" method="POST">
        <input type="hidden" name="filename" value="<?php echo $filename; ?>">
        TΙΤΛΟΣ: <input type="text" name="title" value="<?php echo $video['title']; ?>">
        ΠΕΡΙΓΡΑΦΗ: <textarea name="description"><?php echo $video['description']; ?></textarea>
        <input type="submit" value="ΑΠΟΘΗΚΕΥΣΗ" name="submit">
    </form>
</body>
</html>

