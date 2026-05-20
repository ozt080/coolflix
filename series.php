<?php
$pageTitle = "Séries";
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

// Récupération des séries
$populaires    = tmdbGet("/tv/popular")['results'] ?? [];
$topRated      = tmdbGet("/tv/top_rated")['results'] ?? [];
$turques       = tmdbGet("/discover/tv?with_origin_country=TR")['results'] ?? [];
$francaises    = tmdbGet("/discover/tv?with_origin_country=FR")['results'] ?? [];
$enCours       = tmdbGet("/tv/on_the_air")['results'] ?? [];

require_once 'header.php';
?>

<!-- HERO SÉRIES -->
<div style="background:linear-gradient(135deg,#000d1a,#001a2d); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <h1 class="text-white fw-bold display-5">📺 Séries</h1>
    <p class="text-white-50">Séries populaires, turques, françaises et bien plus</p>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">

  <!-- Onglets -->
  <div style="background:#1a1a1a; border-bottom:1px solid #333;">
    <div class="container-fluid px-4 px-md-5">
      <ul class="nav nav-tabs border-0" id="seriesTabs">
        <li class="nav-item">
          <a class="nav-link active text-white" data-bs-toggle="tab" href="#populaires">🔥 Populaires</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" data-bs-toggle="tab" href="#turques">🇹🇷 Turques</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" data-bs-toggle="tab" href="#francaises">🇫🇷 Françaises</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" data-bs-toggle="tab" href="#toprated">🏆 Top Notées</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" data-bs-toggle="tab" href="#encours">📡 En Cours</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="container-fluid px-4 px-md-5 py-4">
    <div class="tab-content">

      <!-- Populaires -->
      <div class="tab-pane fade show active" id="populaires">
        <div class="row g-3 mt-2">
          <?php foreach($populaires as $s): ?>
          <?php if(empty($s['poster_path'])) continue; ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="movie-card">
              <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                   alt="<?php echo htmlspecialchars($s['name'] ?? ''); ?>" loading="lazy">
              <div class="card-overlay">
                <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($s['name'] ?? ''); ?></p>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($s['vote_average'] ?? 0,1); ?></span>
                  <span class="text-white-50" style="font-size:0.75rem;"><?php echo substr($s['first_air_date'] ?? '',0,4); ?></span>
                </div>
                <a href="#" class="btn btn-danger btn-sm w-100"><i class="fas fa-play me-1"></i>Regarder</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Turques -->
      <div class="tab-pane fade" id="turques">
        <div class="row g-3 mt-2">
          <?php foreach($turques as $s): ?>
          <?php if(empty($s['poster_path'])) continue; ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="movie-card">
              <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                   alt="<?php echo htmlspecialchars($s['name'] ?? ''); ?>" loading="lazy">
              <div class="card-overlay">
                <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($s['name'] ?? ''); ?></p>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($s['vote_average'] ?? 0,1); ?></span>
                  <span class="badge" style="background:#e50914;font-size:0.65rem;">🇹🇷 TR</span>
                </div>
                <a href="#" class="btn btn-danger btn-sm w-100"><i class="fas fa-play me-1"></i>Regarder</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Françaises -->
      <div class="tab-pane fade" id="francaises">
        <div class="row g-3 mt-2">
          <?php foreach($francaises as $s): ?>
          <?php if(empty($s['poster_path'])) continue; ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="movie-card">
              <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                   alt="<?php echo htmlspecialchars($s['name'] ?? ''); ?>" loading="lazy">
              <div class="card-overlay">
                <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($s['name'] ?? ''); ?></p>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($s['vote_average'] ?? 0,1); ?></span>
                  <span class="badge" style="background:#0055a4;font-size:0.65rem;">🇫🇷 FR</span>
                </div>
                <a href="#" class="btn btn-danger btn-sm w-100"><i class="fas fa-play me-1"></i>Regarder</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Top Notées -->
      <div class="tab-pane fade" id="toprated">
        <div class="row g-3 mt-2">
          <?php foreach($topRated as $s): ?>
          <?php if(empty($s['poster_path'])) continue; ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="movie-card">
              <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                   alt="<?php echo htmlspecialchars($s['name'] ?? ''); ?>" loading="lazy">
              <div class="card-overlay">
                <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($s['name'] ?? ''); ?></p>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($s['vote_average'] ?? 0,1); ?></span>
                  <span class="text-white-50" style="font-size:0.75rem;"><?php echo substr($s['first_air_date'] ?? '',0,4); ?></span>
                </div>
                <a href="#" class="btn btn-danger btn-sm w-100"><i class="fas fa-play me-1"></i>Regarder</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- En Cours -->
      <div class="tab-pane fade" id="encours">
        <div class="row g-3 mt-2">
          <?php foreach($enCours as $s): ?>
          <?php if(empty($s['poster_path'])) continue; ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="movie-card">
              <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                   alt="<?php echo htmlspecialchars($s['name'] ?? ''); ?>" loading="lazy">
              <div class="card-overlay">
                <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($s['name'] ?? ''); ?></p>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($s['vote_average'] ?? 0,1); ?></span>
                  <span class="badge-live" style="font-size:0.65rem;">● EN COURS</span>
                </div>
                <a href="#" class="btn btn-danger btn-sm w-100"><i class="fas fa-play me-1"></i>Regarder</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</main>

<?php require_once 'footer.php'; ?>