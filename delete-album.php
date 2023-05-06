<?php
  require_once("header.php"); //Including header
  require_once("./inc/connection.php");//Database connection
?>

      <div class="back-btn container pt-5">
        <a href="#" onclick="history.back(1);">-Back</a>
      </div>

      <section class="content-area py-5">
        <div class="container">
          <?php

            $albumId = $_GET['id'];
            $sqlSelect = "SELECT albums.AlbumId, albums.Title, artists.Name, albums.ArtistId from artists,albums WHERE AlbumId = $albumId AND artists.ArtistId = albums.ArtistId";
            $result = $conn->query($sqlSelect);
            $row = $result->fetch_assoc();
            //
            $name = $row['Title'];
            $artistName = $row['Name'];
          ?>
          <h5><u><?php echo $name; ?>(<?php echo $artistName; ?>)</u></h5>
          <h6>Tracks</h6>
          <?php
            $selectTracks = "SELECT Name from tracks WHERE AlbumId = $albumId";
            $result = $conn->query($selectTracks);
          ?>
          <ol>
            <?php while($row = $result->fetch_assoc()){?>
              <li><?php echo $row['Name']; ?></li>
            <?php }?>
          </ol>
          <p>Do you want to delete this album?</p>
          <button type="button" name="button" id="<?php echo $albumId; ?>" class="del-btn btn btn-outline-secondary">Delete</button>
        </div>
      </section>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.6.1.min.js"></script>

    <script>
        $(".del-btn").click(function(e){
          var id = $(this).attr("id");
          if(confirm('Are you sure to remove this record ?'))
          {
            window.location.href = "./inc/delete.php?id="+id;
          }
        });
    </script>
  </body>
</html>
