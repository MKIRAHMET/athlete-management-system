<?php
include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if fight ID is provided
if (!isset($_GET['fight_id'])) {
    echo "Fight ID not provided";
    exit;
}

$fight_id = $_GET['fight_id'];

// Fetch fight data from the database
$sql_fight = "SELECT * FROM fights WHERE id = ?";
$stmt = $conn->prepare($sql_fight);
$stmt->bind_param("i", $fight_id);
$stmt->execute();
$result_fight = $stmt->get_result();

if ($result_fight === false) {
    echo "Error fetching fight data: " . $conn->error;
    exit;
}

if ($result_fight->num_rows == 0) {
    echo "Fight not found";
    exit;
}

$fight = $result_fight->fetch_assoc();

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
$sqlReferees = "SELECT category_name FROM referees";
$resultReferees = $conn->query($sqlReferees);
$referees = [];
if ($resultReferees->num_rows > 0) {
    while ($row = $resultReferees->fetch_assoc()) {
        $referees[] = $row['category_name'];
    }
}

// Handle form submission for updating fight
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get fight data from the form
    $red_corner = $_POST['red_corner'][0];
    $blue_corner = $_POST['blue_corner'][0];    
    $style = $_POST['style'];
    $proam = $_POST['proam'];
    $weightcategory = $_POST['weightcategory'];
    $round = $_POST['round'];
    $time = $_POST['time'];
    $way_of_win = $_POST['way_of_win'];
    $winner = $_POST['winner'];
    $referee = $_POST['referee'];
    $youtube_link = isset($_POST['youtube_link']) ? $_POST['youtube_link'] : '';


    // Update the fight in the database
$sql_update = "UPDATE fights SET red_corner=?, blue_corner=?, style=?, proam=?, weightcategory=?, round=?, time=?, way_of_win=?, winner=?, referee=?, youtube_link=? WHERE id=?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sssssssssssi", $red_corner, $blue_corner, $style, $proam, $weightcategory, $round, $time, $way_of_win, $winner, $referee, $youtube_link, $fight_id);

    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?fight_id=$fight_id");
        } else {
        echo "Error updating fight: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Fight</title>
    <link rel="stylesheet" href="../essentials/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
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
    
    // Initialize autocomplete for input fields with class 'autocomplete'
    initializeAutocomplete(".autocomplete");
});
</script>

</head>
<body>
    <?php include '../essentials/adminmenu.php'; ?>
    <div style="margin-left: 250px; padding: 20px;">
    <h2>Edit Fight</h2>
        <form action="" method="POST">
            <input type="hidden" name="fight_id" value="<?php echo $fight['id']; ?>">
            <label>Red Corner:</label>
            <input type="text" name="red_corner[]" class="autocomplete" value="<?php echo $fight['red_corner']; ?>" required>
            <br><br>
            <label>Blue Corner:</label>
            <input type="text" name="blue_corner[]" class="autocomplete" value="<?php echo $fight['blue_corner']; ?>" required>
            <br><br>
            <label>Style:</label>
            <select name="style" required>
                <?php foreach ($styles as $styleOption): ?>
                    <option value="<?php echo $styleOption; ?>" <?php if ($fight['style'] == $styleOption) echo "selected"; ?>><?php echo $styleOption; ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Pro/Am:</label>
            <select name="proam" required>
                <option value="PRO" <?php if ($fight['proam'] == 'PRO') echo "selected"; ?>>PRO</option>
                <option value="AMATEUR" <?php if ($fight['proam'] == 'AMATEUR') echo "selected"; ?>>AMATEUR</option>
            </select>
            <br><br>
            <label>Weight Category:</label>
            <select name="weightcategory" required>
                <?php foreach ($weightCategories as $categoryOption): ?>
                    <option value="<?php echo $categoryOption; ?>" <?php if ($fight['weightcategory'] == $categoryOption) echo "selected"; ?>><?php echo $categoryOption; ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Round:</label>
            <select name="round" required>
                <?php
                // Populate the dropdown with PHP data
                $rounds = array("ROUND 1", "ROUND 2", "ROUND 3", "ROUND 4", "ROUND 5");
                foreach ($rounds as $roundOption) {
                    $selected = ($fight['round'] == $roundOption) ? "selected" : "";
                    echo "<option value='$roundOption' $selected>$roundOption</option>";
                }
                ?>
            </select>
            <br><br>
            <label>Time:</label>
            <input type="text" name="time" value="<?php echo $fight['time']; ?>" required>
            <br><br>
            <label>Way of Win:</label>
            <input type="text" name="way_of_win" value="<?php echo $fight['way_of_win']; ?>" required>
            <br><br>
            
            <div class="form-group">
            <label>Winner:</label>
<input type="text" name="winner" value="<?php echo $fight['winner']; ?>" required>
<br><br>               
            </div>
            
            <div class="form-group">
            <label for="youtube_link">YouTube Link:</label><br>
<input type="text" name="youtube_link" value="<?php echo $fight['youtube_link']; ?>">
            </div>
            <label>Referee:</label>
            <select name="referee" required>
                <?php foreach ($referees as $refereeOption): ?>
                    <option value="<?php echo $refereeOption; ?>" <?php if ($fight['referee'] == $refereeOption) echo "selected"; ?>><?php echo $refereeOption; ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <input type="submit" value="Update Fight">
        </form>
        
    </div>
    
    <script>


           // Initialize autocomplete for input fields with class 'autocomplete'
           initializeAutocomplete(".autocomplete");
        });
    </script>
</body>
</html>
