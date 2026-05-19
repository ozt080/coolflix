<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- ======= NAVBAR ======= -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3" id="mainNav">
  <div class="container-fluid">

    <!-- Logo -->
    <a class="navbar-brand fw-bold fs-2" href="index.php">
      <span style="color:#e50914;">Cool</span><span class="text-white">Flix</span>
    </a>

    <!-- Bouton mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-4 gap-2">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='index.php'?'active':''; ?>" href="index.php">
            <i class="fas fa-home me-1"></i>Accueil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='films.php'?'active':''; ?>" href="films.php">
            <i class="fas fa-film me-1"></i>Films
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='series.php'?'active':''; ?>" href="series.php">
            <i class="fas fa-tv me-1"></i>Séries
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='live.php'?'active':''; ?>" href="live.php">
            <i class="fas fa-satellite-dish me-1"></i>Live TV
          </a>
        </li>
      </ul>

      <!-- Barre de recherche -->
      <form class="ms-auto d-flex align-items-center" action="search.php" method="GET">
        <div class="input-group">
          <input class="form-control bg-dark text-white border-secondary"
                 type="search" name="q"
                 placeholder="🔍 Rechercher un film, série..."
                 style="width:280px; border-radius:20px 0 0 20px;">
          <button class="btn btn-danger" type="submit" style="border-radius:0 20px 20px 0;">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>

  </div>
</nav>
<!-- ======= FIN NAVBAR ======= -->