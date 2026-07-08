<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('admin/includes/config.php');
include('dbh.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['login']);
}

// Check if the user is not logged in
if (!isLoggedIn()) {
    // Redirect to the login page if not logged in
    header("Location: coach.php");
    exit(); // Stop further execution of the page
}

// Check if athlete ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect or display error message
    header("Location: coach_dashboard.php");
    exit();
}

// Retrieve coach ID from session
$coach_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Retrieve athlete ID from the URL
$athlete_id = $_GET['id'];

// Fetch athlete data for the logged-in coach
$sql = "SELECT * FROM athlete_requests WHERE id = :athlete_id AND coach_id = :coach_id";
$query = $dbh->prepare($sql);
$query->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
$query->bindParam(':coach_id', $coach_id, PDO::PARAM_INT);
$query->execute();
$athlete = $query->fetch(PDO::FETCH_ASSOC);


// Check if athlete exists and belongs to the logged-in coach
if (!$athlete) {
    // Redirect or display error message
    header("Location: coach_dashboard.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $athleteName = $_POST['AthleteName'];
    $nickname = $_POST['Nickname'];
    $birthdate = $_POST['BirthdateYear'] . '-' . $_POST['BirthdateMonth'] . '-' . $_POST['BirthdateDay'];
    $birthplace = $_POST['Birthplace'];
    $gym = $_POST['Gym'];
    $country = $_POST['Country'];
    $funFacts = $_POST['FunFacts'];
    $image = $_FILES["AthleteImage"]["name"];
    $instagram = $_POST['Instagram'];
    $facebook = $_POST['Facebook'];
    $twitter = $_POST['twitter'];
    $tiktok = $_POST['TikTok'];
    $tapology = $_POST['Tapology'];
    $sherdog = $_POST['Sherdog'];
    $record = $_POST['Record'];
    $category = $_POST['Category'];
    $sex = $_POST['Sex'];
    $proam = $_POST['ProAm'];
    // Move uploaded image to desired directory
    move_uploaded_file($_FILES["AthleteImage"]["tmp_name"], "admin/images/athletes/" . $_FILES["AthleteImage"]["name"]);

    // Update athlete information in the database
    $sql = "UPDATE athlete_requests             SET athleteName = :athleteName, 
    nickname = :nickname, 
    birthdate = :birthdate, 
    birthplace = :birthplace, 
    gym = :gym, 
    country = :country, 
    funFacts = :funFacts, 
    image = :image, 
    instagram = :instagram, 
    facebook = :facebook, 
    twitter = :twitter,
    tiktok = :tiktok, 
    tapology = :tapology, 
    sherdog = :sherdog, 
    record = :record, 
    proam = :proam
WHERE id = :aid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':athleteName', $athleteName, PDO::PARAM_STR);
    $query->bindParam(':nickname', $nickname, PDO::PARAM_STR);
    $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    $query->bindParam(':birthplace', $birthplace, PDO::PARAM_STR);
    $query->bindParam(':gym', $gym, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':funFacts', $funFacts, PDO::PARAM_STR);
    $query->bindParam(':image', $image, PDO::PARAM_STR);
    $query->bindParam(':instagram', $instagram, PDO::PARAM_STR);
    $query->bindParam(':facebook', $facebook, PDO::PARAM_STR);
    $query->bindParam(':twitter', $twitter, PDO::PARAM_STR);
    $query->bindParam(':tiktok', $tiktok, PDO::PARAM_STR);
    $query->bindParam(':tapology', $tapology, PDO::PARAM_STR); // Bind Tapology field
    $query->bindParam(':sherdog', $sherdog, PDO::PARAM_STR); // Bind Sherdog field
    $query->bindParam(':record', $record, PDO::PARAM_STR); // Bind Record field
    $query->bindParam(':category', $category, PDO::PARAM_STR); // Bind category field
    $query->bindParam(':sex', $sex, PDO::PARAM_STR); // Bind Sex field
    $query->bindParam(':proam', $proam, PDO::PARAM_STR); // Updated to use $proam.
    $query->execute();

    // Redirect to the dashboard page after updating
    header("Location: coach_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Athlete</title>
    <link href="css/coach.css" rel='stylesheet' type='text/css' />

</head>
<body>
<?php include 'coachmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">

<h3>Update Athlete</h3>
                    <?php if($error){ ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
                    else if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form class="form-horizontal" name="athleteForm" method="post" enctype="multipart/form-data">
                                <!-- Hidden input to store athleteId -->
                                <input type="hidden" name="id" value="<?php echo $athlete['id']; ?>">
                                <!-- Hidden input to store existing image -->
                                <input type="hidden" name="OldImage" value="<?php echo $athlete['image']; ?>">
                                <!-- Ονομα Αθλητη -->
                                <div class="form-group">
                                    <label for="AthleteName" class="col-sm-2 control-label">Ονομα Αθλητη</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="AthleteName" id="AthleteName" placeholder="Ονομα Αθλητη" value="<?php echo $athlete['athleteName']; ?>" required>
                                    </div>
                                </div>
                                <!-- Nickname -->
                                <div class="form-group">
                                    <label for="Nickname" class="col-sm-2 control-label">Nickname</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Nickname" id="Nickname" placeholder="Nickname" value="<?php echo $athlete['nickname']; ?>" >
                                    </div>
                                </div>
                                <!-- Sex -->
<div class="form-group">
    <label for="Sex" class="col-sm-2 control-label">Sex</label>
    <div class="col-sm-8">
        <select class="form-control1" name="Sex" id="Sex" required>
            <option value="">Select Sex</option>
            <option value="Male" <?php if ($athlete['sex'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($athlete['sex'] == 'Female') echo 'selected'; ?>>Female</option>
        </select>
    </div>
</div>

                                <!-- Record -->
<div class="form-group">
    <label for="Record" class="col-sm-2 control-label">Record</label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="1" name="Record" id="Record" placeholder="Athlete's Record"><?php echo $athlete['record']; ?></textarea>
    </div>
</div>
                                <!-- Category Dropdown -->
<div class="form-group">
    <label for="Category" class="col-sm-2 control-label">Category</label>
    <div class="col-sm-8">
        <select class="form-control1" name="Category" id="Category" required>
            <option value="">Select Category</option>
            <?php 
            // Fetch data from database table wcateg
            $sql = "SELECT * FROM category";
            $query = $dbh->prepare($sql);
            $query->execute();
            $categories = $query->fetchAll(PDO::FETCH_ASSOC);

            // Loop through each category and populate the dropdown options
            foreach ($categories as $category) {
                $selected = ($athlete['category'] == $category['category']) ? 'selected' : '';
                echo '<option value="' . $category['category'] . '" ' . $selected . '>' . $category['category'] . '</option>';
            }
            ?>
        </select>
    </div>
</div>
<!-- Ημερομηνια Γεννησης -->
<div class="form-group">
    <label class="col-sm-2 control-label">Ημερομηνια Γεννησης</label>
    <div class="col-sm-8">
        <input type="date" class="form-control1" name="Birthdate" value="<?php echo htmlentities($athlete['birthdate']); ?>" required>
    </div>
</div>

                                <!-- Τοπος Γεννησης -->
                                <div class="form-group">
                                    <label for="Birthplace" class="col-sm-2 control-label">Τοπος Γεννησης</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Birthplace" id="Birthplace" placeholder="Τοπος Γεννησης" value="<?php echo $athlete['birthplace']; ?>" required>
                                    </div>
                                </div>
                             <!-- GYM -->
<div class="form-group">
    <label for="Gym" class="col-sm-2 control-label">GYM</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Gym" id="Gym" placeholder="GYM" value="<?php echo $athlete['gym']; ?>" required>
    </div>
</div>

<!-- Χωρα -->
<div class="form-group">
    <label for="Country" class="col-sm-2 control-label">Χωρα</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Country" id="Country" placeholder="Χωρα" value="<?php echo $athlete['country']; ?>" required>
    </div>
</div>

                                <!-- Fun Facts -->
                                <div class="form-group">
                                    <label for="FunFacts" class="col-sm-2 control-label">Fun Facts</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="5" cols="50" name="FunFacts" id="FunFacts" placeholder="Fun Facts" ><?php echo $athlete['funFacts']; ?></textarea> 
                                    </div>
                                </div>
                      <!-- Athlete Image -->
<div class="form-group">
    <label for="AthleteImage" class="col-sm-2 control-label">Φωτογραφία Αθλητή</label>
    <div class="col-sm-8">
        <input type="file" name="AthleteImage" id="AthleteImage">
        <?php if (!empty($athlete['image'])) : ?>
            <img src="images/athletes/<?php echo $athlete['image']; ?>" width="200" alt="Athlete Image">
        <?php else: ?>
            <p>No image available</p>
        <?php endif; ?>
        <?php if (!empty($athlete['id'])) : ?>
            &nbsp;&nbsp;&nbsp;<a href="change-image.php?imgid=<?php echo htmlentities($athlete['id']); ?>">Αλλαγή Φωτογραφίας</a>
        <?php endif; ?>
    </div>
</div>
<!-- Pro/Am -->
<div class="form-group">
    <label for="ProAm" class="col-sm-2 control-label">Pro/Am</label>
    <div class="col-sm-8">
        <select class="form-control1" name="ProAm" id="ProAm">
            <option value="PRO" <?php if($athlete['proam'] == "PRO") echo "selected"; ?>>PRO</option>
            <option value="AMATEUR" <?php if($athlete['proam'] == "AMATEUR") echo "selected"; ?>>AMATEUR</option>
        </select>
    </div>
</div>

                                <!-- Tapology -->
<div class="form-group">
    <label for="Tapology" class="col-sm-2 control-label">Tapology</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Tapology" id="Tapology" placeholder="Tapology Profile URL" value="<?php echo $athlete['tapology']; ?>">
    </div>
</div>
<!-- Sherdog -->
<div class="form-group">
    <label for="Sherdog" class="col-sm-2 control-label">Sherdog</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Sherdog" id="Sherdog" placeholder="Sherdog Profile URL" value="<?php echo $athlete['sherdog']; ?>">
    </div>
</div>
                                <!-- Instagram -->
<div class="form-group">
    <label for="Instagram" class="col-sm-2 control-label">Instagram</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Instagram" id="Instagram" placeholder="Instagram Profile URL" value="<?php echo $athlete['instagram']; ?>">
    </div>
</div>
<!-- Facebook -->
<div class="form-group">
    <label for="Facebook" class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Facebook" id="Facebook" placeholder="Facebook Profile URL" value="<?php echo $athlete['facebook']; ?>">
    </div>
</div>
<!-- Twitter -->
<div class="form-group">
    <label for="Twitter" class="col-sm-2 control-label">Twitter</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="Twitter" id="Twitter" placeholder="Twitter Profile URL" value="<?php echo isset($athlete['twitter']) ? $athlete['twitter'] : ''; ?>">
    </div>
</div>


<!-- TikTok -->
<div class="form-group">
    <label for="TikTok" class="col-sm-2 control-label">TikTok</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="TikTok" id="TikTok" placeholder="TikTok Profile URL" value="<?php echo $athlete['tiktok']; ?>">
    </div>
</div>
                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button type="submit" name="submit" class="btn-primary btn">ΕΝΗΜΕΡΩΣΗ</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
</body>
</html>
