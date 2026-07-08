<?php
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ULTIMATE MARTIAL CHAMPIONSHIP - UMC</title>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
    // Fetch unique weight categories
    $sql = "SELECT DISTINCT category FROM category";
    $query = $dbh->prepare($sql);
    $query->execute();
    $weightCategories = $query->fetchAll(PDO::FETCH_COLUMN);

    // Fetch unique sex values
    $sql = "SELECT DISTINCT sex FROM tblathletes";
    $query = $dbh->prepare($sql);
    $query->execute();
    $sexOptions = $query->fetchAll(PDO::FETCH_COLUMN);

    // Fetch unique pro/am values
    $sql = "SELECT DISTINCT proam FROM tblathletes";
    $query = $dbh->prepare($sql);
    $query->execute();
    $proAmOptions = $query->fetchAll(PDO::FETCH_COLUMN);

    // Fetch unique country values
    $sql = "SELECT DISTINCT country FROM tblathletes";
    $query = $dbh->prepare($sql);
    $query->execute();
    $countryOptions = $query->fetchAll(PDO::FETCH_COLUMN);
    ?>

    <script>
        $(document).ready(function() {
    // Function to handle filtering
    function filterAthletes() {
        var searchValue = $('#searchInput').val().toLowerCase();
        var weightCategory = $('#weightCategory').val().toLowerCase();
        var sex = $('#sex').val().toLowerCase();
        var proAm = $('#proAm').val().toLowerCase();
        var country = $('#country').val().toLowerCase();

        $('.class').show(); // Clear previous filter

        $('.class').each(function() {
            var athleteName = $(this).find('h5').text().toLowerCase();
            var athleteWeightCategory = $(this).data('weight-category').toLowerCase();
            var athleteSex = $(this).data('sex').toLowerCase();
            var athleteProAm = $(this).data('pro-am').toLowerCase();
            var athleteCountry = $(this).data('country').toLowerCase();

            var shouldShow =
                (athleteName.includes(searchValue) || searchValue === '') &&
                (athleteWeightCategory === weightCategory || weightCategory === '') &&
                (athleteSex === sex || sex === '') &&
                (athleteProAm === proAm || proAm === '') &&
                (athleteCountry === country || country === '');

            $(this).toggle(shouldShow);
        });
    }

    // Trigger filtering function on page load
    filterAthletes();

    // Event listeners for dropdowns
    $('#weightCategory, #sex, #proAm, #country').on('change', filterAthletes);

    // Event listener for search input
    $('#searchInput').on('keyup', filterAthletes);
});

    </script>
<style>
    .class {
        position: relative;
        overflow: hidden;
    }

    .class-inner {
        position: relative;
        overflow: hidden;
        height: 300px; /* Set a fixed height for all images */
        text-align: center;
    }

    .class img {
        width: auto;
        height: 100%;
        object-fit: cover; /* Ensure the image covers the entire container */
    }

    .athlete-info {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 10px;
        text-align: left;
        transition: opacity 0.3s ease;
        opacity: 0; /* Initially hide */
    }

    .class:hover .athlete-info {
        opacity: 1; /* Show on hover */
    }

    .athlete-info .athlete-header {
        display: flex;
        justify-content: space-between;
    }

    .athlete-info .athlete-details {
        display: none;
        margin-top: 10px;
    }

    .class:hover .athlete-info .athlete-details {
        display: block; /* Show on hover */
    }

    .athlete-info h5,
    .athlete-info p {
        margin: 0;
    }

    /* Adjust text padding */
    .athlete-info h5 {
        padding: 5px 0;
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
                <h1 class="no-underline">Athletes</span></h1>
                <p>UMC ROSTER&nbsp;&nbsp;</p>
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
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Section gallery -->
<section class="dark-2">
    <div class="container padding-60">
        <div class="row">
            <div class="col-md-8 col-md-push-2">
                <img src="images/dark-arrow.svg" alt="Scroll down" class="scroll-arrow centered">
                <h2 class="centered">UMC ATHLETES</h2>
                <p class="centered">MEET OUR FIGHTERS</p>
            </div>
        </div>
        <div class="row classes centered">
            <input type="text" id="searchInput" placeholder="Search for athlete..">
            <select id="weightCategory">
                <option value="">Select Weight Category</option>
                <?php foreach ($weightCategories as $category) { ?>
                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                <?php } ?>
            </select>
            <select id="sex">
                <option value="">Select Sex</option>
                <?php foreach ($sexOptions as $sex) { ?>
                    <option value="<?php echo $sex; ?>"><?php echo $sex; ?></option>
                <?php } ?>
            </select>
            <select id="proAm">
                <option value="">Select Pro/Am</option>
                <?php foreach ($proAmOptions as $proAm) { ?>
                    <option value="<?php echo $proAm; ?>"><?php echo $proAm; ?></option>
                <?php } ?>
            </select>
            <select id="country">
                <option value="">Select Country</option>
                <?php foreach ($countryOptions as $country) { ?>
                    <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                <?php } ?>
            </select>
<p></p>
            <?php
            // Fetch athlete data from the database
            $sql = "SELECT * FROM tblathletes";
            $query = $dbh->prepare($sql);
            $query->execute();
            $athletes = $query->fetchAll(PDO::FETCH_ASSOC);

            // Loop through each athlete and display their information
            foreach ($athletes as $athlete) {
                // Construct the image path
                $imagePath = empty($athlete['image']) ? "images/profile.jpeg" : "admin/images/athletes/" . $athlete['image'];
                ?>
                <div class="col-md-3 col-sm-4 col-sm-12 class"
                     data-weight-category="<?php echo strtolower($athlete['category']); ?>"
                     data-sex="<?php echo strtolower($athlete['sex']); ?>"
                     data-pro-am="<?php echo strtolower($athlete['proam']); ?>"
                     data-country="<?php echo strtolower($athlete['country']); ?>">
                    <div class="class-inner">
                        <a href="athlete_details.php?id=<?php echo $athlete['id']; ?>">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $athlete['athleteName']; ?>">
                        </a>
                        <div class="athlete-info">
                            <div class="athlete-header">
                                <h5 class="no-underline"><?php echo $athlete['athleteName']; ?></h5>
                                <p><?php echo $athlete['record']; ?></p>
                            </div>
                            <div class="athlete-details">
                                <p>Gym: <?php echo $athlete['gym']; ?></p>
                                <p>Country: <?php echo $athlete['country']; ?></p>
                                <p>Birthdate: <?php echo $athlete['birthdate']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
        <div class="row btns centered">
            <div class="col-md-12">
            </div>
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
