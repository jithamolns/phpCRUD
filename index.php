<?php
  require_once("header.php"); //including header
  require_once("./inc/connection.php"); //Database connection
?>

    <div class="container pt-5 pb-5">
      <div class="dropdown-center d-flex align-items-center mb-4">
        <div class="success-div"><!-- Div for showing the success messages -->
          <?php
            if(isset($_GET['msg'])){
                $msg = $_GET['msg'];
                if($msg == 'updated'){
              ?>
                <p class="m-0 success-msg">Successfully <?php echo $msg; ?></p>
              <?php }
                if($msg == 'deleted'){
              ?>
              <p class="m-0 success-msg">Successfully <?php echo $msg; ?></p>
            <?php } ?>
          <?php } ?>
        </div>
        <!-- Sort starts here -->
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Sort by
        </button>
        <?php
          // Php codes for ordering the table
          $orderBy = "AlbumId";
          $order = "ASC";

          if(!empty($_GET["orderby"])) {
          	$orderBy = $_GET["orderby"];
          }
          if(!empty($_GET["order"])) {
          	$order = $_GET["order"];
          }

          // variables assigning initial order values
          $albumNextOrder = "ASC";
          $albumIdOrder = "ASC";
          $artistsNextOrder = "ASC";

          if($orderBy == "title" and $order == "ASC") {
            $albumNextOrder = "DESC";
          }
          if($orderBy == "albumId" and $order == "ASC") {
            $albumIdOrder = "DESC";
          }
          if($orderBy == "name" and $order == "ASC") {
            $artistsNextOrder = "DESC";
          }
        ?>
        <!-- The order menu -->
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="?orderby=albumId&order=<?php echo $albumIdOrder;?>">ID</a></li>
          <li><a class="dropdown-item" href="?orderby=title&order=<?php echo $albumNextOrder;?>">Albums</a></li>
          <li><a class="dropdown-item" href="?orderby=name&order=<?php echo $artistsNextOrder;?>">Artists</a></li>
        </ul>
      </div><!-- Sort ends here -->
      <?php
      // Pagination initialization
        if(isset($_GET['page'])){
          $page = $_GET['page'];
        }else{
          $page = 1;
        }
        $num_per_page = 15;
        $start_from = ($page-1)*15;
        $count = '';
        // Pagination initialization code end

        // Search functionality implementation
        $search = '';
        if(isset($_POST['search'])){
          $search = $_POST['search'];
        }

        if($search!=''){
          // If user enters a search term in the field the following query will execute
          $sql = "SELECT albums.AlbumId, albums.Title, artists.Name from artists JOIN albums ON artists.ArtistId = albums.ArtistId
          WHERE albums.Title LIKE '%$search%' OR artists.Name LIKE '%$search%'";
          $result = $conn->query($sql);
          $count = $result->num_rows;
        }else{
          // By default this select query will execute
          $sqlQuery = "SELECT SQL_CALC_FOUND_ROWS albums.AlbumId, albums.Title, artists.Name from artists,albums
          WHERE artists.ArtistId = albums.ArtistId  ORDER BY $orderBy $order LIMIT $start_from, $num_per_page" ;
          $result = $conn->query($sqlQuery);
        }

        // Counting the total number of rows and assigning pagination values
        $query = "SELECT FOUND_ROWS() AS count";
        $result2 = $conn->query($query);
        $row = mysqli_fetch_array($result2);

        $total_record = $row['count']; //Total number of records
        $total_pages = ceil($total_record/$num_per_page); //Dividing the total count into pages
      ?>

      <!-- Section for showing the counts (singluar, plural and no results found cases are considered) -->
      <?php if($count>1) { ?>
        <p><i><?php echo $count; ?> Results found!</i></p>
      <?php } ?>

      <?php if($count==1){?>
        <p><i><?php echo $count; ?> Result found!</i></p>
      <?php } ?>

      <?php if($count==0){?>
        <p><i>No Result found!</i></p>
      <?php } ?>

      <!-- Showing the records as table -->
      <table class="table table-bordered table-hover album-table">
        <thead class="table-primary">
          <tr>
            <th class='text-center'>ID</th>
            <th>Album</th>
            <th>Artist</th>
            <th colspan="3">Actions</th>
          </tr>
        </thead>
        <?php
          while($row = $result->fetch_assoc()){
            echo "<tr>
            <td class='text-center'>".$row['AlbumId']."</td>
            <td>".$row['Title']."</td>
            <td>".$row['Name']."</td>
            <td class='text-center'><a href='details.php?id=".$row['AlbumId']."' title='Details'><img src='./img/list.png' alt='Details'></a></td>
            <td class='text-center'><a href='update.php?id=".$row['AlbumId']."' title='Edit'><img src='./img/edit.png' alt='Details'></a></td>
            <td class='text-center'><a href='delete-album.php?id=".$row['AlbumId']."' title='Delete'><img src='./img/delete.png' alt='Details'></a></td>
            </tr>";
          }
        ?>
      </table>
      <!-- Pagination starts here -->
      <ul class='pagination my-5'>
        <?php
          // Previous page
          if($page>1){
            echo "<li><a href='index.php?page=".($page-1)."' class='page-btn prev-btn'>Previous</a></li>";
          }

          // Showing page list
          for ($i=1; $i<=$total_pages; $i++) {
            if ($i == $page) {
              echo "<li class='mx-1'><a href='index.php?page=".$i."' class='page-btn active active-btn'>".$i."</a></li>";
            }else{
                echo "<li class='mx-1'><a href='index.php?page=".$i."' class='page-btn'>".$i."</a></li>";
            }
          };

          // Next page
          if($page>=$total_pages){
            //not showing next button
          }else{
            echo "<li><a href='index.php?page=".($page+1)."' class='page-btn next-btn'>NEXT</a></li>";
          }
        ?>
      </ul>
      <!-- Pagination ends here -->
    </div>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.6.1.min.js"></script>
  </body> <!-- End of body -->
</html><!-- End of html -->
