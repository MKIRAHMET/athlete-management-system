<?php
include '../db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../../../login.php");
    exit();
}

// Handle form submission for adding a new event
if (isset($_POST['submit']) && $_POST['action'] === 'add') {
    // Extract event details from the form
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $event_location = $conn->real_escape_string($_POST['event_location']);
    $footer_checked = isset($_POST['footer']) ? 1 : 0; // Check if checkbox is checked

    // Handle file uploads for main poster if a file is uploaded
    $main_poster_name = ""; // Default to empty string
    if (isset($_FILES['main_poster']) && $_FILES['main_poster']['error'] === 0) {
        $main_poster_file = $_FILES['main_poster']['tmp_name'];
        $main_poster_name = $_FILES['main_poster']['name'];

        // Move main poster to upload directory
        if (move_uploaded_file($main_poster_file, "../images/mainposter/$main_poster_name")) {
            // Insert main poster path into the database if upload was successful
            $main_poster_path = "../images/mainposter/$main_poster_name";
        } else {
            echo "Error uploading main poster.";
            exit();
        }
    }

    // Insert event data into the events table using prepared statement
    $sql_insert_event = "INSERT INTO events (event_name, event_date, event_location, footer_checked, main_poster) VALUES (?, ?, ?, ?, ?)";
    $stmt_event = $conn->prepare($sql_insert_event);
    $stmt_event->bind_param("sssib", $event_name, $event_date, $event_location, $footer_checked, $main_poster_name);

    // Execute the prepared statement to insert event data
    if ($stmt_event->execute()) {
        // Get the ID of the inserted event
        $event_id = $conn->insert_id;

        // If a main poster was uploaded, update the main poster path in the database
        if (!empty($main_poster_path)) {
            $sql_update_main_poster_path = "UPDATE events SET main_poster = ? WHERE id = ?";
            $stmt_update_main_poster_path = $conn->prepare($sql_update_main_poster_path);
            $stmt_update_main_poster_path->bind_param("si", $main_poster_path, $event_id);
            $stmt_update_main_poster_path->execute();
            $stmt_update_main_poster_path->close();
        }

        // Handle file uploads for event photos
        if (!empty($_FILES['event_photos'])) {
            // Create directory for event photos if it doesn't exist
            $event_photos_directory = "../images/eventphotos/$event_name";
            if (!file_exists($event_photos_directory)) {
                mkdir($event_photos_directory, 0777, true); // Create directory recursively
            }

            foreach ($_FILES['event_photos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['event_photos']['error'][$key] === 0) {
                    $event_photo_name = $_FILES['event_photos']['name'][$key];
                    $event_photo_tmp_name = $_FILES['event_photos']['tmp_name'][$key];
                    
                    // Move event photo to event photos directory
                    if (move_uploaded_file($event_photo_tmp_name, "$event_photos_directory/$event_photo_name")) {
                        // Construct full path for event photo
                        $event_photo_full_path = "$event_photos_directory/$event_photo_name";

                        // Insert event photo path into the database
                        $sql_insert_event_photo = "INSERT INTO event_photos (event_id, photo_path) VALUES (?, ?)";
                        $stmt_insert_event_photo = $conn->prepare($sql_insert_event_photo);
                        $stmt_insert_event_photo->bind_param("is", $event_id, $event_photo_full_path);
                        $stmt_insert_event_photo->execute();
                        $stmt_insert_event_photo->close();
                    } else {
                        echo "Error uploading event photo: $event_photo_name";
                        exit();
                    }
                }
            }
        }

        // Insert each fight associated with the event into the fights table using prepared statement
        if (isset($_POST['red_corner'], $_POST['blue_corner'], $_POST['style'], $_POST['proam'], $_POST['weightcategory'], $_POST['round'], $_POST['time'], $_POST['way_of_win'], $_POST['winner'])) {
            $stmt_fight = $conn->prepare("INSERT INTO fights (event_id, red_corner, blue_corner, style, proam, weightcategory, round, time, way_of_win, winner, referee, youtube_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($_POST['red_corner'] as $key => $value) {
                $red_corner = $conn->real_escape_string($_POST['red_corner'][$key]);
                $blue_corner = $conn->real_escape_string($_POST['blue_corner'][$key]);
                $style = $conn->real_escape_string($_POST['style'][$key]);
                $proam = $conn->real_escape_string($_POST['proam'][$key]);
                $weightcategory = $conn->real_escape_string($_POST['weightcategory'][$key]);
                $round = $conn->real_escape_string($_POST['round'][$key]);
                $time = $conn->real_escape_string($_POST['time'][$key]);

                $way_of_win = $conn->real_escape_string($_POST['way_of_win'][$key]);
                $winner = $conn->real_escape_string($_POST['winner'][$key]);
                $referee = $conn->real_escape_string($_POST['referee'][$key]);
                $youtube_link = isset($_POST['youtube_link'][$key]) ? $conn->real_escape_string($_POST['youtube_link'][$key]) : '';
                
                $stmt_fight->bind_param("isssssssssss", $event_id, $red_corner, $blue_corner, $style, $proam, $weightcategory, $round, $time, $way_of_win, $winner, $referee, $youtube_link);

                if (!$stmt_fight->execute()) {
                    echo "Error inserting fight: " . $stmt_fight->error;
                }
            }
            $stmt_fight->close();
        }

        // Redirect to events.php after successful submission
        header("Location: events.php");
        exit(); // Make sure to exit after redirection
    } else {
        echo "Error inserting event: " . $stmt_event->error;
    }
    $stmt_event->close(); // Close the prepared statement
}
?>
