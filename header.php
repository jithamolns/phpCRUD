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
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body>
    <!-- header starts here -->
    <header>
      <nav class="py-4 navbar navbar-expand-lg bg-body-tertiary navbar-light bg-light">
        <div class="container">
          <a class="navbar-brand" href="index.php">Chinook Music Store</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo" aria-controls="navbarTogglerDemo" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Navbar starts here -->
          <div class="collapse navbar-collapse" id="navbarTogglerDemo">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="insert-album.php">New Album</a>
              </li>
            </ul>
            <!-- search form -->
            <form class="d-flex" role="search" action="index.php" method="POST">
              <input type="text" name="search" class="form-control me-2" placeholder="Search..." aria-label="Search">
              <input type="submit" value="Search" class="btn-outline-secondary btn">
            </form><!-- search form end-->
          </div><!-- Navbar ends here -->
        </div>
      </nav>
    </header>
    <!-- Header ends here -->
