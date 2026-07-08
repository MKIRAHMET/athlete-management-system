<?php
// Include the database connection file
include('dbh.php');

// Initialize variables to store error/success messages
$error = "";
$msg = "";

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve form data and sanitize inputs
    $athleteName = htmlspecialchars($_POST['AthleteName']);
    $nickname = htmlspecialchars($_POST['Nickname']);
    $sex = htmlspecialchars($_POST['Sex']);
    $record = htmlspecialchars($_POST['Record']);
    $category = htmlspecialchars($_POST['Category']);
    $birthdate = htmlspecialchars($_POST['BirthdateYear'] . '-' . $_POST['BirthdateMonth'] . '-' . $_POST['BirthdateDay']);
    $proAm = htmlspecialchars($_POST['ProAm']);
    $birthplace = htmlspecialchars($_POST['Birthplace']);
    $gym = htmlspecialchars($_POST['Gym']);
    $country = htmlspecialchars($_POST['Country']);
    $funFacts = htmlspecialchars($_POST['FunFacts']);
    $tapology = htmlspecialchars($_POST['Tapology']);
    $sherdog = htmlspecialchars($_POST['Sherdog']);
    $instagram = htmlspecialchars($_POST['Instagram']);
    $facebook = htmlspecialchars($_POST['Facebook']);
    $twitter = htmlspecialchars($_POST['twitter']);
    $tiktok = htmlspecialchars($_POST['TikTok']);

    // Validate form data (perform additional validation as needed)

    // Insert data into the athlete_requests table
    $sql = "INSERT INTO athlete_requests (athleteName, nickname, sex, record, category, birthdate, proam, birthplace, gym, country, funFacts, tapology, sherdog, instagram, facebook, twitter, tiktok) VALUES (:athleteName, :nickname, :sex, :record, :category, :birthdate, :proAm, :birthplace, :gym, :country, :funFacts, :tapology, :sherdog, :instagram, :facebook, :twitter, :tiktok)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':athleteName', $athleteName, PDO::PARAM_STR);
    $query->bindParam(':nickname', $nickname, PDO::PARAM_STR);
    $query->bindParam(':sex', $sex, PDO::PARAM_STR);
    $query->bindParam(':record', $record, PDO::PARAM_STR);
    $query->bindParam(':category', $category, PDO::PARAM_STR);
    $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    $query->bindParam(':proAm', $proAm, PDO::PARAM_STR);
    $query->bindParam(':birthplace', $birthplace, PDO::PARAM_STR);
    $query->bindParam(':gym', $gym, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':funFacts', $funFacts, PDO::PARAM_STR);
    $query->bindParam(':tapology', $tapology, PDO::PARAM_STR);
    $query->bindParam(':sherdog', $sherdog, PDO::PARAM_STR);
    $query->bindParam(':instagram', $instagram, PDO::PARAM_STR);
    $query->bindParam(':facebook', $facebook, PDO::PARAM_STR);
    $query->bindParam(':twitter', $twitter, PDO::PARAM_STR);
    $query->bindParam(':tiktok', $tiktok, PDO::PARAM_STR);

    if($query->execute()) {
        $msg = "Athlete request created successfully";
    } else {
        $error = "Failed to create athlete request. Please try again.";
    }
} else {
    // If the form is not submitted, redirect back to the form page
    header("Location: athlete_form.php");
    exit();
}
?>
