<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('includes/config.php');

// Check if database connection is successful
if (!$dbh) {
    // Handle database connection error
    exit("Error: Unable to connect to the database. Please check your database configuration.");
}

// Check if event ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to events page if event ID is not provided
    header("Location: events.php");
    exit();
}

// Retrieve event data based on the event ID
$event_id = $_GET['id'];
$sql_event_details = "SELECT * FROM events WHERE id = :event_id";
$stmt_event_details = $dbh->prepare($sql_event_details);
$stmt_event_details->bindParam(':event_id', $event_id, PDO::PARAM_INT);
$stmt_event_details->execute();
$event_details = $stmt_event_details->fetch(PDO::FETCH_ASSOC);

// Retrieve fight data associated with the event
$sql_event_fights = "SELECT * FROM fights WHERE event_id = :event_id";
$stmt_event_fights = $dbh->prepare($sql_event_fights);
$stmt_event_fights->bindParam(':event_id', $event_id, PDO::PARAM_INT);
$stmt_event_fights->execute();
$fights = $stmt_event_fights->fetchAll(PDO::FETCH_ASSOC);

// Retrieve event photos
$sql_event_photos = "SELECT * FROM event_photos WHERE event_id = :event_id";
$stmt_event_photos = $dbh->prepare($sql_event_photos);
$stmt_event_photos->bindParam(':event_id', $event_id, PDO::PARAM_INT);
$stmt_event_photos->execute();

// Check if there are any event photos
if ($stmt_event_photos) {
    // Fetch event photos as an associative array
    $event_photos = $stmt_event_photos->fetchAll(PDO::FETCH_ASSOC);

    // Check if event photos exist
    if (!empty($event_photos)) {
        // Iterate over event photos
        foreach ($event_photos as $photo) {
            ?>
            <div class="col-md-3 col-sm-6">
                <img src="<?php echo $photo['photo_path']; ?>" alt="Event Photo">
            </div>
            <?php
        }
    } else {
        echo "No event photos found.";
    }
} else {
    echo "Error retrieving event photos.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $event_details['event_name']; ?> Details</title>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.nav-tabs a').click(function(){
            $(this).tab('show');
        });
    });
</script>
<style>
    /* Custom styles for tab navigation */
.nav-tabs1 {
    border-bottom: 2px solid #ccc; /* Add a border at the bottom of the tab navigation */
}

/* Custom styles for tab links */
.nav-tabs1 > li > a {
    color: #333; /* Change the color of the tab links */
    background-color: #f9f9f9; /* Add background color to the tab links */
    border: 1px solid #ccc; /* Add a border around the tab links */
    border-radius: 5px; /* Add border radius to the tab links */
    margin-right: 5px; /* Add spacing between tab links */
}

/* Custom styles for active tab */
.nav-tabs1 > li.active > a {
    color: #fff; /* Change the color of the active tab link */
    background-color: #337ab7; /* Change the background color of the active tab link */
    border-color: #337ab7; /* Change the border color of the active tab link */
}

/* Custom styles for tab content */
.tab-content {
    padding: 15px; /* Add padding around the tab content */
    border: 1px solid #ccc; /* Add a border around the tab content */
    border-top: none; /* Remove top border of the tab content */
}

/* Custom styles for individual tab panes */
.tab-pane {
    background-color: #fff; /* Add background color to tab panes */
    padding: 10px; /* Add padding inside tab panes */
}
</style>
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
                <!-- Breadcrumbs -->
                <ul class="breadcrumbs">
                    <li><a href="index.php"><i class="icon ion-home"></i></a></li>
                    <li><a href="events.php">UMC Events</a></li>
                    <li class="active"><a data-toggle="tab" href="#info">Info</a></li>
                </ul>
                </ul>
            </div>
        </div>
    </div>
</section>



<!-- Event Details Section -->
<section class="event-details">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $event_details['event_name']; ?></h2>
                <!-- Display event image -->
                <?php
                $event_image = 'images/home-img-1.png'; // Default image path
                if (!empty($event_details['main_poster'])) {
                    $image_path = str_replace('../', '', $event_details['main_poster']);
                    // Check if the image file exists within the allowed directory
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image_path)) {
                        $event_image = $image_path;
                    }
                }
                ?>
                <img src="<?php echo $event_image; ?>" alt="<?php echo $event_details['event_name']; ?>">
                <p><strong>Date:</strong> <?php echo $event_details['event_date']; ?></p>
                <p><strong>Location:</strong> <?php echo $event_details['event_location']; ?></p>
            </div>
        </div>
    </div>
</section>
<!-- Section pagination -->
<section class="green">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Breadcrumbs -->
                <ul class="breadcrumbs">
                    <li class="active"><a data-toggle="tab" href="#info">Info</a></li>
                    <li><a data-toggle="tab" href="#images">Images</a></li>
                    <li><a data-toggle="tab" href="#videos">Videos</a></li>
                </ul>
                </ul>
            </div>
        </div>
    </div>
