<?php
// Include necessary files and start session if required
include('includes/config.php');
session_start();

// Fetch athlete data from the database based on the ID passed through GET parameter
$aid = intval($_GET['id']);
$sql = "SELECT * FROM tblathletes WHERE id = :aid";
$query = $dbh->prepare($sql);
$query->bindParam(':aid', $aid, PDO::PARAM_INT);
$query->execute();
$athlete = $query->fetch(PDO::FETCH_ASSOC);

// Check if the athlete record exists
if (!$athlete) {
    echo "Athlete not found";
    exit; // or redirect to an error page
}

// Display athlete details
 // Construct the image path
 $imagePath = empty($athlete['image']) ? "images/profile.jpeg" : "admin/images/athletes/" . $athlete['image'];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $athlete['athleteName']; ?> Details | UMC</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ionicons.min.css" rel="stylesheet">
    <link href="css/venobox.css" rel="stylesheet">
    <link href="css/socicon.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Add your CSS stylesheets here -->
    <style>
        /* Add your CSS styles for formatting here */
        .athlete-details {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .athlete-image {
            text-align: center;
        }
        .athlete-image img {
    max-width: 200px;
    height: auto;
}
        .social-icons {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
            text-align: center;
        }
        .social-icons li {
            display: inline;
            margin: 0 5px;
        }
        .social-icons li a {
            color: #333;
            font-size: 24px;
        }
        .social-icons li a i {
            vertical-align: middle;
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
              <a href="index.html" title="UMC">
                <img src="images/logo.png" alt="UMC"height="40%" width="40%">
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
            <h1 class="no-underline"><?php echo $athlete['athleteName']; ?></span></h1>
            <p>Details</p>
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
              <li><a href="athletes.php">UMC ROSTER</li></a>
              <li> Details <?php echo $athlete['athleteName']; ?></h2>
 </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="dark-2">
    <div class="athlete-details">
        <div class="athlete-image">
        <img src="<?php echo $imagePath; ?>" alt="<?php echo $athlete['athleteName']; ?>">
        </div>
        <div class="row classes centered">

        <h2 style="color: black;"><?php echo $athlete['athleteName']; ?></h2>
        <?php
// Assuming $athlete is an array containing athlete data fetched from the database
if (!empty($athlete['nickname'])) {
    echo "<p>Nickname: " . $athlete['nickname'] . "</p>";
}
?>
        <p>Birthdate: <?php echo $athlete['birthdate']; ?></p>
        <p>Birthplace: <?php echo $athlete['birthplace']; ?></p>
        <p>GYM: <?php echo $athlete['gym']; ?></p>
        <p>Country: <?php echo $athlete['country']; ?></p>
        <?php if (!empty($athlete['funFacts'])) : ?>
    <p>Fun Facts: <?php echo htmlspecialchars($athlete['funFacts']); ?></p>
<?php endif; ?>
        <p>PRO / AM: <?php echo $athlete['proam']; ?></p>
        <ul class="social-icons">
    <?php if (!empty($athlete['instagram'])) : ?>
        <li><a href="<?php echo $athlete['instagram']; ?>" target="_blank" class="socicon-instagram"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($athlete['facebook'])) : ?>
        <li><a href="<?php echo $athlete['facebook']; ?>" target="_blank" class="socicon-facebook"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($athlete['tiktok'])) : ?>
        <li><a href="<?php echo $athlete['tiktok']; ?>" target="_blank"><i class="bi bi-tiktok"></i></a></li>
    <?php endif; ?>
    <?php if (!empty($athlete['twitter'])) : ?>
        <li><a href="<?php echo $athlete['twitter']; ?>" target="_blank"><i class="socicon-twitter"></i></a></li>
    <?php endif; ?>
    <p>
    <?php if (!empty($athlete['tapology'])) : ?>
        <li><a href="<?php echo $athlete['tapology']; ?>" target="_blank"><img src="images/tapology.png" alt="Tapology" style="width: 70x; height: 70px;"></a></li>
    <?php endif; ?>
    <?php if (!empty($athlete['sherdog'])) : ?>
        <li><a href="<?php echo $athlete['sherdog']; ?>" target="_blank"><img src="images/sherdog.png" alt="Sherdog" style="width: 70px; height: 70px;"></a></li>
    <?php endif; ?>
    </p>
    <!-- Add other link/image pairs similarly -->
</ul>


        <p>Record: <?php echo $athlete['record']; ?>  W-L-D</p>
        <p>Category: <?php echo $athlete['category']; ?></p>
        <p>Sex: <?php echo $athlete['sex']; ?></p>
        <!-- Display other athlete details similarly -->
    </div>
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
