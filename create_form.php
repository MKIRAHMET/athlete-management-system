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

// Check if the form is submitted
if(isset($_POST['submit'])) {
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

    // Set coach_id and status
    $coach_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $status = 'pending'; // Default status value

    // Ensure coach_id is available
    if ($coach_id !== null) {
        try {
            // SQL query to insert data into the athlete_requests table
            $sql = "INSERT INTO athlete_requests (athleteName, nickname, birthdate, birthplace, gym, country, funFacts, image, instagram, facebook, twitter, tiktok, tapology, sherdog, record, category, sex, proam, coach_id, status) 
                    VALUES (:athleteName, :nickname, :birthdate, :birthplace, :gym, :country, :funFacts, :image, :instagram, :facebook, :twitter, :tiktok, :tapology, :sherdog, :record, :category, :sex, :proam, :coach_id, :status)";
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

            // Bind coach_id and status
            $query->bindParam(':coach_id', $coach_id, PDO::PARAM_INT);
            $query->bindParam(':status', $status, PDO::PARAM_STR);

            // Execute the query
            if ($query->execute()) {
                $_SESSION['msg'] = "Athlete created successfully";
            } else {
                throw new Exception("Failed to create athlete");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Coach ID not found";
    }

    // Redirect to the same page after form submission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Display error or success messages if set
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : "";
$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";

// Clear session messages to avoid displaying them again
unset($_SESSION['msg']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CREATE ATHLETE</title>

    <link href="css/coach.css" rel='stylesheet' type='text/css' />


</head>
<body>

<?php include 'coachmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">

    <?php
   // Check if success parameter is set in the URL and display success message
   if(isset($_GET['success']) && $_GET['success'] == 1){ ?>
       <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
   <?php } ?>

   <?php
   // Check if error parameter is set in the URL and display error message
   if(isset($_GET['error']) && $_GET['error'] == 1){ ?>
       <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
   <?php } ?>
   <!-- Form -->
   <div class="logo-container">
    <img src="images/logo.png" alt="Website Logo" class="logo">
</div>   
   <div class="athlete-form">     
     
                    <h3>Create Athlete</h3>
                    <?php if($error){ ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
                    else if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form class="form-horizontal" name="athleteForm" method="post" enctype="multipart/form-data">
                                <!-- Ονομα Αθλητη -->
                                <div class="form-group">
                                    <label for="AthleteName" class="col-sm-2 control-label">Ονομα Αθλητη</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="AthleteName" id="AthleteName" placeholder="Ονομα Αθλητη" required>
                                        <input type="hidden" name="coach_id" value="<?php echo isset($_SESSION['coach_id']) ? $_SESSION['coach_id'] : ''; ?>">
                                    </div>
                                </div>
                                <!-- Nickname -->
                                <div class="form-group">
                                    <label for="Nickname" class="col-sm-2 control-label">Nickname</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Nickname" id="Nickname" placeholder="Nickname">
                                    </div>
                                </div>
                                <!-- Sex -->
                                <div class="form-group">
                                    <label for="Sex" class="col-sm-2 control-label">Sex</label>
                                    <div class="col-sm-8">
                                        <select class="form-control1" name="Sex" id="Sex" required>
                                            <option value="">Select Sex</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Record -->
                                <div class="form-group">
                                    <label for="Record" class="col-sm-2 control-label">Record</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="1" name="Record" id="Record" placeholder="Athlete's Record"></textarea>
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
                                                echo '<option value="' . $category['category'] . '">' . $category['category'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Ημερομηνια Γεννησης -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Ημερομηνια Γεννησης</label>
                                    <div class="col-sm-2">
                                        <select class="form-control1" name="BirthdateDay" required>
                                            <option value="">Day</option>
                                            <?php for($i = 1; $i <= 31; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control1" name="BirthdateMonth" required>
                                            <option value="">Month</option>
                                            <?php
                                            $months = array(
                                                1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                                            );
                                            foreach($months as $key => $month) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $month; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-control1" name="BirthdateYear" required>
                                            <option value="">Year</option>
                                            <?php 
                                            $currentYear = date('Y');
                                            for($i = $currentYear; $i >= 1900; $i--) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Pro/Am -->
                                <div class="form-group">
                                    <label for="ProAm" class="col-sm-2 control-label">Pro/Am</label>
                                    <div class="col-sm-8">
                                        <select class="form-control1" name="ProAm" id="ProAm">
                                            <option value="PRO">PRO</option>
                                            <option value="AMATEUR">AMATEUR</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Τοπος Γεννησης -->
                                <div class="form-group">
                                    <label for="Birthplace" class="col-sm-2 control-label">Τοπος Γεννησης</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Birthplace" id="Birthplace" placeholder="Τοπος Γεννησης" required>
                                    </div>
                                </div>
                                <!-- GYM -->
                                <div class="form-group">
                                    <label for="Gym" class="col-sm-2 control-label">GYM</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Gym" id="Gym" placeholder="GYM" required>
                                    </div>
                                </div>
                                <!-- Χωρα -->
                                <div class="form-group">
                                    <label for="Country" class="col-sm-2 control-label">Χωρα</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Country" id="Country" placeholder="Χωρα" required>
                                    </div>
                                </div>
                                <!-- Fun Facts -->
                                <div class="form-group">
                                    <label for="FunFacts" class="col-sm-2 control-label">Fun Facts</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="5" cols="50" name="FunFacts" id="FunFacts" placeholder="Fun Facts"></textarea> 
                                    </div>
                                </div>
                                <!-- Athlete Image -->
                                <div class="form-group">
                                    <label for="AthleteImage" class="col-sm-2 control-label">Φωτογραφια Αθλητη</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="AthleteImage" id="AthleteImage">
                                    </div>
                                </div>
                                <!-- Tapology -->
                                <div class="form-group">
                                    <label for="Tapology" class="col-sm-2 control-label">Tapology</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Tapology" id="Tapology" placeholder="Tapology Profile URL">
                                    </div>
                                </div>
                                <!-- Sherdog -->
                                <div class="form-group">
                                    <label for="Sherdog" class="col-sm-2 control-label">Sherdog</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Sherdog" id="Sherdog" placeholder="Sherdog Profile URL">
                                    </div>
                                </div>
                                <!-- Instagram -->
                                <div class="form-group">
                                    <label for="Instagram" class="col-sm-2 control-label">Instagram</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Instagram" id="Instagram" placeholder="Instagram Profile URL">
                                    </div>
                                </div>
                                <!-- Facebook -->
                                <div class="form-group">
                                    <label for="Facebook" class="col-sm-2 control-label">Facebook</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="Facebook" id="Facebook" placeholder="Facebook Profile URL">
                                    </div>
                                </div>
                                <!-- twitter -->
                                <div class="form-group">
                                    <label for="twitter" class="col-sm-2 control-label">twitter</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="twitter" id="twitter" placeholder="twitter Profile URL">
                                    </div>
                                </div>
                                <!-- TikTok -->
                                <div class="form-group">
                                    <label for="TikTok" class="col-sm-2 control-label">TikTok</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1" name="TikTok" id="TikTok" placeholder="TikTok Profile URL">
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button type="submit" name="submit" class="btn-primary btn">ΔΗΜΙΟΥΡΓΙΑ</button>
                                        <button type="reset" class="btn-inverse btn">Reset</button>
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
