<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbh.php');



if(isset($_POST['submit50'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    // Input validation/sanitization
    // (You can add more specific validation rules)
    if(empty($email) || empty($mobile) || empty($newpassword) || empty($confirmpassword)) {
        $error = "All fields are required.";
    } elseif($newpassword !== $confirmpassword) {
        $error = "New password and confirm password do not match.";
    } else {
        // Hash the new password (using bcrypt for better security)
        $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

        // Perform the password update
        $sql ="SELECT EmailId FROM tblusers WHERE EmailId=:email and MobileNumber=:mobile";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            $con = "UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email and MobileNumber=:mobile";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
            $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg = "Your password has been successfully changed.";
        } else {
            $error = "Email id or Mobile no is invalid.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
 <title>FORGOT PASSWORD</title>
        <!-- Javascript for check email availability -->
    <link rel="stylesheet" href="admin/essentials/admin.css">
<script>
function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data: 'emailid=' + $("#email").val(),
        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error: function() {}
    });
}
</script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="js/jquery-1.12.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
	<script type="text/javascript">
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script>
<link href="css/coach.css" rel='stylesheet' type='text/css' />

        </head>
<body>
<div class="container">
    <div class="logo">
        <img src="images/logo.png" alt="Website Logo" class="logo">
    </div>
    <div class="privacy">
	<div class="container">
		<h3>Recover Password</h3>
		<form name="chngpwd" method="post" onSubmit="return valid();">
			<?php if($error){ ?><div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div><?php }?>
			<p>
				<b>Email id</b>  <input type="email" name="email" class="form-control" id="email" placeholder="Reg Email id" required="">
			</p> 
			<p>
				<b>Mobile No</b>  <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Reg Mobile no" required="">
			</p> 
			<p>
				<b>New Password</b>  <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New Password" required="">
			</p>
			<p>
				<b>Confirm Password</b>  <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required="">
			</p>
			<p>
				<button type="submit" name="submit50" class="btn-primary btn">Change</button>
			</p>
		</form>
        <a href="coachsignup.php" class="redirect-button home-button">REGISTER</a>
        <a href="coachlogin.php" class="redirect-button upload-button">LOGIN</a>
                </div>

	</div>
</div>
    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>