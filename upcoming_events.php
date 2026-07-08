<?php

include('includes/config.php');

// Check if database connection is successful
if (!$dbh) {
    // Handle database connection error
    exit("Error: Unable to connect to the database. Please check your database configuration.");
}

$current_date = date("Y-m-d");
$sql_upcoming_events = "SELECT * FROM events WHERE event_date >= '$current_date' ORDER BY event_date ASC";

// Execute the query and handle errors if any
try {
    $result_upcoming_events = $dbh->query($sql_upcoming_events);
    $upcoming_events = $result_upcoming_events->fetchAll(PDO::FETCH_ASSOC);
    if ($result_upcoming_events->rowCount() > 0) {
        // Display upcoming events
        foreach ($upcoming_events as $event) {
            // Output event details
          $event['event_name'] . "<br>";
          $event['event_date'] . "<br>";
          $event['event_location'] . "<br>";
        }
    } else {
        // No upcoming events
        echo "No upcoming events";
    }
} catch (PDOException $e) {
    // Handle PDO exception
    exit("Error: " . $e->getMessage());
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UMC Events</title>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ionicons.min.css" rel="stylesheet">
    <link href="css/venobox.css" rel="stylesheet">
    <link href="css/socicon.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php include('includes/menu.php');?>

<!-- Hero -->
<section class="hero sub" id="top">
    <!-- Navigation -->
    <div class="navbar" id="scroll_to" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <a href="index.php" title="UMC">
                        <img src="images/logo.png" alt="UMC" height="40%" width="40%">
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 menu">
                    <a href="#">Menu</a>
                    <div id="nav_icon">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero content -->
    <div class="container">
        <div class="row blurb">
            <div class="col-md-push-1 col-md-10 col-sm-12 blurb-content">
                <h1 class="no-underline">Events</h1>
                <p>UMC Events Schedule</p>
            </div>
        </div>
    </div>
    <img src="images/content-4.png" class="bg" alt="MMA Website template">
</section>

<!-- Section pagination -->
<section class="green">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumbs">
                    <li><a href="index.php"><i class="icon ion-home"></i></a></li>
                    <li><a href="events.php">UMC Events</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="dark-2">
    <div class="container padding-60">
        <div class="row">
            <div class="col-md-8 col-md-push-2">
                <img src="images/dark-arrow.svg" alt="Scroll down" class="scroll-arrow centered">
                <h2 class="centered">UMC Events Schedule</h2>
                <p class="centered">Check out our upcoming events</p>
            </div>
        </div>
        <div class="row classes centered">
            <!-- Display Upcoming Events -->
            <?php
            if (count($upcoming_events) > 0) {
                foreach ($upcoming_events as $event) {
                    ?>
<div class="col-md-4 col-sm-6 col-xs-12 class">
    <div class="class-inner">
        <a href="event_details.php?id=<?php echo $event['id']; ?>">
            <!-- Display event image -->
            <?php
            $event_image = 'images/home-img-1.png'; // Default image path
            if (!empty($event['main_poster'])) {
                $image_path = str_replace('../', 'admin/', $event['main_poster']);
                // Check if the image file exists within the allowed directory
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path)) {
                    $event_image = $image_path;
                }
            }
            ?>
            <img src="<?php echo $event_image; ?>" alt="<?php echo $event['event_name']; ?>">
        </a>
        <div class="athlete-info">
            <div class="athlete-header">
                <h5 class="no-underline"><?php echo $event['event_name']; ?></h5>
                <p>Date: <?php echo $event['event_date']; ?></p>
            </div>
            <div class="athlete-details">
                <!-- Additional event details can be displayed here -->
                <p>Location: <?php echo $event['event_location']; ?></p>
            </div>
        </div>
    </div>
</div>

                    <?php
                }
            } else {
                echo "No upcoming events";
            }
            ?>
        </div>
        <div class="clear"></div>
    </div>
</section>
<!-- Section banner -->
<?php include('includes/banner.php');?>


<?php include('includes/footer.php');?>


<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/scrollme.min.js"></script>
<script src="js/matchHeight.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/pace.js"></script>
<script src="js/doubletap.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
<script src="js/gmap3.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>