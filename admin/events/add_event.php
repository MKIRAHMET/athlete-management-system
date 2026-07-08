<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
</head>
<body>
    <?php include '../essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">
        <h2>ΠΡΟΣΘΗΚΗ ΝΕΟΥ EVENT</h2>
        <form action="submit_event.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            ΟΝΟΜΑ EVENT: <input type="text" name="event_name" required>
            <br><br>
            ΗΜΕΡΟΜΗΝΙΑ: <input type="date" name="event_date" required>
            <br><br>
            ΤΟΠΟΘΕΣΙΑ: <input type="text" name="event_location" required>
            <br><br>
            <label>
                Εμφάνιση στο footer:
                <input type="checkbox" name="footer" value="1">
            </label>
            <br><br>
            Αφίσα Event: <input type="file" name="main_poster" accept="image/*">

            <br><br>
            Φωτογραφίες Event: <input type="file" name="event_photos[]" accept="image/*" multiple>
            <br><br>

            <!-- Container for dynamically added fights -->
            <div id="fights-container"></div>
            <button type="button" id="add-fight-btn">+ Add Fight</button>

            <br><br>
            <input type="submit" value="Προσθήκη Event" name="submit">
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
</script>
</body>
</html>
