<?php
$pageTitle = "Accueil";
require_once 'config.php';

// ============================
// FONCTIONS API TMDB
// ============================

function tmdbGet($endpoint) {
    $url = TMDB_BASE_URL . $endpoint . "?api_key=" . TMDB_API_KEY . "&language=fr-FR&page=1";
    $opts = ['http' => ['timeout' => 10]];
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    return $response ? json_decode($response, true) : ['results' => []];
}

// Récupération des contenus
$heroMovie     = tmdbGet("/movie/popular")['results'][0];
$popularMovies = array_slice(tmdbGet("/movie/popular")['results'], 1, 12);
$popularSeries = array_slice(tmdbGet("/tv/popular")['results'], 0, 12);
$topRated      = array_slice(tmdbGet("/movie/top_rated")['results'], 0, 12);
$turkishSeries = array_slice(tmdbGet("/discover/tv&with_origin_country=TR")['results'], 0, 12);

require_once 'header.php';
?>

<!-- ======= HERO BANNER ======= -->
<section class="hero-section" style="
  background: linear-gradient(to right, rgba(0,0,0,0.95) 35%, rgba(0,0,0,0.3)),
              linear-gradient(to top, rgba(0,0,0,0.8) 10%, transparent),
              url('<?php echo TMDB_IMG_ORIGINAL . $heroMovie['backdrop_path']; ?>') center/cover no-repeat;
