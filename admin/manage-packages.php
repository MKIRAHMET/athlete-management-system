<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Redirect to the login page
    header("Location: ../../login.php");
    exit; // Terminate script to prevent further execution
} else {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>ΔΙΑΧΕΙΡΙΣΗ ΑΘΛΗΤΩΝ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<!-- Graph CSS -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery-2.1.4.min.js"></script>
<!-- //jQuery -->
<!-- tables -->
<style>
           #searchInput {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
 /* Default table styles */
 table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            /* Adjust table layout for smaller screens */
            th, td {
                padding: 6px;
            }
            th {
                font-size: 12px;
            }
            td {
                font-size: 14px;
            }
        }
    </style>
<link rel="stylesheet" type="text/css" href="css/table-style.css" />
<link rel="stylesheet" type="text/css" href="css/basictable.css" />
<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
<script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#table').basictable();

      $('#table-breakpoint').basictable({
        breakpoint: 768
      });

      $('#table-swap-axis').basictable({
        swapAxis: true
      });

      $('#table-force-off').basictable({
        forceResponsive: false
      });

      $('#table-no-resize').basictable({
        noResize: true
      });

      $('#table-two-axis').basictable();

      $('#table-max-height').basictable({
        tableWrapper: true
      });
    });
</script>
<!-- //tables -->
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<!-- lined-icons -->
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
<!-- //lined-icons -->
</head> 
<body>
<div class="page-container">
       <div class="left-content">
           <div class="mother-grid-inner">
              <!-- Header -->
              <?php include('includes/header.php');?>
              <div class="clearfix"> </div>	
           </div>
           <!-- Breadcrumb -->
           <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a><i class="fa fa-angle-right"></i>ΔΗΜΙΟΥΡΓΙΑ ΠΡΟΦΙΛ ΑΘΛΗΤΗ</li>
            </ol>
    <div class="agile-grids">	
        <div class="agile-tables">
            <div class="w3l-table-info">
                <h2>ΔΙΑΧΕΙΡΙΣΗ ΑΘΛΗΤΩΝ</h2>
                <li> <input type="text" id="searchInput" placeholder="Search for athlete.."></li>

                <table id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ΟΝΟΜΑ</th>
                            <th>NICKNAME</th>
                            <th>GYM</th>
                            <th>ΧΩΡΑ</th>
                            <th>RECORD</th>
                            <th>ΚΑΤΗΓΟΡΙΑ</th>
                            <th>ΕΝΕΡΓΕΙΕΣ</th> <!-- Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
$sql = "SELECT id, athleteName, nickname, gym, country, record, category FROM tblathletes";
$query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if($query->rowCount() > 0) {
                            foreach($results as $result) {
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result->athleteName);?></td>
                            <td><?php echo htmlentities($result->nickname);?></td>
                            <td><?php echo htmlentities($result->gym);?></td>
                            <td><?php echo htmlentities($result->country);?></td>
                            <td><?php echo htmlentities($result->record);?></td>
                            <td><?php echo htmlentities($result->category);?></td>
                            <td>
                            <a href="update-package.php?pid=<?php echo htmlentities($result->id);?>">
    <button type="button" class="btn btn-primary btn-block">ΠΡΟΒΟΛΗ ΛΕΠΤΟΜΕΡΕΙΩΝ</button>
</a>
<br>
<a href="delete-package.php?pid=<?php echo htmlentities($result->id);?>">
    <button type="button" class="btn btn-danger btn-block">ΔΙΑΓΡΑΦΗ</button>
</a>

    </td>
</tr>                     
                        <?php $cnt = $cnt + 1; } }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include('includes/footer.php');?>
    
    <!-- Include sidebar menu -->
    <?php include('includes/sidebarmenu.php');?>
    
    <!-- JavaScript and jQuery -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
