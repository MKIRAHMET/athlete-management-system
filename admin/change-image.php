<?php
// Start the session and include necessary files
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: ../../admin/login.php");
    exit;
}

// Get the athlete ID from the query string
$id = isset($_GET['imgid']) ? intval($_GET['imgid']) : null;

// Check if the athlete ID is valid
if ($id === null) {
    // Handle invalid athlete ID
    echo "Invalid athlete ID";
    exit;
}

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Check if the image file is uploaded successfully
    if ($_FILES["athleteimage"]["error"] === UPLOAD_ERR_OK) {
        $image = $_FILES["athleteimage"]["name"];
        // Move the uploaded image file to the destination directory
        move_uploaded_file($_FILES["athleteimage"]["tmp_name"], "images/athletes/" . $_FILES["athleteimage"]["name"]);

        // Update the athlete image in the database
        $sql = "UPDATE tblathletes SET image = :image WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':image', $image, PDO::PARAM_STR);

        if ($query->execute()) {
            $msg = "Athlete Image Updated Successfully";
        } else {
            $error = "Failed to update athlete image. Please try again.";
        }
    } else {
        // Handle file upload error
        $error = "Failed to upload image. Please try again.";
    }
}

// Fetch the current athlete image
$sql = "SELECT image FROM tblathletes WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
if ($query->execute()) {
    $result = $query->fetch(PDO::FETCH_ASSOC);
    // Check if the result is not empty
    if ($result) {
        $currentImage = $result['image'];
    } else {
        // Handle case where no image is found for the given ID
        $error = "No image found for the given ID";
    }
} else {
    // Handle query execution error
    $error = "Failed to fetch image. Please try again.";
}
?>



<!DOCTYPE HTML>
<html>
<head>
<title>ΑΛΛΑΓΗ ΕΙΚΟΝΑΣ</title>
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
   <!--/content-inner-->
<div class="left-content">
	   <div class="mother-grid-inner">
              <!--header start here-->
<?php include('includes/header.php');?>
							
				     <div class="clearfix"> </div>	
				</div>
<!--heder end here-->
	<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a><i class="fa fa-angle-right"></i>ΕΝΗΜΕΡΩΣΗ ΦΩΤΟΓΡΑΦΙΑΣ ΠΑΚΕΤΟΥ </li>
            </ol>
		<!--grid-->
 	<div class="grid-form">
 
<!---->
<div class="grid-form1">
            <h3>ΕΝΗΜΕΡΩΣΗ ΦΩΤΟΓΡΑΦΙΑΣ ΠΑΚΕΤΟΥ </h3>
            <?php if(isset($error)): ?>
                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
            <?php elseif(isset($msg)): ?>
                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
            <?php endif; ?>
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
        <!-- Athlete Image -->
<div class="form-group">
    <label for="athleteimage" class="col-sm-2 control-label">Φωτογραφία Αθλητή</label>
    <div class="col-sm-8">
        <?php if(isset($currentImage)): ?>
            <img src="images/athletes/<?php echo htmlentities($currentImage ?? '', ENT_QUOTES, 'UTF-8'); ?>" width="200" alt="Current Image">
        <?php else: ?>
            <p>No image available</p>
        <?php endif; ?>
    </div>
</div>

<!-- Upload New Image -->
<div class="form-group">
    <label for="athleteimage" class="col-sm-2 control-label">Ανέβασμα νέας φωτογραφίας</label>
    <div class="col-sm-8">
        <input type="file" name="athleteimage" id="athleteimage" required>
    </div>
</div>

                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button type="submit" name="submit" class="btn-primary btn">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 	<!--//grid-->

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
		<!-- /script-for sticky-nav -->
<!--inner block start here-->
<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->
<?php include('includes/footer.php');?>
<!--COPY rights end here-->
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
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   
</body>
</html>
