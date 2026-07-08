<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('includes/config.php');

// Assuming 'pid' is the athlete ID
$aid = intval($_GET['pid']); // Change 'id' to 'pid' here
$error = ""; // Initializing the variable
$msg = ""; // Initializing the variable

// Fetch athlete data from the database based on the ID
$sql = "SELECT * FROM tblathletes WHERE id = :aid";
$query = $dbh->prepare($sql);
$query->bindParam(':aid', $aid, PDO::PARAM_INT);
$query->execute();
$athlete = $query->fetch(PDO::FETCH_ASSOC);

// Check if the athlete record exists
if (!$athlete) {
    $error = "Athlete with ID: $aid not found";
} else {
    // Populate variables with athlete data for pre-filling the form
    $athleteName = $athlete['athleteName'];
    $nickname = $athlete['nickname'];
    $birthdate = $athlete['birthdate'];
    $birthplace = $athlete['birthplace'];
    $gym = $athlete['gym'];
    $country = $athlete['country'];
    $funFacts = $athlete['funFacts'];
    $image = $athlete['image'];
    $instagram = $athlete['instagram'];
    $facebook = $athlete['facebook'];
    $twitter = $athlete['twitter'];
    $tiktok = $athlete['tiktok'];
    $tapology = $athlete['tapology'];
    $sherdog = $athlete['sherdog'];
    $record = $athlete['record'];
    $proam = $athlete['proam'];
    $sex = $athlete['sex']; // Corrected assignment

    // Now, proceed to update the athlete data
    if(isset($_POST['submit'])) {
        // Retrieve athlete data from the form
        $athleteName = $_POST['AthleteName'];
        $nickname = $_POST['Nickname'];
        $birthdate = $_POST['Birthdate'];
        $birthplace = $_POST['Birthplace'];
        $gym = $_POST['Gym'];
        $country = $_POST['Country'];
        $funFacts = $_POST['FunFacts'];
        $image = $_FILES["AthleteImage"]["name"];
        $instagram = $_POST['Instagram'];
        $facebook = $_POST['Facebook'];
        $twitter = $_POST['Twitter'];
        $tiktok = $_POST['TikTok'];
        $tapology = $_POST['Tapology'];
        $sherdog = $_POST['Sherdog'];
        $record = $_POST['Record'];
        $sex = $_POST['Sex']; // Corrected assignment
        $proam = $_POST['ProAm'];
        $category = $_POST['category'];

        // Check if a new image is uploaded
        if (!empty($_FILES["AthleteImage"]["name"])) {
            $image = $_FILES["AthleteImage"]["name"];
            // Move uploaded image to desired directory
            move_uploaded_file($_FILES["AthleteImage"]["tmp_name"], "images/athletes/" . $_FILES["AthleteImage"]["name"]);
        } else {
            // If no new image is uploaded, keep the existing image value
            $image = $athlete['image'];
        }

        // SQL query to update data in the tblathletes table
        $sql = "UPDATE tblathletes 
            SET athleteName = :athleteName, 
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
                proam = :proam,
                sex = :sex,
                category = :category
            WHERE id = :aid";

        // Prepare and execute the query
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
        $query->bindParam(':tapology', $tapology, PDO::PARAM_STR);
        $query->bindParam(':sherdog', $sherdog, PDO::PARAM_STR);
        $query->bindParam(':record', $record, PDO::PARAM_STR);
        $query->bindParam(':sex', $sex, PDO::PARAM_STR);
        $query->bindParam(':proam', $proam, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':aid', $aid, PDO::PARAM_INT);

        // Execute the query
        if ($query->execute()) {
            $_SESSION['msg'] = "Athlete updated successfully with ID: $aid";
        } else {
            $_SESSION['error'] = "Failed to update athlete with ID: $aid";
        }

        // Redirect to the same page after form submission
        header("Location: ".$_SERVER['PHP_SELF']."?pid=$aid");
        exit();
    }
}

// Display error or success messages if set
if(isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']); // Clear the session message to avoid displaying it again
}

if(isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Clear the session error message to avoid displaying it again
}
?>



<!DOCTYPE HTML>
<html>
<head>
<title>UMC | ΕΝΗΜΕΡΩΣΗ Αθλητη <?php echo $athlete['athleteName']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pooled Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>

</head> 
<body>

   <div class="page-container">
       <div class="left-content">
           <div class="mother-grid-inner">
              <!-- Header -->
              <?php include('includes/header.php');?>
              <div class="clearfix"> </div>   
           </div>
           <!-- Breadcrumb -->
           <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a><i class="fa fa-angle-right"></i>Update Athlete</li>
            </ol>
            <!-- Form -->
            <div class="grid-form">
                <div class="grid-form1">
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
        <select class="form-control1" name="category" id="category" required>
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
      <div class="panel-footer">
      <?php include('includes/footer.php');?>
<!--COPY rights end here-->
	 </div>
    </form>
  </div>
 	</div>
 	<!--//grid-->


		<!-- /script-for sticky-nav -->
<!--inner block start here-->
<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->

</div>
</div>
  <!--//content-inner-->
		<!--/sidebar-menu-->
					<?php include('includes/sidebarmenu.php');?>
							  <div class="clearfix"></div>		
							</div>
							<script>
							var toggle = true;
										
							$(".sidebar-icon").click(function() {                
							  if (toggle)
							  {
								$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
								$("#menu span").css({"position":"absolute"});
							  }
							  else
							  {
								$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
								setTimeout(function() {
								  $("#menu span").css({"position":"relative"});
								}, 400);
							  }
											
											toggle = !toggle;
										});
							</script>
<!--js -->
<!-- script-for sticky-nav -->
<script>
		$(document).ready(function() {
			 var navoffeset=$(".header-main").offset().top;
			 $(window).scroll(function(){
				var scrollpos=$(window).scrollTop(); 
				if(scrollpos >=navoffeset){
					$(".header-main").addClass("fixed");
				}else{
					$(".header-main").removeClass("fixed");
				}
			 });
			 
		});
		</script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   

</body>
</html>
