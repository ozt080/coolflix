<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
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

<nav class="navbar navbar-expand-lg navbar-dark fixed-top px-3" id="mainNav">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold fs-2" href="index.php">
      <span style="color:#e50914;">Can</span><span class="text-white">Flix</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

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
        <?php if(isset($_SESSION['user'])): ?>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF'])=='maliste.php'?'active':''; ?>" href="maliste.php">
            <i class="fas fa-heart me-1"></i>Ma Liste
          </a>
        </li>
        <?php endif; ?>
      </ul>

      <div class="ms-auto d-flex align-items-center gap-3">

        <!-- Barre de recherche -->
        <form class="d-flex" action="search.php" method="GET">
          <div class="input-group">
            <input class="form-control bg-dark text-white border-secondary"
                   type="search" name="q"
                   placeholder="🔍 Rechercher..."
                   style="width:220px; border-radius:20px 0 0 20px;">
            <button class="btn btn-danger" type="submit" style="border-radius:0 20px 20px 0;">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>

        <!-- Connecté -->
        <?php if(isset($_SESSION['user'])): ?>
        <div class="dropdown">
          <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i>
            <?php echo htmlspecialchars($_SESSION['user']['nom']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="maliste.php">
                <i class="fas fa-heart me-2"></i>Ma Liste
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-danger" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
              </a>
            </li>
          </ul>
        </div>

        <!-- Non connecté -->
        <?php else: ?>
        <a href="login.php" class="btn btn-outline-light btn-sm px-3">
          <i class="fas fa-sign-in-alt me-1"></i>Connexion
        </a>
        <a href="register.php" class="btn btn-danger btn-sm px-3">
          <i class="fas fa-user-plus me-1"></i>S'inscrire
        </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>