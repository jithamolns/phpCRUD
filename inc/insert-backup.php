<?php
  require_once('connection.php');

  if(isset($_POST['submit'])){

    // Required field names
    $required = array('album', 'artistName');
    var_dump($required);

    // Loop over field names, make sure each one exists and is not empty
    $error = false;
    foreach($required as $field) {
      if (empty($_POST[$field][0])) {
        $error = true;
      }
    }

    // Validating Empty Fields
    if ($error) { //====================== If empty
      echo "Album are required.";
    } else { //=========================== All fields are entered
      $albumName = $_POST['album'];
      $selectOption = $_POST['artistName'];

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
      echo "<div class='success-msg my-3'><p>Successfully Inserted</p></div>";
    }
    else
    {
      echo "<script>alert('Please enter tracks');</script>";
    }
  }
?>
<?php
  $conn->close();
?>
