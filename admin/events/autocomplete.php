<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection script
include '../db.php';

// Check if the database connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the search term parameter is set
if(isset($_GET['term'])) {
    // Fetch data from the database based on the search term
    $searchTerm = $_GET['term'];
    $query = $conn->query("SELECT athleteName FROM tblathletes WHERE athleteName LIKE '%$searchTerm%' ORDER BY athleteName ASC");
    $athletes = array();
    while ($row = $query->fetch_assoc()) {
        $athletes[] = $row['athleteName'];
    }

    // Return the data as JSON
    echo json_encode($athletes);
} else {
    // Return an empty array if the search term parameter is not set
    echo json_encode(array());
}
?>
