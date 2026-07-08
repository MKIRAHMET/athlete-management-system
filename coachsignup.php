<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('admin/db.php');


include('dbh.php');


if(isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Not recommended, consider using a more secure hashing algorithm

    // Check if passwords match
    if($_POST['password'] !== $_POST['confirm_password']) {
        $_SESSION['msg'] = "Passwords do not match";
    } else {
        $sql = "INSERT INTO tblusers (FullName, MobileNumber, EmailId, Password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $fname, $mnumber, $email, $password);

        if($stmt->execute()) {
            $_SESSION['msg'] = "You are Successfully registered. Now you can login";
        } else {
            $_SESSION['msg'] = "Something went wrong. Please try again.";
        }
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
    <link href="css/coach.css" rel='stylesheet' type='text/css' />

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
<style>
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.logo {
    text-align: center;
    margin-bottom: 20px;
}

.logo img {
    width: 200px;
    height: auto;
}

.login-right {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.login-right h3 {
    margin-bottom: 20px;
}

.login-right input[type="text"],
.login-right input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 5px;
}

.login-right input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    box-sizing: border-box;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
}

.login-right input[type="submit"]:hover {
    background-color: #0056b3;
}
.upload-button {
    /* Styles specific to the upload button */
    background-color: #28a745; /* Green color */
    color: white;
}
.login-right {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.login-right h3 {
    margin-bottom: 20px;
}

.login-right input[type="text"],
.login-right input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 5px;
}

.login-right input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    box-sizing: border-box;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    cursor: pointer;
}

.login-right input[type="submit"]:hover {
    background-color: #0056b3;
}

.redirect-button {
    /* Styles for the login button */
    display: block;
    margin-top: 10px;
    padding: 10px;
    text-align: center;
    background-color: #28a745; /* Green color */
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.redirect-button:hover {
    background-color: #218838; /* Darker green color on hover */
}


    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>COACH REGISTRATION</title>
        </head>
        <body>
<div class="container">
    <div class="logo">
        <img src="images/logo.png" alt="Website Logo" class="logo">
    </div>

    <div class="login-right">
        <form name="signup" method="post" onsubmit="return validateForm()">
            <h3>Create your account</h3>
            <input type="text" value="" placeholder="Full Name" name="fname" autocomplete="off" required="">
            <input type="text" value="" placeholder="Mobile number" maxlength="10" name="mobilenumber" autocomplete="off" required="">
            <input type="text" value="" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
            <span id="user-availability-status" style="font-size:12px;"></span>
            <input type="password" value="" placeholder="Password" name="password" id="password" required="">
            <input type="password" value="" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
            <input type="submit" name="submit" id="submit" value="CREATE">
        </form>
        <a href="coachlogin.php" class="redirect-button upload-button">LOGIN</a>

    </div>

</div>

<script>
function validateForm() {
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;

    if (password != confirm_password) {
        alert("Passwords do not match");
        return false;
    }
    return true;
}

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

<?php
if (isset($_SESSION['msg'])) {
    echo '<script>alert("' . $_SESSION['msg'] . '");</script>';
    unset($_SESSION['msg']); // Clear the message to prevent it from being displayed on subsequent reloads
}
?>
</body>
</html>