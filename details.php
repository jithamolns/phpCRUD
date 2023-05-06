<?php
  require_once("header.php");
  require_once('./inc/connection.php');
?>

    <!-- Back button -->
    <div class="back-btn container pt-5">
      <a href="#" onclick="history.back(1);">-Back</a>
    </div>

    <!-- Main section -->
    <section class="pt-3">
      <div class="container">
        <?php
            $id = $_GET['id'];
            // Query to select records
            $sqlSelect = "SELECT albums.AlbumId, albums.Title, artists.Name, albums.ArtistId from artists,albums WHERE AlbumId = $id AND artists.ArtistId = albums.ArtistId";
            $result = $conn->query($sqlSelect);
            $row = $result->fetch_assoc();
            //Album name and artist name
            $name = $row['Title'];
            $artistName = $row['Name'];
        ?>
        <h5 class="mb-4"><?php echo $name; ?>(<?php echo $artistName; ?>)</h5>
        <h6><u>Tracks</u></h6>
        <?php
        // Selecting the tracks associated with the album selected
          $selectTracks = "SELECT Name from tracks WHERE AlbumId = $id";
          $result = $conn->query($selectTracks);
          if($result->num_rows > 0){
        ?>
        <ol>
          <?php while($row = $result->fetch_assoc()){?>
            <li><?php echo $row['Name']; ?></li>
          <?php }?>
        </ol>
      <?php } else {?> <!-- No records -->
        <p>Oops! No tracks found for this album</p>
        <a href="update.php?id=<?php echo $id;?>" class="btn btn-secondary">Add new tracks</a>
      <?php } ?>
      </div>
    </section><!-- Main section end -->

  </body> <!-- End of body -->
</html><!-- End of html -->
