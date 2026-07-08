<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('admin/includes/config.php');
include('dbh.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['login']);
}

// Check if the user is not logged in
if (!isLoggedIn()) {
    // Redirect to the login page if not logged in
    header("Location: coach.php");
    exit(); // Stop further execution of the page
}

// Retrieve coach ID from session
$coach_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Fetch athletes data for the logged-in coach
$sql = "SELECT * FROM athlete_requests WHERE coach_id = :coach_id";
$query = $dbh->prepare($sql);
$query->bindParam(':coach_id', $coach_id, PDO::PARAM_INT);
$query->execute();
$athletes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
    <link href="css/coach.css" rel='stylesheet' type='text/css' />

</head>
<body>
<?php include 'coachmenu.php'; ?>
<div style="margin-left: 250px; padding: 20px;">


    <h1>Coach Dashboard</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Athlete Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($athletes as $athlete) { ?>
                <tr>
                    <td><?php echo $athlete['athleteName']; ?></td>
                    <td><?php echo $athlete['status']; ?></td>
                    <td>
                        <?php if ($athlete['status'] === 'pending' || $athlete['status'] === 'denied') { ?>
                            <a href="edit_athlete.php?id=<?php echo $athlete['id']; ?>">Edit</a>
                        <?php } else { ?>
                            <!-- Display message or disable editing for accepted athletes -->
                            <span>Accepted - No action allowed</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</body>
</html>