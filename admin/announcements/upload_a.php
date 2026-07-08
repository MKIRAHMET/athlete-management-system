<?php 

include '../db.php'; // Include your database connection script
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect back to the login page if not logged in
    header("Location: ../login.php");
    exit();
}

//CKeditor upload operation
if(isset($_FILES['upload']['name']))
{
 $file = $_FILES['upload']['tmp_name'];
 $file_name = $_FILES['upload']['name'];
 $file_name_array = explode(".", $file_name); 
 $extension = end($file_name_array);
 $new_image_name =rand() . '.' . $extension; 
        if($extension!= "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "PNG" && $extension != "JPG" && $extension != "JPEG") {
    
    echo "<script type='text/javascript'>alert('Sorry, only JPG, JPEG, & PNG files are allowed. Close image properties window and try again');</script>";
}
 elseif($_FILES["upload"]["size"] > 100000000) {
  
 echo "<script type='text/javascript'>alert('Image is too large: Upload image under 100 MB . Close image properties window and try again');</script>";
}
else 
{
 move_uploaded_file($file, '../../uploads/' . $new_image_name); 
  //mysqli_query($mysqli,"INSERT into media(image) VALUES('$new_image_name')"); //Insert Query
  $function_number = $_GET['CKEditorFuncNum'];
  $url = 'http://thalatta.umc.gr/uploads/'.$new_image_name; //Set your path
  $message = '';
  echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";   
} 
}
?>