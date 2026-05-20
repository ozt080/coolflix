<?php
$pageTitle = "Films";
require_once 'config.php';

function tmdbGet($endpoint) {
    $url = TMDB_BASE_URL . $endpoint;
    $separator = strpos($url, '?') !== false ? '&' : '?';
    $url .= $separator . "api_key=" . TMDB_API_KEY . "&language=fr-FR&page=1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CoolFlix/1.0');
    $response = curl_exec($ch);
    curl_close($ch);
    if (!$response) return ['results' => []];
    return json_decode($response, true) ?: ['results' => []];
}

// Genre sélectionné
$genre    = $_GET['genre'] ?? '';
$genreNom = $_GET['nom'] ?? 'Tous les Films';

// Récupération selon genre
if ($genre) {
    $films = tmdbGet("/discover/movie?with_genres=" . $genre)['results'] ?? [];
} else {
    $films = tmdbGet("/movie/popular")['results'] ?? [];
}

// Liste des genres
$genres = [
    '28'  => '💥 Action',
    '35'  => '😂 Comédie',
    '18'  => '🎭 Drame',
    '27'  => '👻 Horreur',
    '878' => '🚀 Sci-Fi',
    '10749'=> '❤️ Romance',
    '16'  => '🎨 Animation',
    '53'  => '🔪 Thriller',
    '12'  => '🗺️ Aventure',
    '99'  => '🎥 Documentaire',
];

require_once 'header.php';
?>

<!-- HERO FILMS -->
<div style="background:linear-gradient(135deg,#1a0000,#2d0000); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <h1 class="text-white fw-bold display-5">🎬 <?php echo htmlspecialchars($genreNom); ?></h1>
    <p class="text-white-50">Découvrez les meilleurs films en streaming gratuit</p>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-4">

    <!-- FILTRES PAR GENRE -->
    <div class="d-flex flex-wrap gap-2 mb-5">
      <a href="films.php"
         class="btn btn-sm <?php echo !$genre ? 'btn-danger' : 'btn-outline-secondary'; ?>">
        🎬 Tous
      </a>
      <?php foreach($genres as $id => $nom): ?>
      <a href="films.php?genre=<?php echo $id; ?>&nom=<?php echo urlencode(strip_tags($nom)); ?>"
         class="btn btn-sm <?php echo $genre==$id ? 'btn-danger' : 'btn-outline-secondary'; ?>">
        <?php echo $nom; ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- GRILLE FILMS -->
    <div class="row g-3">
      <?php foreach($films as $film): ?>
      <?php if(empty($film['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $film['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($film['title'] ?? ''); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1">
              <?php echo htmlspecialchars($film['title'] ?? ''); ?>
            </p>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-warning" style="font-size:0.75rem;">
                ★ <?php echo round($film['vote_average'] ?? 0, 1); ?>
              </span>
              <span class="text-white-50" style="font-size:0.75rem;">
                <?php echo substr($film['release_date'] ?? '', 0, 4); ?>
              </span>
            </div>
            <a href="#" class="btn btn-danger btn-sm w-100">
              <i class="fas fa-play me-1"></i>Regarder
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</main>

<?php require_once 'footer.php'; ?>