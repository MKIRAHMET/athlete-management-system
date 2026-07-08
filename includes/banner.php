<section class="green padding-50">
  <div class="container">
    <?php
    session_start();
    include('includes/config.php');

    // Fetch banner data from the database
    $sql = "SELECT * FROM banners ORDER BY created_at DESC LIMIT 1"; // Fetch the latest banner
    $result = $dbh->query($sql); // Use $dbh instead of $conn

    if ($result->rowCount() > 0) {
        // Output data of the latest banner
        $banner = $result->fetch(PDO::FETCH_ASSOC);
        echo '<span class="col-md-10 col-sm-10"><img src="admin/images/banner/' . $banner['filename'] . '" alt="' . $banner['description'] . '"/></span>';
    } else {
        echo "No banners found";
    }
    ?>
    <div class="col-md-10 col-sm-10"></div>
  </div>
</section>
