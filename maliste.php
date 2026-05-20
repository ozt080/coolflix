<?php
$pageTitle = "Ma Liste";
require_once 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

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

// Charger la liste de l'utilisateur
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$maListe = [];

foreach ($users as $u) {
    if ($u['email'] === $_SESSION['user']['email']) {
        $maListe = $u['maListe'] ?? [];
        break;
    }
}

// Récupérer les détails de chaque item
$items = [];
foreach ($maListe as $entry) {
    $type = $entry['type'];
    $id   = $entry['id'];
    $data = tmdbGet("/$type/$id");
    if (!empty($data)) {
        $data['_type'] = $type;
        $items[] = $data;
    }
}

require_once 'header.php';
?>

<div style="background:linear-gradient(135deg,#1a0010,#0a0a0a); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <h1 class="text-white fw-bold display-5">❤️ Ma Liste</h1>
    <p class="text-white-50">Tes films et séries sauvegardés</p>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-4">

    <?php if(empty($items)): ?>
    <div class="text-center py-5">
      <i class="fas fa-heart text-danger" style="font-size:4rem; opacity:0.3;"></i>
      <h4 class="text-white mt-3">Ta liste est vide</h4>
      <p class="text-white-50">Ajoute des films et séries depuis les pages de détail</p>
      <a href="index.php" class="btn btn-danger mt-3">
        <i class="fas fa-home me-2"></i>Découvrir des films
      </a>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach($items as $item): ?>
      <?php if(empty($item['poster_path'])) continue; ?>
      <?php
        $type  = $item['_type'];
        $title = $type === 'movie' ? ($item['title'] ?? '') : ($item['name'] ?? '');
      ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="movie-card">
          <img src="<?php echo TMDB_IMG_URL . $item['poster_path']; ?>"
               alt="<?php echo htmlspecialchars($title); ?>"
               loading="lazy">
          <div class="card-overlay">
            <p class="text-white small fw-bold mb-1"><?php echo htmlspecialchars($title); ?></p>
            <div class="d-flex gap-2">
              <a href="detail.php?type=<?php echo $type; ?>&id=<?php echo $item['id']; ?>"
                 class="btn btn-danger btn-sm flex-fill">
                <i class="fas fa-play"></i>
              </a>
              <a href="maliste.php?remove=<?php echo $item['id']; ?>&type=<?php echo $type; ?>"
                 class="btn btn-outline-danger btn-sm">
                <i class="fas fa-trash"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</main>

<?php require_once 'footer.php'; ?>