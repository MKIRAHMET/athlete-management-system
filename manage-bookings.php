<?php
session_start();
include('includes/config.php');
include('../../admin/db.php'); // Include your database connection script

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Check if $_SESSION['admin_logged_in'] is not set or not equal to true

    // Redirect to the login page
    header("Location: ../../login.php");
} else {
    // code for approve or deny athlete
	if(isset($_GET['approve_id'])) {
		$athlete_id = intval($_GET['approve_id']);
		$status = 'accepted';
		// Fetch athlete details from athlete_requests table
		$fetch_sql = "SELECT * FROM athlete_requests WHERE id = :athlete_id";
		$fetch_query = $dbh->prepare($fetch_sql);
		$fetch_query->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
		$fetch_query->execute();
		$athlete_details = $fetch_query->fetch(PDO::FETCH_ASSOC);
	
		// Insert athlete details into tblathletes table
		$insert_sql = "INSERT INTO tblathletes (athleteName, nickname, birthdate, birthplace, gym, country, funFacts, image, instagram, facebook, tiktok, category, tapology, sherdog, record, sex, twitter, proam) 
					   VALUES (:athleteName, :nickname, :birthdate, :birthplace, :gym, :country, :funFacts, :image, :instagram, :facebook, :tiktok, :category, :tapology, :sherdog, :record, :sex, :twitter, :proam)";
		$insert_query = $dbh->prepare($insert_sql);
		$insert_query->bindParam(':athleteName', $athlete_details['athleteName'], PDO::PARAM_STR);
		$insert_query->bindParam(':nickname', $athlete_details['nickname'], PDO::PARAM_STR);
		$insert_query->bindParam(':birthdate', $athlete_details['birthdate'], PDO::PARAM_STR);
		$insert_query->bindParam(':birthplace', $athlete_details['birthplace'], PDO::PARAM_STR);
		$insert_query->bindParam(':gym', $athlete_details['gym'], PDO::PARAM_STR);
		$insert_query->bindParam(':country', $athlete_details['country'], PDO::PARAM_STR);
		$insert_query->bindParam(':funFacts', $athlete_details['funFacts'], PDO::PARAM_STR);
		$insert_query->bindParam(':image', $athlete_details['image'], PDO::PARAM_STR);
		$insert_query->bindParam(':instagram', $athlete_details['instagram'], PDO::PARAM_STR);
		$insert_query->bindParam(':facebook', $athlete_details['facebook'], PDO::PARAM_STR);
		$insert_query->bindParam(':tiktok', $athlete_details['tiktok'], PDO::PARAM_STR);
		$insert_query->bindParam(':category', $athlete_details['category'], PDO::PARAM_STR);
		$insert_query->bindParam(':tapology', $athlete_details['tapology'], PDO::PARAM_STR);
		$insert_query->bindParam(':sherdog', $athlete_details['sherdog'], PDO::PARAM_STR);
		$insert_query->bindParam(':record', $athlete_details['record'], PDO::PARAM_STR);
		$insert_query->bindParam(':sex', $athlete_details['sex'], PDO::PARAM_STR);
		$insert_query->bindParam(':twitter', $athlete_details['twitter'], PDO::PARAM_STR);
		$insert_query->bindParam(':proam', $athlete_details['proam'], PDO::PARAM_STR);
		$insert_query->execute();
	
		// Update athlete status in athlete_requests table
		$update_sql = "UPDATE athlete_requests SET status = :status WHERE id = :athlete_id";
		$update_query = $dbh->prepare($update_sql);
		$update_query->bindParam(':status', $status, PDO::PARAM_STR);
		$update_query->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
		$update_query->execute();
	
		$msg = "Athlete Approved successfully";
	}

    if(isset($_GET['deny_id'])) {
        $athlete_id = intval($_GET['deny_id']);
        $status = 'denied';
        $sql = "UPDATE athlete_requests SET status=:status WHERE id=:athlete_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':athlete_id', $athlete_id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Athlete Denied successfully";
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>UMC | ΔΙΑΧΕΙΡΗΣΗ ΑΘΛΗΤΩΝ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/table-style.css" />
<link rel="stylesheet" type="text/css" href="css/basictable.css" />
<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#table').basictable();

      $('#table-breakpoint').basictable({
        breakpoint: 768
      });

      $('#table-swap-axis').basictable({
        swapAxis: true
      });

      $('#table-force-off').basictable({
        forceResponsive: false
      });

      $('#table-no-resize').basictable({
        noResize: true
      });

      $('#table-two-axis').basictable();

      $('#table-max-height').basictable({
        tableWrapper: true
      });
    });
</script>
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
                <li class="breadcrumb-item"><a href="index.html">ΑΡΧΙΚΗ ΣΕΛΙΔΑ</a><i class="fa fa-angle-right"></i>ΔΙΑΧΕΙΡΗΣΗ ΚΡΑΤΗΣΕΩΝ</li>
            </ol>
            <div class="agile-grids">
                <?php if($msg) { ?>
                <div class="succWrap"><strong>Success</strong>: <?php echo htmlentities($msg); ?></div>
                <?php } ?>

                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>Manage Athlete Requests</h2>
						<table id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ΟΝΟΜΑ</th>
                            <th>NICKNAME</th>
                            <th>GYM</th>
                            <th>ΧΩΡΑ</th>
                            <th>RECORD</th>
                            <th>ΚΑΤΗΓΟΡΙΑ</th>
                            <th>ΕΝΕΡΓΕΙΕΣ</th> <!-- Action column -->
							<th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
$sql = "SELECT id, athleteName, nickname, gym, country, record, category, status FROM athlete_requests";
$query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if($query->rowCount() > 0) {
                            foreach($results as $result) {
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result->athleteName);?></td>
                            <td><?php echo htmlentities($result->nickname);?></td>
                            <td><?php echo htmlentities($result->gym);?></td>
                            <td><?php echo htmlentities($result->country);?></td>
                            <td><?php echo htmlentities($result->record);?></td>
                            <td><?php echo htmlentities($result->category);?></td>
                            <td>
                                                <?php if ($result->status == 'pending') : ?>
                                                    <a href="manage-bookings.php?approve_id=<?php echo htmlentities($result->id); ?>">Approve</a> /
                                                    <a href="manage-bookings.php?deny_id=<?php echo htmlentities($result->id); ?>">Deny</a>
                                                <?php elseif ($result->status == 'accepted') : ?>
                                                    Accepted
                                                <?php elseif ($result->status == 'denied') : ?>
                                                    Denied
                                                <?php endif; ?>
                                            </td>
											<td>
                    <button onclick="confirmDelete(<?php echo htmlentities($result->id); ?>)">Delete</button>
                </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No athlete requests found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
							<script>
// Function to confirm deletion
function confirmDelete(athleteId) {
    if (confirm("Are you sure you want to delete this athlete?")) {
        window.location.href = "delete-athlete.php?id=" + athleteId; // Redirect to delete script
    }
}
</script>
<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.min.js"></script>
   <!-- /Bootstrap Core JavaScript -->	   

</body>
</html>
<?php } ?>