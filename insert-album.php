<?php
    require_once('header.php'); //Including header
    require_once('./inc/connection.php'); //Database connection

    $trackID = array(); // Array for storing the trackId's

    // Validation section and Error messages
    $successMsg = $errorMsg = "";
    $albumName = $selectOption = "";
    $albumErr = $selectOptionErr = "";

    function input_data($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    if(isset($_POST['submit'])){
      // Validating Empty Fields
      if (empty($_POST["album"])) {
        $albumErr = "Album is required";
      } else {
        $albumName = input_data($_POST["album"]);
        // validating special characters
        if (!preg_match("/^[a-zA-Z0-9 _.\[\],!()-]+$/",$albumName)) {
            $albumErr = "Invalid album name";
        }
      }

      if (empty($_POST["artistName"])) {
        $selectOptionErr = "Artist is required";
      } else {
        $selectOption = input_data($_POST["artistName"]);
      }


      if($albumErr == "" && $selectOptionErr == "" ){ //=========================== All fields are entered

        $fetchQuery = "SELECT ArtistId FROM artists WHERE Name = '$selectOption'";
        $result = $conn->query($fetchQuery);//============================= Artist table data

        if($result->num_rows > 0){//======================================= Checking if the entered artist exists, If yes,
            $row = $result->fetch_assoc();
            $id = $row['ArtistId'];
            albumInsert($albumName, $id, $conn);
        }else{ //========================================================== If No,
          $selectOption = $_POST['artistName'];
          $queryArtistInsert = "INSERT INTO artists(Name) VALUES('$selectOption')";//============= Inserting new value into Artist table
          $queryResult = $conn->query($queryArtistInsert);
          if ($queryResult === TRUE) {
            $artistID = $conn->insert_id;
            albumInsert($albumName, $artistID, $conn);
          } else {
            echo "Error Found !<br>" . $conn->error;
          }
        }
        $successMsg = "Successfully Inserted";
      }else {
          $errorMsg = "Please fill the mandatory fields";
      }

    }

    // Function for inserting album
    function albumInsert($albumName, $artistID, $conn){
      $sqlInsert = "INSERT INTO albums(Title,ArtistId) VALUES ('$albumName','$artistID')";// Inserting values into Album and Artist tables
      $result = $conn->query($sqlInsert);
      if ($result === TRUE) {
        $lastAlbum_id = $conn->insert_id;
        if (isset($_POST["tracks"])){
          addTracks($lastAlbum_id, $conn);
        }
      }else {
        echo "Error Found !<br>" . $conn->error;
      }
    }

    // Function for inserting tracks
    function addTracks($albumId, $conn){
      // Counting No of tracks
      $count = count($_POST["tracks"]);
      // console.log($count);
      //Getting post values
      $tracks = $_POST["tracks"];
      if($count >= 1)
      {
        for($i=0; $i<$count; $i++)
        {
          if(trim($_POST["tracks"][$i] != ''))
          {
              // Inserting tracks into tracks table
              $sql = mysqli_query($conn,"INSERT INTO tracks(Name, AlbumId) VALUES('$tracks[$i]','$albumId')");
          }
        }
      }
    }
?>

    <section class="insert-section">
      <div class="container pt-5">
        <h4 class="mb-3"><i>Add a new album</i></h4>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group mb-3">
            <label>Album <span>*</span></label>
            <input type="text" name="album" value="" class="form-control">
            <i class="error"><?php echo $albumErr; ?> </i>
          </div>
          <div class="form-group mb-3">
            <label>Artist <span>*</span></label>
            <input type="text" name="artistName" list="artistName" class="form-control">
            <i class="error"><?php echo $selectOptionErr; ?> </i>
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
          <div class="d-flex mb-3 align-items-center">
            <div class="flex-shrink-0">
              <label>Add album tracks</label>
            </div>
            <div class="flex-grow-1 ms-3">
              <button type="button" name="add" id="add" class="btn btn-outline-secondary">Add new</button>
            </div>
          </div>
            <div id="dynamic_field">
              <div class="input-group mb-3">
                <input type="text" name="tracks[]" value="" id="track1" class="form-control">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary btn_remove" type="button">Remove Track</button>
                </div>
              </div>
            </div>
          <input type="submit" name="submit" class="btn btn-primary" value="Insert Album">
          <p class="warning-msg my-3 error"><i><?php echo $errorMsg; ?></i></p>
          <p class="success-msg my-3"><i><?php echo $successMsg; ?></i></p>
        </form>
      </div>
    </section>
    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.6.1.min.js"></script>
    <script>
      $(document).ready(function(){
        $('#add').click(function(e){
          e.preventDefault();
          var i= $('#dynamic_field .input-group').length;
          i++;
          $('#dynamic_field').append('<div class="input-group mb-3"><input type="text" name="tracks[]" id="track'+i+'" class="form-control" /><div class="input-group-append"><button class="btn btn-outline-secondary btn_remove" type="button">Remove Track</button></div></div>');
          $('#dynamic_field .btn_remove').attr("id", i);
        });

        $(document).on('click', '.btn_remove', function(e){
          e.preventDefault();
          $(this).parent().parent().remove();
        });
      });
    </script>
  </body>
</html>
