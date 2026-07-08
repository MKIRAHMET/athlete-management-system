<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../../../login.php");
    exit();
}

// Fetch event details based on event ID
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details from the database
    $sql_fetch_event = "SELECT * FROM events WHERE id = $event_id";
    $result_fetch_event = $conn->query($sql_fetch_event);
    if ($result_fetch_event->num_rows > 0) {
        $event = $result_fetch_event->fetch_assoc();
        // Check if the 'main_poster' key exists in the $event array
        if (isset($event['main_poster'])) {
            $main_poster = $event['main_poster'];
        } else {
            // Set a message indicating that the main poster doesn't exist
            $main_poster_message = "Main poster doesn't exist for this event.";
        }
    } else {
        echo "Event not found";
        exit();
    }
    $id = $event_id; // Set the $id variable
} else {
    echo "Event ID not provided";
    exit();
}


// Handle form submission for updating event details
if (isset($_POST['submit'])) {
    // Extract event details from the form
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $event_location = $conn->real_escape_string($_POST['event_location']);
    $footer_checked = isset($_POST['footer']) ? 1 : 0; // Check if checkbox is checked

    // Handle file uploads for main poster if a file is uploaded
    if (isset($_FILES['main_poster']) && $_FILES['main_poster']['error'] === 0) {
        $main_poster_file = $_FILES['main_poster']['tmp_name'];
        $main_poster_name = $_FILES['main_poster']['name'];

        // Move main poster to upload directory
        move_uploaded_file($main_poster_file, "../images/mainposter/$main_poster_name");

        // Update main poster path in the database
        $main_poster_path = "../images/mainposter/$main_poster_name";
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
                move_uploaded_file($event_photo_tmp_name, "$event_photos_directory/$event_photo_name");

                // Construct full path for event photo
                $event_photo_full_path = "$event_photos_directory/$event_photo_name";

                // Insert event photo path into the database
                $sql_insert_event_photo = "INSERT INTO event_photos (event_id, photo_path) VALUES (?, ?)";
                $stmt_insert_event_photo = $conn->prepare($sql_insert_event_photo);
                $stmt_insert_event_photo->bind_param("is", $event_id, $event_photo_full_path);
                $stmt_insert_event_photo->execute();
                $stmt_insert_event_photo->close();
            }
        }
    }
   // Handle fights data
if (isset($_POST['red_corner']) && isset($_POST['blue_corner']) && isset($_POST['style']) && isset($_POST['proam']) && isset($_POST['weightcategory']) && isset($_POST['round']) && isset($_POST['time']) && isset($_POST['way_of_win']) && isset($_POST['winner']) && isset($_POST['youtube_link'])) {
    // Prepare SQL statement for inserting fights
    $sql_insert_fight = "INSERT INTO fights (event_id, red_corner, blue_corner, style, proam, weightcategory, round, time, way_of_win, winner, referee, youtube_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_fight = $conn->prepare($sql_insert_fight);

    // Bind parameters and execute for each fight
    foreach ($_POST['red_corner'] as $key => $red_corner) {
        $stmt_insert_fight->bind_param("isssssssssss", $event_id, $_POST['red_corner'][$key], $_POST['blue_corner'][$key], $_POST['style'][$key], $_POST['proam'][$key], $_POST['weightcategory'][$key], $_POST['round'][$key], $_POST['time'][$key], $_POST['way_of_win'][$key], $_POST['winner'][$key], $_POST['referee'][$key], $_POST['youtube_link'][$key]);
        $stmt_insert_fight->execute();
    }

    // Close statement
    $stmt_insert_fight->close();
}

    // Update other event details in the events table
    $sql_update_event_details = "UPDATE events SET event_name = ?, event_date = ?, event_location = ?, footer_checked = ? WHERE id = ?";
    $stmt_update_event_details = $conn->prepare($sql_update_event_details);
    $stmt_update_event_details->bind_param("ssssi", $event_name, $event_date, $event_location, $footer_checked, $event_id);
    if ($stmt_update_event_details->execute()) {
        echo "Event details updated successfully";
    } else {
        echo "Error updating event details: " . $stmt_update_event_details->error;
    }
    $stmt_update_event_details->close();
    
}
// Handle form submission for adding a new fight
if (isset($_POST['add_fight'])) {
    // Extract fight details from the form
    $red_corners = $_POST['red_corner'];
    $blue_corners = $_POST['blue_corner'];
    $styles = $_POST['style'];
    $proams = $_POST['proam'];
    $weightcategories = $_POST['weightcategory'];
    $rounds = $_POST['round'];
    $times = $_POST['time'];
    $ways_of_win = $_POST['way_of_win'];
    $winners = $_POST['winner'];
    $referees = $_POST['referee'];
    $youtube_links = $_POST['youtube_link']; // Access YouTube links array

    // Prepare and execute SQL statement to insert the new fights into the database
    $sql_insert_fight = "INSERT INTO fights (event_id, red_corner, blue_corner, style, proam, weightcategory, round, time, way_of_win, winner, referee, youtube_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_fight = $conn->prepare($sql_insert_fight);

    // Loop through each fight and bind parameters
    foreach ($red_corners as $key => $red_corner) {
        $stmt_insert_fight->bind_param("isssssssssss", $event_id, $red_corner, $blue_corners[$key], $styles[$key], $proams[$key], $weightcategories[$key], $rounds[$key], $times[$key], $ways_of_win[$key], $winners[$key], $referees[$key], $youtube_links[$key]);
        if ($stmt_insert_fight->execute()) {
            echo "New fight added successfully.";
        } else {
            echo "Error adding new fight: " . $stmt_insert_fight->error;
        }
    }
    $stmt_insert_fight->close();
}

