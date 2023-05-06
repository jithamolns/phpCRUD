<?php
  require_once("connection.php");
  $search = '';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chinook Music Store</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&family=Signika+Negative:wght@400;500&display=swap" rel="stylesheet">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>
    <!-- header starts here -->
    <header class="py-4">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
          <a class="navbar-brand" href="../index.php">Chinook Music Store</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../insert-album.php">New Album</a>
              </li>
            </ul>
            <form class="d-flex" role="search" action="search.php" method="POST">
              <input type="text" name="search" class="form-control me-2" placeholder="Search albums" aria-label="Search">
              <input type="submit" value="Search" class="btn-outline-secondary btn">
            </form>
          </div>
        </div>
      </nav>
    </header>
    <!-- Header ends here -->
    <section class="py-5">
      <div class="container">
        <?php
          if(isset($_POST['search'])){
            $search = $_POST['search'];
          }
            $sql = "SELECT albums.AlbumId, albums.Title, artists.Name from artists,albums WHERE artists.ArtistId = albums.ArtistId AND albums.Title LIKE '%$search%'";
            $result = $conn->query($sql);
            $count = $result->num_rows;
        ?>
        <p><i><?php if($count>1){echo $count." Results found."; }else{ echo "1 Result found."; } ?></i></p>
        <table class="table table-bordered table-hover album-table">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Album</th>
              <th>Artist</th>
              <th colspan="3">Actions</th>
            </tr>
          </thead>
          <?php
            if ($result->num_rows > 0){
              while($row = $result->fetch_assoc() ){
                echo "<tr>
                <td class='text-center'>".$row['AlbumId']."</td>
                <td>".$row['Title']."</td>
                <td>".$row['Name']."</td>
                <td class='text-center'><a href='../details.php?id=".$row['AlbumId']."' title='Details'><img src='../img/list.png' alt='Details'></a></td>
                <td class='text-center'><a href='../update.php?id=".$row['AlbumId']."' title='Edit'><img src='../img/edit.png' alt='Details'></a></td>
                <td class='text-center'><a href='../delete-album.php?id=".$row['AlbumId']."' title='Delete'><img src='../img/delete.png' alt='Details'></a></td>
                </tr>";
              }
            } else {
              	echo "0 records";
            }
          ?>
        </table>

      </div>
    </section>
  </body>
</html>
<?php
  $conn->close();
?>
