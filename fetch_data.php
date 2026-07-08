<?php
// Include your database connection file
include('includes/config.php');

// Initialize an empty array to store the filtered athlete data
$filteredAthletes = array();

// Construct the SQL query based on the received POST parameters
$sql = "SELECT * FROM tblathletes WHERE 1";

if (!empty($_POST['category'])) {
    $category = $_POST['category'];
    $sql .= " AND category = :category";
}

if (!empty($_POST['gym'])) {
    $gym = $_POST['gym'];
    $sql .= " AND gym = :gym";
}

if (!empty($_POST['sex'])) {
    $sex = $_POST['sex'];
    $sql .= " AND sex = :sex";
}

if (!empty($_POST['proam'])) {
    $proam = $_POST['proam'];
    $sql .= " AND proam = :proam";
}

if (!empty($_POST['country'])) {
    $country = $_POST['country'];
    $sql .= " AND country = :country";
}

// Prepare the SQL statement
$stmt = $dbh->prepare($sql);

// Bind parameters if they are set
if (!empty($category)) {
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
}
if (!empty($gym)) {
    $stmt->bindParam(':gym', $gym, PDO::PARAM_STR);
}
if (!empty($sex)) {
    $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);
}
if (!empty($proam)) {
    $stmt->bindParam(':proam', $proam, PDO::PARAM_STR);
}
if (!empty($country)) {
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
}

// Execute the SQL statement
$stmt->execute();

// Fetch the filtered athlete data
$filteredAthletes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output the filtered athlete data as HTML
foreach ($filteredAthletes as $athlete) {
    // Output athlete information as needed (e.g., create HTML elements)
    echo "<div>{$athlete['athleteName']}</div>";
}
?>