// Fetch styles from the database
$sqlStyles = "SELECT category_name FROM categories";
$resultStyles = $conn->query($sqlStyles);
$styles = [];
if ($resultStyles->num_rows > 0) {
    while ($row = $resultStyles->fetch_assoc()) {
        $styles[] = $row['category_name'];
    }
}

// Fetch weight categories from the database
$sqlWeightCategories = "SELECT category FROM category";
$resultWeightCategories = $conn->query($sqlWeightCategories);
$weightCategories = [];
if ($resultWeightCategories->num_rows > 0) {
    while ($row = $resultWeightCategories->fetch_assoc()) {
        $weightCategories[] = $row['category'];
    }
}

// Fetch referees from the database
$sqlreferee = "SELECT category_name FROM referees";
$resultreferee = $conn->query($sqlreferee);
$referee = []; // Initialize an empty array to store referee categories

if ($resultreferee->num_rows > 0) {
    // If there are rows returned by the query
    while ($row = $resultreferee->fetch_assoc()) {
        // Loop through each row and store the category_name in the $referee array
        $referee[] = $row['category_name'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ΔΙΑΧΕΙΡΗΣΗ EVENTS</title>
    <link rel="stylesheet" href="../essentials/admin.css">
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <!-- Include Dropzone.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <style>
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        grid-gap: 10px;
    }

    .photo-item {
        text-align: center;
    }

    .photo-item img {
        max-width: 100%;
        height: auto;
    }
</style>

</head>

<body>
<?php include '../essentials/adminmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">


    <h2>Edit Event</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" value="<?php echo $event['event_name']; ?>"><br><br>

        <label for="event_date">Event Date:</label><br>
        <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>"><br><br>

        <label for="event_location">Event Location:</label><br>
        <input type="text" id="event_location" name="event_location" value="<?php echo $event['event_location']; ?>"><br><br>

        <label for="main_poster">Main Poster:</label><br>
        <input type="file" id="main_poster" name="main_poster"><br><br>

        <label for="event_photos">Event Photos:</label><br>

        <input type="file" id="event_photos" name="event_photos[]" multiple><br><br>

        <input type="checkbox" id="footer" name="footer" <?php if ($event['footer_checked'] == 1) echo 'checked'; ?>>
        <label for="footer">Add to Footer</label><br><br>
        
   <!-- Container for dynamically added fights -->
   <div id="fights-container"></div>
            <button type="button" id="add-fight-btn">+ Add Fight</button>
        <input type="submit" name="submit" value="Update Event">
    </form>
    <form id="deleteMainPosterForm" action="delete_main_poster.php" method="post">
    <label for="main_poster">Main Poster:</label><br>
    <?php
    // Fetch main poster from the database
    $sql_fetch_main_poster = "SELECT main_poster FROM events WHERE id = $event_id";
    $result_fetch_main_poster = $conn->query($sql_fetch_main_poster);
    if ($result_fetch_main_poster->num_rows > 0) {
        while ($row = $result_fetch_main_poster->fetch_assoc()) {
            $main_poster_path = $row['main_poster'];
            echo '<img src="' . $main_poster_path . '" alt="MAIN POSTER" style="max-width: 200px;"><br>';
        }
    }
    ?>
<form id="deleteMainPosterForm" action="delete_main_poster.php" method="POST">
    <button type="button" onclick="confirmDelete()">Delete Poster</button>
    <input type="hidden" name="main_poster" value="<?php echo $main_poster; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Add the event ID -->
</form>



    <?php
// Fetch fights for the event from the database
$sql_fetch_fights = "SELECT * FROM fights WHERE event_id = ?";
$stmt_fetch_fights = $conn->prepare($sql_fetch_fights);
$stmt_fetch_fights->bind_param("i", $event_id);
$stmt_fetch_fights->execute();
$result_fights = $stmt_fetch_fights->get_result();

// Check if there are any fights for the event
if ($result_fights->num_rows > 0) {
    // Display the fights in a table
    echo "<h2>Edit Fights for Event: {$event['event_name']}</h2>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Red Corner</th>
            <th>Blue Corner</th>
            <th>Style</th>
            <th>Pro/Am</th>
            <th>Weight Category</th>
            <th>Round</th>
            <th>Time</th>
            <th>Way of Win</th>
            <th>Winner</th>
            <th>Action</th>
          </tr>";
    while ($fight = $result_fights->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$fight['red_corner']}</td>";
        echo "<td>{$fight['blue_corner']}</td>";
        echo "<td>{$fight['style']}</td>";
        echo "<td>{$fight['proam']}</td>";
        echo "<td>{$fight['weightcategory']}</td>";
        echo "<td>{$fight['round']}</td>";
        echo "<td>{$fight['time']}</td>";
        echo "<td>{$fight['way_of_win']}</td>";
        echo "<td>{$fight['winner']}</td>";
        echo "<td><a href='edit_fight.php?fight_id={$fight['id']}'>Edit</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // Display a message if no fights are found for the event
    echo "No fights found for this event.";
}

// Close the statement
$stmt_fetch_fights->close();
?>
</table>



<form id="eventPhotosForm" name="eventPhotosForm" method="POST" action="delete_selected_photos.php">
    <label for="event_photos">Event Photos:</label><br>

    <div class="photo-grid">
        <?php
        // Fetch event photos from the database
        $sql_fetch_event_photos = "SELECT id, photo_path FROM event_photos WHERE event_id = $event_id";
        $result_fetch_event_photos = $conn->query($sql_fetch_event_photos);
        if ($result_fetch_event_photos->num_rows > 0) {
            while ($row = $result_fetch_event_photos->fetch_assoc()) {
                ?>
                <div class="photo-item">
                    <img src="<?php echo $row['photo_path']; ?>" alt="Event Photo" style="max-width: 200px;"><br>
                    <input type="checkbox" name="selected_photos[]" value="<?php echo $row['id']; ?>">
                </div>
                <?php
            }
        }
        ?>
    </div>

    <button type="submit">Delete Selected Photos</button>
</form>

    </div>
             


    <script>
$(document).ready(function() {
    // Function to initialize autocomplete
    function initializeAutocomplete(inputField) {
        $(inputField).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "autocomplete.php",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1
        });
    }

    // Add event listener for dynamically added fights
    $(document).on("click", "#add-fight-btn", function() {
        var fightIndex = $(".fight").length; // Get the total number of fights
        var newFight = `
            <div class="fight" id="fight-${fightIndex}">
                <h3>Fight ${fightIndex + 1}</h3>
                <div class="form-group">
                    <label>Red Corner:</label>
                    <input type="text" name="red_corner[]" class="autocomplete" required>
                </div>
                <div class="form-group">
                    <label>Blue Corner:</label>
                    <input type="text" name="blue_corner[]" class="autocomplete" required>
                </div>
                Style:
                    <select name="style[]" required>
                        <?php
                            // Populate the dropdown with PHP data
                            foreach ($styles as $style) {
                                echo "<option value='$style'>$style</option>";
                            }
                        ?>
                    </select>                    
                    Pro/Am: 
                    <select name="proam[]" required>
                        <option value="PRO">PRO</option>
                        <option value="AMATEUR">AMATEUR</option>
                    </select>
                    <div class="form-row">
                        <div class="form-group">
                            Weight Category: 
                            <select name="weightcategory[]" required>
                                <?php
                                    // Populate the dropdown with PHP data
                                    foreach ($weightCategories as $weightCategory) {
                                        echo "<option value='$weightCategory'>$weightCategory</option>";
                                    }
                                ?>
                            </select>  
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            Round: 
                            <select name="round[]" required>
                                <option value="ROUND 1">ROUND 1</option>
                                <option value="ROUND 2">ROUND 2</option>
                                <option value="ROUND 3">ROUND 3</option>
                                <option value="ROUND 4">ROUND 4</option>
                                <option value="ROUND 5">ROUND 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            Time: <input type="text" name="time[]">
                        </div>
                        <div class="form-group">
                            Way of Win: <input type="text" name="way_of_win[]">
                        </div>
                        <div class="form-group">
                            Winner:
                            <select name="winner[]" id="winner-${fightIndex}" required>
                                <option value="">Select Winner</option>
                            </select>
                        </div>

                        <div class="form-group">
                <label for="youtube_link">YouTube Link:</label><br>
                <input type="text" name="youtube_link[]" class="youtube-link">
            </div>
                        Referee:
                        <select name="referee[]">
    <?php
    // Iterate through each referee category in the $referee array
    foreach ($referee as $category) {
        // Output an <option> element with the category as both the value and text
        echo "<option value='$category'>$category</option>";
    }
    ?>
</select>
 
                    </div>
                    <button type="button" class="remove-fight-btn">Remove Fight</button>
            </div>
        `;
        $("#fights-container").append(newFight);
        // Initialize autocomplete for newly added input fields
        initializeAutocomplete($("#fight-" + fightIndex + " .autocomplete"));
    });


    // Function to update winner dropdown options
    $(document).on("change", "input[name='red_corner[]'], input[name='blue_corner[]']", function() {
        var fightIndex = $(this).closest(".fight").index();
        updateWinnerOptions(fightIndex);
    });

    function updateWinnerOptions(fightIndex) {
        var redCornerValue = $(`#fight-${fightIndex} input[name="red_corner[]"]`).val().trim();
        var blueCornerValue = $(`#fight-${fightIndex} input[name="blue_corner[]"]`).val().trim();
        var winnerDropdown = $(`#winner-${fightIndex}`);

        // Clear existing options
        winnerDropdown.empty();

        // Add options based on red and blue corners
        if (redCornerValue !== "") {
            winnerDropdown.append(`<option value="${redCornerValue}">${redCornerValue}</option>`);
        }
        if (blueCornerValue !== "") {
            winnerDropdown.append(`<option value="${blueCornerValue}">${blueCornerValue}</option>`);
        }

        // Add "DRAW" and "NO CONTEST" options
        winnerDropdown.append('<option value="DRAW">DRAW</option>');
        winnerDropdown.append('<option value="NO CONTEST">NO CONTEST</option>');
    }

    // Initialize autocomplete for existing input fields
    initializeAutocomplete(".autocomplete");
    
});
  // Event listener to remove fights
  $(document).on("click", ".remove-fight-btn", function() {
        $(this).closest(".fight").remove();
    });

    function confirmDelete() {
    if (confirm("Are you sure you want to delete this poster?")) {
        document.getElementById("deleteMainPosterForm").submit();
    }
}
    </script>
</body>
</html>