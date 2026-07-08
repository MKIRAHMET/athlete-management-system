<?php
include 'db.php'; // Include your database connection script
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../login.php");
    exit();
}
$funnyMessages = array(
    "UMC WEBMASTER",

     );


// Function to get a random message
function getRandomFunnyMessage($messages) {
    return $messages[array_rand($messages)];
}

// Get a random message
$randomMessage = getRandomFunnyMessage($funnyMessages);

// Fetch articles data
$articles_query = "SELECT title, publication_date FROM articles ORDER BY publication_date DESC";
$articles_result = $conn->query($articles_query);
$articles = [];
while ($row = $articles_result->fetch_assoc()) {
    $articles[] = $row;
}

// Fetch announcements data
$announcements_query = "SELECT title, publication_date FROM announcements ORDER BY publication_date DESC";
$announcements_result = $conn->query($announcements_query);
$announcements = [];
while ($row = $announcements_result->fetch_assoc()) {
    $announcements[] = $row;
}

// Fetch videos data
$videos_query = "SELECT video_path, created_at FROM videos ORDER BY created_at DESC";
$videos_result = $conn->query($videos_query);
$videos = [];
while ($row = $videos_result->fetch_assoc()) {
    $videos[] = $row;
}

// Fetch photos data
$photos_query = "SELECT image_path, created_at FROM images ORDER BY created_at DESC";
$photos_result = $conn->query($photos_query);
$photos = [];
while ($row = $photos_result->fetch_assoc()) {
    $photos[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="essentials/admin.css">
</head>
<body>
    <?php include 'essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">
        <p><?php echo $randomMessage; ?></p>
        Διαλεξε τι θες να κανεις..
        <h2>Displaying Data</h2>

<table>
  <tr>
    <th>ΤΙΤΛΟΣ ΑΝΑΚΟΙΝΩΣΗΣ</th>
    <th>ΗΜΕΡΟΜΗΝΙΑ ΑΝΑΚΟΙΝΩΣΕΩΝ</th>
    <th>ΤΙΤΛΟΣ ΑΡΘΡΟΥ</th>
    <th>ΗΜΕΡΟΜΗΝΙΑ ΑΡΘΡΟΥ</th>
    <th>Videos</th>
    <th>Photos</th>
  </tr>

  <?php
// Display maximum ten items from each category
for ($i = 0; $i < 10; $i++) {
    echo "<tr>";

    // Display Announcements
    echo "<td>" . ($i < count($announcements) ? $announcements[$i]['title'] : '') . "</td>";
    echo "<td>" . ($i < count($announcements) ? $announcements[$i]['publication_date'] : '') . "</td>";

    // Display Articles
    echo "<td>" . ($i < count($articles) ? $articles[$i]['title'] : '') . "</td>";
    echo "<td>" . ($i < count($articles) ? $articles[$i]['publication_date'] : '') . "</td>";

    // Display Videos
    echo "<td style='width: 300px; height: 200px;'>"; // Adjust width and height as needed
    if ($i < count($videos)) {
        $videoPath = "../" . $videos[$i]['video_path']; // Adjust the path here
        echo "<video width='100%' height='100%' controls><source src='" . $videoPath . "'></video>";
    }
    echo "</td>";

    // Display Photos
    echo "<td style='width: 300px; height: 200px;'>"; // Adjust width and height as needed
    if ($i < count($photos)) {
        $imagePath = "../" . $photos[$i]['image_path']; // Adjust the path here
        echo "<img src='" . $imagePath . "' alt='Photo' style='width: 100%; height: 100%; object-fit: cover;'>";
    }
    echo "</td>";

    echo "</tr>";
}
?>

</table>

</body>
</html>

    