</section><!-- Tabs Section -->
<section class="tabs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#info">Info</a></li>
                    <li><a data-toggle="tab" href="#images">Images</a></li>
                    <li><a data-toggle="tab" href="#videos">Videos</a></li>
                </ul>
                <div class="tab-content">
                    <!-- Info Tab -->
                    <div id="info" class="tab-pane fade in active">
                        <!-- Fight Details -->
                        <?php if (!empty($fights)) : ?>
                            <section class="fight-details">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Fight Details</h3>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Red Corner</th>
                                                        <th>Blue Corner</th>
                                                        <th>Style</th>
                                                        <th>Pro/Am</th>
                                                        <th>Weight Category</th>
                                                        <th>Round</th>
                                                        <th>Way of Win</th>
                                                        <th>Winner</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($fights as $fight) : ?>
                                                        <tr>
                                                            <td><?php echo !empty($fight['red_corner']) ? $fight['red_corner'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['blue_corner']) ? $fight['blue_corner'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['style']) ? $fight['style'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['proam']) ? $fight['proam'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['weightcategory']) ? $fight['weightcategory'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['round']) ? $fight['round'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['way_of_win']) ? $fight['way_of_win'] : '-'; ?></td>
                                                            <td><?php echo !empty($fight['winner']) ? $fight['winner'] : '-'; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        <?php endif; ?>
                    </div>
<!-- Images Tab -->
<div id="images" class="tab-pane fade">
    <!-- Event Photos -->
    <div class="container">
    <div class="row">
    <?php foreach ($event_photos as $photo) : ?>
        <?php
        // Replace '../' in the photo path with 'admin/'
        $photo_path = str_replace('../', 'admin/', $photo['photo_path']);
        ?>
        <div class="col-md-3 col-sm-6">
            <a href="#" class="image-link">
                <img src="<?php echo $photo_path; ?>" alt="Event Photo">
            </a>
        </div>
    <?php endforeach; ?>
</div>
    </div>
</div>
                   <!-- Videos Tab -->
<div id="videos" class="tab-pane fade">
    <!-- YouTube Videos -->
    <div class="container">
        <div class="row">
            <?php
// Retrieve fight data associated with the event, including YouTube video links
$sql_event_fights = "SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(youtube_link, '?v=', -1), '&', 1) AS video_id FROM fights WHERE event_id = :event_id";
$stmt_event_fights = $dbh->prepare($sql_event_fights);
$stmt_event_fights->bindParam(':event_id', $event_id, PDO::PARAM_INT);
$stmt_event_fights->execute();
$fights = $stmt_event_fights->fetchAll(PDO::FETCH_ASSOC);

// Check if there are any fights associated with the event
if (!empty($fights)) {
    // Iterate over fights
    foreach ($fights as $fight) {
        // Extract video ID from YouTube link
        $video_id = $fight['video_id'];
        if (!empty($video_id)) {
            ?>
            <div class="col-md-4">
                <iframe width="100%" height="250" src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <?php
        }
    }
} else {
    echo "No fights found for this event.";
}
            ?>
        </div>
    </div>
</div>


</section>

<!-- Section banner -->
<?php include('includes/banner.php');?>

<?php include('includes/footer.php');?>

<!-- HTML for Modal and Image Display -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" id="modalImage" alt="Modal Image">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="prevImage">Previous</button>
                <button type="button" class="btn btn-secondary" id="nextImage">Next</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Attach click event listener to each image link
    $('.image-link').click(function(e) {
        e.preventDefault(); // Prevent the default anchor behavior
        var imagePath = $(this).find('img').attr('src').replace('../', 'admin/');
        $('#modalImage').attr('src', imagePath);
        $('#imageModal').modal('show'); // Show modal with the selected image
    });

    // Variable initialization
    var images = <?php echo json_encode($event_photos); ?>;
    var currentIndex = 0;

    // Show next image when Next button is clicked
    $('#nextImage').click(function() {
        currentIndex = (currentIndex + 1) % images.length;
        var nextImagePath = images[currentIndex]['photo_path'].replace('../', 'admin/');
        $('#modalImage').attr('src', nextImagePath);
    });

    // Show previous image when Previous button is clicked
    $('#prevImage').click(function() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        var prevImagePath = images[currentIndex]['photo_path'].replace('../', 'admin/');
        $('#modalImage').attr('src', prevImagePath);
    });

    // Show the first image in the modal when it's opened
    $('#imageModal').on('show.bs.modal', function (e) {
        var firstImagePath = images[0]['photo_path'].replace('../', 'admin/');
        $('#modalImage').attr('src', firstImagePath);
    });
});
</script>

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
