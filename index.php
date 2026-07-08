
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ULTIMATE MARTIAL CHAMPIONSHIP</title>
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ionicons.min.css" rel="stylesheet">
    <link href="css/socicon.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php include('includes/menu.php');?>
   
   
   <!-- Hero -->
    <section class="hero" id="top">
      <!-- Navigation -->
      <div class="navbar" id="scroll_to" role="navigation">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
              <a href="index.php" title="UMC">
                 <img src="images/logo.png" alt="UMC" height="100%" width="100%">              </a>
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

      <!-- Scroll -->
      <div class="scroll-down">
        <img src="images/scroll.svg" alt="Scroll down for more content">
        <p>Scroll Down</p>
      </div>

      <!-- Hero content -->
      <div id="carousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#carousel" data-slide-to="0" class="active"></li>
          <li data-target="#carousel" data-slide-to="1"></li>
          <li data-target="#carousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">

          <!-- Slide one -->
          <div class="item active"> 
            <div class="container">
              <div class="row blurb">
                <div class="col-md-push-1 col-md-10 col-sm-12 blurb-content">
                  <h1 class="no-underline">YOU CAN WATCH OUR EVENTS ONLINE WITH <span>PPV</span></h1>
                  <p>OR CHECK PAST EVENTS</p>
                  <a href="PPV.php" class="btn btn-default">WATCH PPV</a> 
                  <a href="event.php" class="btn btn-primary">EVENTS</a>
                </div>
              </div>
            </div>
            <img src="images/hero-1.jpg" alt="UMC MMA">
          </div>
          <!-- Slide two -->
          <div class="item">
            <div class="container">
              <div class="row blurb">
                <div class="col-md-push-1 col-md-10 col-sm-12 blurb-content">
                  <h1 class="no-underline">MMA - K1 - GRAPPLING - BOXING <span>ALL-IN-ONE</span></h1>
                  <p>ONE EVENT TO WATCH THEM ALL</p>
                  <a href="about.php" class="btn btn-default">Tell me more</a> 
                  <a href="upcoming_events.php" class="btn btn-primary">Upcoming Events</a>
                </div>
              </div>
            </div>
            <img src="images/hero-2.jpg" alt="UMC">
          </div>
          <!-- Slide three -->
          <div class="item">
            <div class="container">
              <div class="row blurb">
                <div class="col-md-push-1 col-md-10 col-sm-12 blurb-content">
                  <h1 class="no-underline">THE <span>UMC</span> SHOW</h1>
                  <p>WATCH AND LISTEN THE LATEST NEWS FROM MMA WORLD AT OUR SHOW</p>
                  <a href="umcshow.html" class="btn btn-default">THE UMC SHOW</a> 
                  <a href="past_events.php" class="btn btn-primary">PAST EVENTS</a>
                </div>
              </div>
            </div>
            <img src="images/hero-3.jpg" alt="UMC MMA">
          </div>
          <!-- Slides end -->
        </div>
      </div>
    </section>

    <!-- Section links -->
    <section>
      <div class="container-fluid">
        <div class="row">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 title-block">
              <header>
                <h2 class="no-underline"><a href="PPV.php" title="PAY PER VIEW">PPV</a></h2>
              </header>
              <aside>
                <p>WATCH UMC LIVE AND ONLINE</p>
                <a href="PPV.php" class="btn">More information</a>
              </aside>
              <img src="images/home-img-1.png" alt="">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 title-block">
              <header>
                <h2 class="no-underline"><a href="upcoming_events.php" title="GALLERY">UPCOMING EVENTS</a></h2>
              </header>
              <aside>
                <p>VIDEOS AND PHOTOS FROM OUR EVENTS</p>
                <a href="past_events.php" class="btn">More information</a>
              </aside>
              <img src="images/home-img-2.png" alt="">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 title-block">
              <header>
                <h2 class="no-underline"><a href="events.php" title="EVENTS">EVENTS</a></h2>
              </header>
              <aside>
                <p>MORE INFO ABOUT OUR EVENTS</p>
                <a href="events.php" class="btn">More information</a>
              </aside>
              <img src="images/home-img-3.png" alt="">
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 title-block">
              <header>
                <h2 class="no-underline"><a href="#" title="THE UMC SHOW">THE UMC SHOW</a></h2>
              </header>
              <aside>
                <p>CLICK TO WATCH OUR SHOW.</p>
                <a href="umcshow.html" class="btn">More information</a>
              </aside>
              <img src="images/home-img-4.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section about -->
    <section>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 dark padding-90-90" style="background-image: url('images/background-1.png')">
            <h2>About UMC</h2>
            <blockquote> “Excellence is not a skill, excellence is an attitude.”

— Conor McGregor</blockquote>

            <p>Our priotity is to be excellent in everything. We try our best to bring the best events while assuring the athletes safety. We only work with first class referees, doctors and cutman.</p>

            <p><a href="about.php" class="btn btn-default">Read more</a></p>
          </div>
          <div class="col-md-6 scrollme animateme"  data-when="enter" data-from="1" data-to="0.1" data-opacity="0" data-translatex="250">
            <div class="row">
              <div class="col-md-6 col-sm-6 icon-block">
                <div class="wrap">
                  <i class="icon ion-ios-people"></i>
                  <h3 class="no-underline">Open door policy</h3>
                  <p>Everybody is welcome to our event, no matter what age, gender or race.</p>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 icon-block">
                <div class="wrap">
                  <i class="icon ion-ios-medkit"></i>
                  <h3 class="no-underline">SAFETY FIRST</h3>
                  <p>At all our events we have proffessional medics and cutmen</p>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 icon-block">
                <div class="wrap">
                  <i class="icon ion-trophy"></i>
                  <h3 class="no-underline">BEST FIGHTERS</h3>
                  <p>High level athletes to make sure you enjoy spectacular fights</p>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 icon-block">
                <div class="wrap">
                  <i class="icon ion-calendar"></i>
                  <h3 class="no-underline">Event Dates</h3>
                  <p>our next event will be at 6 May</p>
                </div>
              </div>
            </div>
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