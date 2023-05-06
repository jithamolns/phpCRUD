<?php
  require_once("header.php");
  require_once("./inc/connection.php");

  $id = '';
  $albumName = '';
  $artistName = '';
?>


    <div class="back-btn container pt-5">
      <a href="#" onclick="history.back(1);">-Back</a>
    </div>

    <?php
      $albumErr = $artistErr = '';
      $name = $artistName = '';

      if($_SERVER['REQUEST_METHOD']=='GET'){
        //showing client data
        $id = $_GET['id'];

        $sqlSelect = "SELECT albums.AlbumId, albums.Title, artists.Name, albums.ArtistId from artists,albums WHERE AlbumId = $id AND artists.ArtistId = albums.ArtistId";
        $result = $conn->query($sqlSelect);
        $row = $result->fetch_assoc();

        $name = $row['Title'];
        $artistId = $row['ArtistId'];
        $artistName = $row['Name'];

        // $selectTracks = "SELECT TrackId, Name from tracks WHERE AlbumId = $id";
        // $selectTrackResult = $conn->query($selectTracks);

      }
      else{
        // update data
        $id = $_POST['id'];
        $artistId = $_POST['artistId'];
        $removedIds = $_POST['removedTracklist'];

        if(empty($_POST["album"])){
          $albumErr = "Album is required";
        }else{
          $name = $_POST['album'];
          // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z0-9 _.\[\],!()-]+$/",$name)) {
                $albumErr = "Invalid album name";
            }
        }

        if (empty($_POST["artist"])) {
          $artistErr = "Artist is required";
        } else {
          $artistName = $_POST['artist'];
        }

        if($removedIds){
          $removedIdArray = explode(",",$removedIds);
        }

        if($albumErr == "" && $artistErr == ""){
          $sqlUpdate = "UPDATE albums,artists SET albums.Title='$name', artists.Name='$artistName' WHERE albums.AlbumId = '$id' AND artists.ArtistId = '$artistId'";
          $result = $conn->query($sqlUpdate);

          $is_success = true;
          if($result){
            if (isset($_POST["tracks"])){
              $is_success = updateTracks($id, $conn);
            }
            if($removedIds){
              $is_success = removeTracks($removedIdArray, $conn);
            }
            if(isset($_POST["newTracks"])){
              $is_success = insertNewTracks($id, $conn);
            }
            if($is_success){
              header("location: ./index.php?msg=updated");
            }else{
              echo "Update Failed";
            }
          }
        }else {
            $errorMsg = "Please fill the mandatory fields";
        }
      }



      function updateTracks($albumId, $conn){
        //Getting post values
        if(isset($_POST["tracks"])){
          $count = count($_POST["tracks"]);
          $tracks = $_POST["tracks"]; //Track Names
        }

        $trackIds = $_POST["tracklist"]; //Track Ids (for Update)
        $trackIDArray = explode(",",$trackIds);

        $query = "SELECT TrackId from tracks WHERE AlbumId = $albumId";
        $queryResult = $conn->query($query);

        while($row = $queryResult->fetch_assoc()) {
          $trackId[] = $row['TrackId'];
        }

        if($count >= 1)
        {
          if($trackIDArray>1){

            for($i=0; $i<$count; $i++)
            {
              if(trim($_POST["tracks"][$i] != ''))
              {
                $Updatequery = "UPDATE tracks SET Name = '$tracks[$i]'  WHERE TrackId = $trackId[$i]";
                $queryResult = $conn->query($Updatequery);
              }
            }
          }
        }
        return $queryResult;

      }

      function insertNewTracks($albumId, $conn){
        if(isset($_POST["newTracks"]) ){
          $newTrackNames = $_POST["newTracks"];

          $newTracks = $_POST["newtracklist"]; //Track Ids (for Insert)
          $newTracksArray = explode(",",$newTracks);
          $newarrycount = count($newTracksArray);

          if($newarrycount>=1){
            if($newTracksArray>1){
              for($i=0; $i<$newarrycount; $i++)
              {
                if(trim($_POST["newTracks"][$i] != ''))
                {
                  $insertQuery = "INSERT INTO `tracks`(`Name`, `AlbumId`) VALUES ('$newTrackNames[$i]','$albumId')";
                  $insertQueryResult = $conn->query($insertQuery);
                }
              }
            }
          }
        }
        return $insertQueryResult;
      }

      function removeTracks($removedIdArray, $conn){
        $removedIdArrayCount = count($removedIdArray);
        if($removedIdArray>1){
          for($i=0; $i<$removedIdArrayCount; $i++)
          {
              $deleteQuery = "DELETE FROM `tracks` WHERE TrackId = $removedIdArray[$i]";
              $deleteQueryResult = $conn->query($deleteQuery);
          }
        }
        return $deleteQueryResult;
      }
    ?>

      <section class="update-form-section pb-5">
        <div class="container pt-5">
          <h4 class="pb-4"><i>Update album</i></h4>
          <form class="" action="update.php?id=<?php echo $id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="artistId" value="<?php echo $artistId;?>">

            <!-- Tracks ids -->
            <input type="hidden" name="tracklist" value="" id="trackId">
            <input type="hidden" name="newtracklist" value="" id="newTrackIds">
            <input type="hidden" name="removedTracklist" value="" id="removedIds">

            <div class="form-group mb-3">
              <label>Album</label>
              <input type="text" name="album" value="<?php echo $name;?>" class="form-control">
              <i class="error"><?php echo $albumErr; ?> </i>
            </div>
            <div class="form-group mb-3">
              <label>Artist</label>
              <input type="text" name="artist" value="<?php echo $artistName;?>" list="artistName" class="form-control">
              <i class="error"><?php echo $artistErr; ?> </i>
              <datalist id="artistName">
              <?php
                $sqlQuery = "SELECT Name, ArtistId from artists";
                $results = $conn->query($sqlQuery);
                while($row = $results->fetch_assoc()){
              ?>
                  <option value="<?php echo $row['Name']; ?>">
              <?php } ?>
              </datalist>
            </div>
            <div class="form-group mb-3">
              <label>Tracks</label>
              <div id="dynamic_field">
                <?php $count = 1; ?>
                <?php
                  $selectTracks = "SELECT TrackId, Name from tracks WHERE AlbumId = $id";
                  $selectTrackResult = $conn->query($selectTracks);
                  while($row = $selectTrackResult->fetch_assoc()) {?>
                    <div class="input-group mb-3">
                      <div class="input-group-append me-3">
                        Track <i class="track_num"><?php echo $count;?></i>
                      </div>
                      <input type="text" name="tracks[]" value="<?php echo $row['Name'];?>" class="form-control mb-3" data-id="<?php echo $row['TrackId']; ?>">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary btn_remove" type="button" data-id="<?php echo $row['TrackId']; ?>">Remove Track</button>
                      </div>
                    </div>
                <?php $count++; }?>
              </div>
            </div>
            <button type="button" name="add" id="add" class="btn btn-secondary">Add Track</button>
            <input type="submit" name="submit" value="Update Album" class="btn btn-primary">
          </form>
        </div>
      </section>

      <!-- Scripts -->
      <script src="./js/bootstrap.bundle.min.js"></script>
      <script src="./js/jquery-3.6.1.min.js"></script>

      <script>

        $(document).ready(function(){

          var ids = [];
          var newIds = [];

          var len = $('input[name="tracks[]"]').length;

          $('#add').click(function(){
              len++;
              $('#dynamic_field').append('<div class="input-group mb-3">'+
                '<div class="input-group-append me-3">Track <i class="track_num">'+len+
                '</i></div>'+
                '<input type="text" name="newTracks[]" value="" class="form-control mb-3 new-track" data-id="NULL">'+
                '<div class="input-group-append">'+
                  '<button class="btn btn-outline-secondary btn_remove" type="button">Remove Track</button>'+
                '</div>'+
              '</div>');
              $('#dynamic_field .btn_remove').attr("id", len);
              // $(".new-track").each(function(){
                newIds.push($('.new-track').attr("data-id"));
                 $('#newTrackIds').val(newIds);
              // });
          });


          $("input[name='tracks[]']").each(function(){
            ids.push($(this).attr("data-id"));
             $('#trackId').val(ids.join());
          });


          var removeIds = [];

          $(document).on('click', '.btn_remove', function(){
            removeIds.push($(this).attr("data-id"));
            $('#removedIds').val(removeIds);

            if (len >= 1) {
               $(this).parent().parent().remove();
               resetIndexes();
             }
             len--;
             return false;
          });


          function resetIndexes() {
            var j = 1;
            var $this;

            // for each element on the page with the class .input-wrap
            $('#dynamic_field .input-group').each(function() {
              if (j >= 1) {
                // within each matched .input-wrap element, find each <input> element
                $(this).find('.track_num').each(function() {
                  $this = $(this);
                  $(this).html(j);
                })
              }
              j++;
            });
          }

        });
      </script>
  </body>
</html>
<?php
  $conn->close();
?>
