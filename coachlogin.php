<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('dbh.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['coach_id']);
}

// Check if the user is already logged in
if (isLoggedIn()) {
    header("Location: create_form.php"); // Redirect to the main page if already logged in
    exit();
}

if(isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Consider using a stronger hashing algorithm

    $sql = "SELECT id FROM tblusers WHERE EmailId=:email AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user) {
        $_SESSION['id'] = $user['id']; // Set the coach ID in the session
        $_SESSION['login'] = $email;
        header("Location: create_form.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>COACH REGISTRATION</title>
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
<link href="css/coach.css" rel='stylesheet' type='text/css' />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>COACH SIGN IN</title>
        </head>
<body>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="Website Logo" class="logo">
        </div>
        <div class="login-right">
        <form method="post">
            <h3>Sign in to your account</h3>
            <input type="text" name="email" id="email" placeholder="Enter your Email" required="">  
            <input type="password" name="password" id="password" placeholder="Password" required="">  
            <h4><a href="forgot-password.php">Forgot password</a></h4>
            <input type="submit" name="signin" value="SIGN IN">
        </form>
                    <a href="coachsignup.php" class="redirect-button upload-button">REGISTER</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>