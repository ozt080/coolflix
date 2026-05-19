<?php require_once 'config.php'; ?>
<?php
// Récupérer les films populaires via TMDB
function getPopularMovies() {
    $url = TMDB_BASE_URL . "/movie/popular?api_key=" . TMDB_API_KEY . "&language=fr-FR&page=1";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getPopularSeries() {
    $url = TMDB_BASE_URL . "/tv/popular?api_key=" . TMDB_API_KEY . "&language=fr-FR&page=1";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$movies = getPopularMovies();
$series = getPopularSeries();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolFlix - Streaming Gratuit</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (icônes) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Ton CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background:#0a0a0a;">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold fs-3" href="index.php" style="color:#e50914;">
      🎬 CoolFlix
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-4">
        <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="films.php">Films</a></li>
        <li class="nav-item"><a class="nav-link" href="series.php">Séries</a></li>
        <li class="nav-item"><a class="nav-link" href="live.php">📺 Live TV</a></li>
      </ul>
      <div class="ms-auto">
        <input class="form-control bg-dark text-white border-secondary" type="search" placeholder="🔍 Rechercher..." style="width:250px;">
      </div>
    </div>
  </div>
</nav>

<!-- HERO BANNER -->
<div class="hero-banner d-flex align-items-center" style="background:linear-gradient(to right, #0a0a0a 40%, transparent), url('https://image.tmdb.org/t/p/original<?php echo $movies['results'][0]['backdrop_path']; ?>') center/cover; min-height:90vh; margin-top:56px;">
  <div class="container px-5">
    <h1 class="display-3 text-white fw-bold"><?php echo $movies['results'][0]['title']; ?></h1>
    <p class="text-white fs-5 w-50"><?php echo substr($movies['results'][0]['overview'], 0, 150); ?>...</p>
    <div class="mt-4">
      <a href="#" class="btn btn-danger btn-lg me-3"><i class="fas fa-play me-2"></i>Regarder</a>
      <a href="#" class="btn btn-secondary btn-lg"><i class="fas fa-info-circle me-2"></i>Plus d'infos</a>
    </div>
  </div>
</div>

<!-- SECTION FILMS POPULAIRES -->
<div class="container-fluid px-5 py-4" style="background:#141414;">
  <h2 class="text-white mb-4">🎬 Films Populaires</h2>
  <div class="row g-3">
    <?php foreach(array_slice($movies['results'], 0, 8) as $movie): ?>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="card bg-dark border-0 movie-card">
        <img src="<?php echo TMDB_IMG_URL . $movie['poster_path']; ?>" 
             class="card-img-top rounded" 
             alt="<?php echo $movie['title']; ?>"
             loading="lazy">
        <div class="card-overlay">
          <p class="text-white small fw-bold"><?php echo $movie['title']; ?></p>
          <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-play"></i> Play</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- SECTION SÉRIES POPULAIRES -->
<div class="container-fluid px-5 py-4" style="background:#141414;">
  <h2 class="text-white mb-4">📺 Séries Populaires</h2>
  <div class="row g-3">
    <?php foreach(array_slice($series['results'], 0, 8) as $show): ?>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="card bg-dark border-0 movie-card">
        <img src="<?php echo TMDB_IMG_URL . $show['poster_path']; ?>" 
             class="card-img-top rounded" 
             alt="<?php echo $show['name']; ?>"
             loading="lazy">
        <div class="card-overlay">
          <p class="text-white small fw-bold"><?php echo $show['name']; ?></p>
          <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-play"></i> Play</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center text-white py-4" style="background:#0a0a0a;">
  <p>© 2025 <span style="color:#e50914;">CoolFlix</span> — Streaming Gratuit 🎬</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>