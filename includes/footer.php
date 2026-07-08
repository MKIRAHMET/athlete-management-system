<?$currentYear = date('Y');
?>
<!-- Section footer -->
<section class="dark large-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 hours padding-90 centered">
                <h4>NEXT EVENTS</h4>
                <div class="time">
                    <?php
                    // Fetch checked events from the database
                    $sql = "SELECT * FROM events WHERE footer_checked = 1";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($events) {
                        foreach ($events as $event) {
                            echo '<p><i class="icon ion-calendar"></i> ' . $event['event_name'] . ' <span class="day">' . date('j F', strtotime($event['event_date'])) . '<br />' . $event['event_location'] . '</span></p>';
                        }
                    } else {
                        echo '<p class="day">No upcoming events</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 connect padding-90">
                <h4 class="centered">Connect with us</h4>
                <ul class="social-icons">
                    <li><a href="https://www.instagram.com/umc.mma/" target="_blank" class="socicon-instagram"></a></li>
                    <li><a href="https://www.facebook.com/profile.php?id=100083352812132" target="_blank" class="socicon-facebook"></a></li>
                    <li><a href="https://www.youtube.com/channel/UCj6CjfuD32xP51O56bdvFeg" target="_blank" class="socicon-youtube"></a></li>
                    <li><a href="https://www.tiktok.com/@umcmma" target="_blank">
  <i class="bi bi-tiktok"></i>
</a>
</li>

                </ul>
            </div>
        </div>
    </div>
</section>

    <!-- Section small footer -->
    <section class="section footer no-margin-top">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-5 copyright">
            <p>&copy; UMC MMA <?echo "$currentYear";?>. All rights reserved.<br />
            <a href="https://www.instagram.com/muhammedkirahmet/" title="Website design by M KIR AHMET">Website design by M KIR AHMET</a></p>
          </div>
          <!-- Social icons -->
          <div class="col-md-6 col-sm-7">
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="about.php">About</a></li>
              <li><a href="contact.php">Contact</a></li>
            </ul>
          </div>
        </div>
      </div>
    </section>
