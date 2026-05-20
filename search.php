<?php
$pageTitle = "Recherche";
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

$query   = trim($_GET['q'] ?? '');
$films   = [];
$series  = [];

if ($query) {
    $films  = tmdbGet("/search/movie?query=" . urlencode($query))['results'] ?? [];
    $series = tmdbGet("/search/tv?query="    . urlencode($query))['results'] ?? [];
}

require_once 'header.php';
?>

<div style="background:linear-gradient(135deg,#0a0a0a,#1a1a1a); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <h1 class="text-white fw-bold display-5">🔍 Recherche</h1>
    <p class="text-white-50">Trouve tes films et séries préférés</p>

    <!-- Barre de recherche principale -->
    <form action="search.php" method="GET" class="mt-4">
      <div class="input-group" style="max-width:600px;">
        <input type="text"
               name="q"
               class="form-control form-control-lg bg-dark text-white border-secondary"
               placeholder="🔍 Rechercher un film, une série..."
               value="<?php echo htmlspecialchars($query); ?>"
               autofocus>
        <button class="btn btn-danger btn-lg px-4" type="submit">
          <i class="fas fa-search"></i> Rechercher
        </button>
      </div>
    </form>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-4">

    <?php if(!$query): ?>
    <!-- Suggestions de recherche -->
    <div class="text-center py-5">
      <i class="fas fa-search text-danger" style="font-size:4rem; opacity:0.3;"></i>
      <h4 class="text-white-50 mt-3">Tape le nom d'un film ou d'une série</h4>
      <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
        <?php
        $suggestions = ['Action','Comédie','Drame','Horreur','Marvel','Disney','Turque','Romance'];
        foreach($suggestions as $s):
        ?>
        <a href="search.php?q=<?php echo urlencode($s); ?>"
           class="btn btn-outline-secondary btn-sm">
          <?php echo $s; ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php elseif(empty($films) && empty($series)): ?>
    <!-- Aucun résultat -->
    <div class="text-center py-5">
      <i class="fas fa-film text-danger" style="font-size:4rem; opacity:0.3;"></i>
      <h4 class="text-white mt-3">Aucun résultat pour "<?php echo htmlspecialchars($query); ?>"</h4>
      <p class="text-white-50">Essaie avec d'autres mots-clés</p>
    </div>

    <?php else: ?>

    <!-- Résultats Films -->
    <?php if(!empty($films)): ?>
    <h2 class="section-title mb-4">
      🎬 Films
      <span class="text-white-50 fs-6 ms-2">(<?php echo count($films); ?> résultats)</span>
    </h2>
    <div class="row g-3 mb-5">
      <?php foreach($films as $film): ?>
      <?php if(empty($film['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $film['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($film['title'] ?? ''); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($film['title'] ?? ''); ?></p>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($film['vote_average'] ?? 0,1); ?></span>
              <span class="text-white-50" style="font-size:0.75rem;"><?php echo substr($film['release_date'] ?? '',0,4); ?></span>
            </div>
            <a href="detail.php?type=movie&id=<?php echo $film['id']; ?>" class="btn btn-danger btn-sm w-100">
              <i class="fas fa-play me-1"></i>Voir
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Résultats Séries -->
    <?php if(!empty($series)): ?>
    <h2 class="section-title mb-4">
      📺 Séries
      <span class="text-white-50 fs-6 ms-2">(<?php echo count($series); ?> résultats)</span>
    </h2>
    <div class="row g-3">
      <?php foreach($series as $show): ?>
      <?php if(empty($show['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $show['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($show['name'] ?? ''); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($show['name'] ?? ''); ?></p>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-warning" style="font-size:0.75rem;">★ <?php echo round($show['vote_average'] ?? 0,1); ?></span>
              <span class="text-white-50" style="font-size:0.75rem;"><?php echo substr($show['first_air_date'] ?? '',0,4); ?></span>
            </div>
            <a href="detail.php?type=tv&id=<?php echo $show['id']; ?>" class="btn btn-danger btn-sm w-100">
              <i class="fas fa-play me-1"></i>Voir
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
  </div>
</main>

<?php require_once 'footer.php'; ?>