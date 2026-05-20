<?php
require_once 'config.php';

function tmdbGet($endpoint) {
    $url = TMDB_BASE_URL . $endpoint;
    $separator = strpos($url, '?') !== false ? '&' : '?';
    $url .= $separator . "api_key=" . TMDB_API_KEY . "&language=fr-FR";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CoolFlix/1.0');
    $response = curl_exec($ch);
    curl_close($ch);
    if (!$response) return [];
    return json_decode($response, true) ?: [];
}

$type = $_GET['type'] ?? 'movie';
$id   = intval($_GET['id'] ?? 0);

if (!$id) { header('Location: index.php'); exit; }

// Récupérer les détails
$item    = tmdbGet("/$type/$id");
$videos  = tmdbGet("/$type/$id/videos")['results'] ?? [];
$credits = tmdbGet("/$type/$id/credits");
$similar = tmdbGet("/$type/$id/similar")['results'] ?? [];
$cast    = array_slice($credits['cast'] ?? [], 0, 8);

// Trouver la bande-annonce YouTube
$trailer = null;
foreach ($videos as $v) {
    if ($v['site'] === 'YouTube' && in_array($v['type'], ['Trailer','Teaser'])) {
        $trailer = $v['key'];
        break;
    }
}

$title = $type === 'movie' ? ($item['title'] ?? 'Titre inconnu') : ($item['name'] ?? 'Titre inconnu');
$pageTitle = $title;

require_once 'header.php';
?>

<!-- HERO DÉTAIL -->
<div style="
  background: linear-gradient(to right, rgba(0,0,0,0.97) 40%, rgba(0,0,0,0.4)),
              url('<?php echo TMDB_IMG_ORIGINAL . ($item['backdrop_path'] ?? ''); ?>') center/cover;
  min-height: 70vh;
  margin-top: 56px;
  display: flex;
  align-items: center;
">
  <div class="container px-4 px-md-5 py-5">
    <div class="row align-items-center">

      <!-- Affiche -->
      <div class="col-md-3 mb-4 mb-md-0">
        <img src="<?php echo TMDB_IMG_URL . ($item['poster_path'] ?? ''); ?>"
             alt="<?php echo htmlspecialchars($title); ?>"
             class="img-fluid rounded shadow"
             style="max-width:220px; border:3px solid #333;">
      </div>

      <!-- Infos -->
      <div class="col-md-9">
        <h1 class="text-white fw-bold mb-2"><?php echo htmlspecialchars($title); ?></h1>

        <!-- Badges infos -->
        <div class="d-flex flex-wrap gap-2 mb-3">
          <span class="badge bg-danger px-3 py-2">
            ★ <?php echo round($item['vote_average'] ?? 0, 1); ?>/10
          </span>
          <span class="badge bg-secondary px-3 py-2">
            <?php echo substr($type === 'movie' ? ($item['release_date'] ?? '') : ($item['first_air_date'] ?? ''), 0, 4); ?>
          </span>
          <?php if($type === 'movie' && !empty($item['runtime'])): ?>
          <span class="badge bg-secondary px-3 py-2">
            ⏱️ <?php echo floor($item['runtime']/60).'h'.($item['runtime']%60).'min'; ?>
          </span>
          <?php endif; ?>
          <?php if($type === 'tv' && !empty($item['number_of_seasons'])): ?>
          <span class="badge bg-secondary px-3 py-2">
            📺 <?php echo $item['number_of_seasons']; ?> saison(s)
          </span>
          <?php endif; ?>
        </div>

        <!-- Genres -->
        <div class="d-flex flex-wrap gap-2 mb-3">
          <?php foreach($item['genres'] ?? [] as $g): ?>
          <span class="badge" style="background:#222; border:1px solid #555; color:#fff;">
            <?php echo $g['name']; ?>
          </span>
          <?php endforeach; ?>
        </div>

        <!-- Synopsis -->
        <p class="text-white-50 mb-4" style="line-height:1.8; max-width:700px;">
          <?php echo !empty($item['overview']) ? htmlspecialchars($item['overview']) : 'Synopsis non disponible.'; ?>
        </p>

        <!-- Boutons -->
        <div class="d-flex gap-3 flex-wrap">
          <?php if($trailer): ?>
          <button class="btn btn-danger btn-lg px-4"
                  data-bs-toggle="modal" data-bs-target="#trailerModal">
            <i class="fas fa-play me-2"></i>Bande-annonce
          </button>
          <?php endif; ?>
          <a href="<?php echo $type === 'movie' ? 'films.php' : 'series.php'; ?>"
             class="btn btn-outline-light btn-lg px-4">
            <i class="fas fa-arrow-left me-2"></i>Retour
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<main style="background:#141414; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-5">

    <!-- CASTING -->
    <?php if(!empty($cast)): ?>
    <h2 class="section-title mb-4">🎭 Casting</h2>
    <div class="row g-3 mb-5">
      <?php foreach($cast as $actor): ?>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="text-center">
          <?php if(!empty($actor['profile_path'])): ?>
          <img src="<?php echo TMDB_IMG_URL . $actor['profile_path']; ?>"
               alt="<?php echo htmlspecialchars($actor['name']); ?>"
               class="rounded-circle mb-2"
               style="width:80px; height:80px; object-fit:cover; border:2px solid #333;">
          <?php else: ?>
          <div class="rounded-circle mb-2 d-flex align-items-center justify-content-center mx-auto"
               style="width:80px; height:80px; background:#333; border:2px solid #555;">
            <i class="fas fa-user text-white-50"></i>
          </div>
          <?php endif; ?>
          <p class="text-white small fw-bold mb-0"><?php echo htmlspecialchars($actor['name']); ?></p>
          <p class="text-white-50" style="font-size:0.75rem;"><?php echo htmlspecialchars($actor['character'] ?? ''); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- SIMILAIRES -->
    <?php if(!empty($similar)): ?>
    <h2 class="section-title mb-4">🎬 Vous aimerez aussi</h2>
    <div class="row g-3">
      <?php foreach(array_slice($similar, 0, 6) as $s): ?>
      <?php if(empty($s['poster_path'])) continue; ?>
      <div class="col-6 col-sm-4 col-md-2">
        <a href="detail.php?type=<?php echo $type; ?>&id=<?php echo $s['id']; ?>"
           style="text-decoration:none;">
          <div class="movie-card">
            <img src="<?php echo TMDB_IMG_URL . $s['poster_path']; ?>"
                 alt="<?php echo htmlspecialchars($s['title'] ?? $s['name'] ?? ''); ?>"
                 loading="lazy">
            <div class="card-overlay">
              <p class="text-white small fw-bold mb-1">
                <?php echo htmlspecialchars($s['title'] ?? $s['name'] ?? ''); ?>
              </p>
              <span class="text-warning" style="font-size:0.75rem;">
                ★ <?php echo round($s['vote_average'] ?? 0,1); ?>
              </span>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</main>

<!-- MODAL BANDE-ANNONCE -->
<?php if($trailer): ?>
<div class="modal fade" id="trailerModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark border-0">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-white">🎬 <?php echo htmlspecialchars($title); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                onclick="document.getElementById('yt').src=document.getElementById('yt').src;"></button>
      </div>
      <div class="modal-body p-0">
        <div style="position:relative; padding-bottom:56.25%; height:0;">
          <iframe id="yt"
                  src="https://www.youtube.com/embed/<?php echo $trailer; ?>?autoplay=1"
                  style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;"
                  allowfullscreen allow="autoplay"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>