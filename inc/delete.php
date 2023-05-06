<?php
  require_once("connection.php");
  $albumId = $_GET['id'];
  if(isset($albumId))
  {
       $sql = "DELETE FROM albums WHERE AlbumId = $albumId";
       $result = $conn->query($sql);
       if($result){
         header("location: ../index.php?msg=deleted");
       }
  }
?>
