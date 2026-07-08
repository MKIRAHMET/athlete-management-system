<?php
include '../db.php'; // Include your database connection script
session_start();


// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Handle form submission for adding or editing an event
if (isset($_POST['submit'])) {
    if ($_POST['action'] === 'add') {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_location = $_POST['event_location'];
        $footer_checked = isset($_POST['footer']) ? 1 : 0; // Check if checkbox is checked

        // Insert event data into the events table
        $sql_insert = "INSERT INTO events (event_name, event_date, event_location, footer_checked) VALUES ('$event_name', '$event_date', '$event_location', '$footer_checked')";
        
        if ($conn->query($sql_insert) === TRUE) {
            echo "Event added successfully";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    } elseif ($_POST['action'] === 'edit') {
        $event_id = $_POST['event_id'];
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_location = $_POST['event_location'];
        $footer_checked = isset($_POST['footer']) ? 1 : 0; // Check if checkbox is checked

        // Update event data in the events table
        $sql_update = "UPDATE events SET event_name='$event_name', event_date='$event_date', event_location='$event_location', footer_checked='$footer_checked' WHERE id=$event_id";
        
        if ($conn->query($sql_update) === TRUE) {
            echo "Event updated successfully";
        } else {
            echo "Error: " . $sql_update . "<br>" . $conn->error;
        }
    }
}

// Handle event deletion
if (isset($_POST['delete'])) {
    $event_id = $_POST['event_id'];
        
    // Fetch associated event details
    $sql_fetch_event_details = "SELECT main_poster FROM events WHERE id = $event_id";
    $result_fetch_event_details = $conn->query($sql_fetch_event_details);
    if ($result_fetch_event_details->num_rows > 0) {
        $row = $result_fetch_event_details->fetch_assoc();
        $main_poster_path = $row['main_poster'];

        // Delete main poster file if it exists
        if (file_exists($main_poster_path) && is_file($main_poster_path)) {
            if (unlink($main_poster_path)) {
                echo "Main poster file deleted successfully";
            } else {
                echo "Error deleting main poster file";
            }
        } else {
            echo "Main poster file not found";
        }
    }

    // Fetch associated event photos directory
    $sql_fetch_photos_directory = "SELECT event_name FROM events WHERE id = $event_id";
    $result_fetch_photos_directory = $conn->query($sql_fetch_photos_directory);
    if ($result_fetch_photos_directory->num_rows > 0) {
        $row = $result_fetch_photos_directory->fetch_assoc();
        $event_photos_directory = "../images/eventphotos/" . $row['event_name'];
        
        // Check if the directory exists and is not empty
        if (file_exists($event_photos_directory) && is_dir($event_photos_directory)) {
            // Delete the directory and its contents recursively
            $deleted = deleteDirectory($event_photos_directory);
            if ($deleted) {
                echo "Event photos directory deleted successfully";
            } else {
                echo "Error deleting event photos directory";
            }
        }
    }

    // Delete event from the events table
    $sql_delete_event = "DELETE FROM events WHERE id=$event_id";
    if ($conn->query($sql_delete_event) === TRUE) {
        echo "Event deleted successfully";
    } else {
        echo "Error deleting event: " . $conn->error;
    }
}

// Function to delete directory and its contents recursively
function deleteDirectory($dir) {
    if (!file_exists($dir) || !is_dir($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteDirectory("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}


// Fetch existing events from the database
$sql_select = "SELECT * FROM events";
$result = $conn->query($sql_select);
?>

<!DOCTYPE html>
<html>
<head>
    <title>ΔΙΑΧΕΙΡΗΣΗ EVENTS</title>
    <link rel="stylesheet" href="../essentials/admin.css">
</head>
<body>
    <?php include '../essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">
        <h2>ΠΡΟΣΘΗΚΗ ΝΕΟΥ EVENT</h2>
        <a href="add_event.php" class="redirect-button upload-button">ADD EVENT</a>


        <!-- Display existing events -->
        <h2>ΥΠΑΡΧΟΝΤΑ EVENTS</h2>
        <table border="1">
            <tr>
                <th>Όνομα Event</th>
                <th>Ημερομηνία</th>
                <th>Τοποθεσία</th>
                <th>Εμφάνιση στο Footer</th>
                <th>Επεξεργασία</th>
                <th>Διαγραφή</th>
            </tr>
            <?php
            // Iterate over each row in the result set
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['event_name'] . "</td>";
                echo "<td>" . $row['event_date'] . "</td>";
                echo "<td>" . $row['event_location'] . "</td>";
                echo "<td>" . ($row['footer_checked'] == 1 ? 'Ναι' : 'Όχι') . "</td>";
                echo "<td><a href='edit_event.php?id=" . $row['id'] . "'>Επεξεργασία</a></td>";
                echo "<td>
                <form action='' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this event?\");'>
                    <input type='hidden' name='event_id' value='" . $row['id'] . "'>
                    <input type='submit' value='Διαγραφή' name='delete'>
                </form>
            </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