">
  <div class="container px-4 px-md-5">
    <div class="col-12 col-md-7">

      <!-- Badge -->
      <span class="badge bg-danger mb-3 px-3 py-2" style="font-size:0.85rem;">
        🔥 Tendance #1
      </span>

      <!-- Titre -->
      <h1 class="display-3 fw-bold text-white mb-3" style="text-shadow:2px 2px 8px rgba(0,0,0,0.8);">
        <?php echo $heroMovie['title']; ?>
      </h1>

      <!-- Note -->
      <div class="mb-3">
        <span class="text-warning me-2">
          <?php
          $note = round($heroMovie['vote_average'], 1);
          $stars = round($note / 2);
          for($i=0;$i<$stars;$i++) echo '★';
          for($i=$stars;$i<5;$i++) echo '☆';
          ?>
        </span>
        <span class="text-white-50"><?php echo $note; ?>/10</span>
        <span class="ms-3 text-white-50">
          <?php echo substr($heroMovie['release_date'], 0, 4); ?>
        </span>
      </div>

      <!-- Description -->
      <p class="text-white fs-5 mb-4" style="line-height:1.7; text-shadow:1px 1px 4px rgba(0,0,0,0.9);">
        <?php echo mb_substr($heroMovie['overview'], 0, 180); ?>...
      </p>

      <!-- Boutons -->
      <div class="d-flex gap-3 flex-wrap">
        <a href="#" class="btn btn-danger btn-lg px-4">
          <i class="fas fa-play me-2"></i>Regarder
        </a>
        <a href="#" class="btn btn-outline-light btn-lg px-4">
          <i class="fas fa-plus me-2"></i>Ma liste
        </a>
        <a href="#" class="btn btn-secondary btn-lg px-4">
          <i class="fas fa-info-circle me-2"></i>Détails
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ======= CONTENU PRINCIPAL ======= -->
<main style="background:#141414; padding-bottom: 60px;">

  <!-- 🎬 Films Populaires -->
  <section class="container-fluid px-4 px-md-5 pt-5">
    <h2 class="section-title">🎬 Films Populaires</h2>
    <div class="row g-3">
      <?php foreach($popularMovies as $movie): ?>
      <?php if(empty($movie['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $movie['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($movie['title']); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1">
              <?php echo htmlspecialchars($movie['title']); ?>
            </p>
            <div class="d-flex align-items-center justify-content-between">
              <span class="text-warning" style="font-size:0.75rem;">
                ★ <?php echo round($movie['vote_average'],1); ?>
              </span>
              <span class="text-white-50" style="font-size:0.75rem;">
                <?php echo substr($movie['release_date'],0,4); ?>
              </span>
            </div>
            <a href="#" class="btn btn-danger btn-sm w-100 mt-2">
              <i class="fas fa-play me-1"></i>Play
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 📺 Séries Populaires -->
  <section class="container-fluid px-4 px-md-5 pt-5">
    <h2 class="section-title">📺 Séries Populaires</h2>
    <div class="row g-3">
      <?php foreach($popularSeries as $show): ?>
      <?php if(empty($show['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $show['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($show['name']); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1">
              <?php echo htmlspecialchars($show['name']); ?>
            </p>
            <div class="d-flex align-items-center justify-content-between">
              <span class="text-warning" style="font-size:0.75rem;">
                ★ <?php echo round($show['vote_average'],1); ?>
              </span>
              <span class="text-white-50" style="font-size:0.75rem;">
                <?php echo substr($show['first_air_date'],0,4); ?>
              </span>
            </div>
            <a href="#" class="btn btn-danger btn-sm w-100 mt-2">
              <i class="fas fa-play me-1"></i>Play
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 🏆 Les Mieux Notés -->
  <section class="container-fluid px-4 px-md-5 pt-5">
    <h2 class="section-title">🏆 Les Mieux Notés</h2>
    <div class="row g-3">
      <?php foreach($topRated as $movie): ?>
      <?php if(empty($movie['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $movie['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($movie['title']); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1">
              <?php echo htmlspecialchars($movie['title']); ?>
            </p>
            <div class="d-flex align-items-center justify-content-between">
              <span class="text-warning" style="font-size:0.75rem;">
                ★ <?php echo round($movie['vote_average'],1); ?>
              </span>
              <span class="text-white-50" style="font-size:0.75rem;">
                <?php echo substr($movie['release_date'],0,4); ?>
              </span>
            </div>
            <a href="#" class="btn btn-danger btn-sm w-100 mt-2">
              <i class="fas fa-play me-1"></i>Play
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 🇹🇷 Séries Turques -->
  <section class="container-fluid px-4 px-md-5 pt-5">
    <h2 class="section-title">🇹🇷 Séries Turques</h2>
    <div class="row g-3">
      <?php if(!empty($turkishSeries)): ?>
        <?php foreach($turkishSeries as $show): ?>
        <?php if(empty($show['poster_path'])) continue; ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <div class="movie-card">
            <img src="<?php echo TMDB_IMG_URL . $show['poster_path']; ?>"
                 alt="<?php echo htmlspecialchars($show['name']); ?>"
                 loading="lazy">
            <div class="card-overlay">
              <p class="text-white small fw-bold mb-1">
                <?php echo htmlspecialchars($show['name']); ?>
              </p>
              <div class="d-flex align-items-center justify-content-between">
                <span class="text-warning" style="font-size:0.75rem;">
                  ★ <?php echo round($show['vote_average'],1); ?>
                </span>
                <span class="badge" style="background:#e50914; font-size:0.65rem;">TR</span>
              </div>
              <a href="#" class="btn btn-danger btn-sm w-100 mt-2">
                <i class="fas fa-play me-1"></i>Play
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-white-50">Chargement des séries turques...</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- 📺 Bannière Live TV -->
  <section class="container-fluid px-4 px-md-5 pt-5">
    <div class="p-4 p-md-5 rounded-3" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
      <div class="row align-items-center">
        <div class="col-md-8">
          <span class="badge-live me-2">● LIVE</span>
          <h3 class="text-white fw-bold mt-2">📡 Chaînes en Direct</h3>
          <p class="text-white-50 mb-3">
            Regarde les chaînes françaises et turques en direct et gratuitement
          </p>
          <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-secondary px-3 py-2">🇫🇷 TF1</span>
            <span class="badge bg-secondary px-3 py-2">🇫🇷 France 2</span>
            <span class="badge bg-secondary px-3 py-2">🇫🇷 M6</span>
            <span class="badge bg-secondary px-3 py-2">🇹🇷 TRT 1</span>
            <span class="badge bg-secondary px-3 py-2">🇹🇷 Show TV</span>
            <span class="badge bg-secondary px-3 py-2">🇹🇷 Kanal D</span>
          </div>
        </div>
        <div class="col-md-4 text-center mt-4 mt-md-0">
          <a href="live.php" class="btn btn-danger btn-lg px-5">
            <i class="fas fa-satellite-dish me-2"></i>Voir les chaînes
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once 'footer.php'; ?>