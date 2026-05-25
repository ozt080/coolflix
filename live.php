<?php
$pageTitle = "Live TV";
require_once 'config.php';
require_once 'header.php';

// Chaînes françaises avec vrais streams M3U8
$chainesFR = [
    ['nom' => 'TF1',      'emoji' => '📺', 'pays' => 'FR', 'couleur' => '#003087',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_tf1.m3u8',
     'logo' => 'TF1'],
    ['nom' => 'France 2', 'emoji' => '📺', 'pays' => 'FR', 'couleur' => '#0055a4',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_france2.m3u8',
     'logo' => 'F2'],
    ['nom' => 'France 3', 'emoji' => '📺', 'pays' => 'FR', 'couleur' => '#0055a4',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_france3.m3u8',
     'logo' => 'F3'],
    ['nom' => 'M6',       'emoji' => '📺', 'pays' => 'FR', 'couleur' => '#FF6600',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_m6.m3u8',
     'logo' => 'M6'],
    ['nom' => 'Arte',     'emoji' => '🎨', 'pays' => 'FR', 'couleur' => '#b50000',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_arte.m3u8',
     'logo' => 'Arte'],
    ['nom' => 'BFM TV',   'emoji' => '📰', 'pays' => 'FR', 'couleur' => '#e2001a',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_bfmtv.m3u8',
     'logo' => 'BFM'],
    ['nom' => 'CNews',    'emoji' => '📰', 'pays' => 'FR', 'couleur' => '#003087',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_cnews.m3u8',
     'logo' => 'CN'],
    ['nom' => 'France 5', 'emoji' => '📺', 'pays' => 'FR', 'couleur' => '#0055a4',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr_france5.m3u8',
     'logo' => 'F5'],
];

// Chaînes turques
$chainesTR = [
    ['nom' => 'TRT 1',    'emoji' => '📺', 'pays' => 'TR', 'couleur' => '#e30a17',
     'url' => 'https://tv-trt1.medya.trt.com.tr/master.m3u8',
     'logo' => 'TRT1'],
    ['nom' => 'TRT Haber', 'emoji' => '📰', 'pays' => 'TR', 'couleur' => '#e30a17',
     'url' => 'https://tv-trthaber.medya.trt.com.tr/master.m3u8',
     'logo' => 'TRTH'],
    ['nom' => 'TRT World', 'emoji' => '🌍', 'pays' => 'TR', 'couleur' => '#e30a17',
     'url' => 'https://tv-trtworld.medya.trt.com.tr/master.m3u8',
     'logo' => 'TRTW'],
    ['nom' => 'Kanal D',  'emoji' => '📺', 'pays' => 'TR', 'couleur' => '#FF0000',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/tr_kanald.m3u8',
     'logo' => 'KD'],
    ['nom' => 'Show TV',  'emoji' => '📺', 'pays' => 'TR', 'couleur' => '#FF6600',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/tr_showtv.m3u8',
     'logo' => 'STV'],
    ['nom' => 'ATV',      'emoji' => '📺', 'pays' => 'TR', 'couleur' => '#0066CC',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/tr_atv.m3u8',
     'logo' => 'ATV'],
    ['nom' => 'Star TV',  'emoji' => '⭐', 'pays' => 'TR', 'couleur' => '#FFD700',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/tr_startv.m3u8',
     'logo' => 'Star'],
    ['nom' => 'FOX TR',   'emoji' => '🦊', 'pays' => 'TR', 'couleur' => '#FF6600',
     'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/tr_fox.m3u8',
     'logo' => 'FOX'],
];
?>

<!-- HERO LIVE -->
<div style="background:linear-gradient(135deg,#0a0a0a,#1a0a00); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <div class="d-flex align-items-center gap-3 mb-2">
      <span class="badge-live px-3 py-2" style="font-size:1rem;">● LIVE</span>
      <h1 class="text-white fw-bold display-5 mb-0">📡 Live TV</h1>
    </div>
    <p class="text-white-50">Chaînes françaises et turques en direct</p>
  </div>
</div>

<!-- LECTEUR PRINCIPAL -->
<div id="playerSection" style="display:none; background:#000; border-bottom:3px solid #e50914;">
  <div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center px-4 py-2" style="background:#111;">
      <span class="text-white fw-bold" id="playerTitle">🔴 En direct</span>
      <button class="btn btn-sm btn-outline-danger" onclick="fermerPlayer()">✕ Fermer</button>
    </div>
    <video id="videoPlayer" controls autoplay
           style="width:100%; max-height:500px; background:#000;">
      Votre navigateur ne supporte pas la vidéo HTML5.
    </video>
    <div id="playerMessage" class="text-center py-3" style="display:none; background:#111;">
      <p class="text-warning mb-2">⚠️ Le stream HLS nécessite un lecteur compatible.</p>
      <a id="vlcLink" href="#" class="btn btn-danger me-2" target="_blank">
        <i class="fas fa-external-link-alt me-1"></i>Ouvrir dans VLC
      </a>
      <a id="directLink" href="#" class="btn btn-outline-light" target="_blank">
        Lien direct M3U8
      </a>
    </div>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-4">

    <!-- 🇫🇷 Chaînes Françaises -->
    <h2 class="section-title mb-4">🇫🇷 Chaînes Françaises</h2>
    <div class="row g-3 mb-5">
      <?php foreach($chainesFR as $chaine): ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="chaine-card text-center p-3 rounded"
             onclick="lancerChaine('<?php echo $chaine['url']; ?>', '<?php echo $chaine['nom']; ?>', this)"
             style="background:#1e1e1e; cursor:pointer; transition:all 0.3s; border:2px solid transparent;">
          <div class="d-flex align-items-center justify-content-center mb-2 rounded"
               style="height:60px; background:<?php echo $chaine['couleur']; ?>; border-radius:8px !important;">
            <span style="color:white; font-weight:bold; font-size:1.1rem;">
              <?php echo $chaine['logo']; ?>
            </span>
          </div>
          <p class="text-white small fw-bold mb-1"><?php echo $chaine['nom']; ?></p>
          <span class="badge-live" style="font-size:0.65rem;">● LIVE</span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- 🇹🇷 Chaînes Turques -->
    <h2 class="section-title mb-4">🇹🇷 Chaînes Turques</h2>
    <div class="row g-3 mb-5">
      <?php foreach($chainesTR as $chaine): ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="chaine-card text-center p-3 rounded"
             onclick="lancerChaine('<?php echo $chaine['url']; ?>', '<?php echo $chaine['nom']; ?>', this)"
             style="background:#1e1e1e; cursor:pointer; transition:all 0.3s; border:2px solid transparent;">
          <div class="d-flex align-items-center justify-content-center mb-2 rounded"
               style="height:60px; background:<?php echo $chaine['couleur']; ?>; border-radius:8px !important;">
            <span style="color:white; font-weight:bold; font-size:1.1rem;">
              <?php echo $chaine['logo']; ?>
            </span>
          </div>
          <p class="text-white small fw-bold mb-1"><?php echo $chaine['nom']; ?></p>
          <span class="badge-live" style="font-size:0.65rem;">● LIVE</span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- INFO BOX -->
    <div class="p-4 rounded mb-4" style="background:#1a1a1a; border-left:4px solid #e50914;">
      <h5 class="text-white mb-2">📱 Comment regarder ?</h5>
      <p class="text-white-50 mb-2">
        Clique sur une chaîne pour lancer la lecture directement dans le navigateur.
        Si la vidéo ne se lance pas, utilise le bouton <strong class="text-danger">"Ouvrir dans VLC"</strong>.
      </p>
      <p class="text-white-50 mb-0">
        💡 <strong class="text-white">VLC Media Player</strong> est gratuit et lit tous les streams :
        <a href="https://www.videolan.org/vlc/" target="_blank" class="text-danger">Télécharger VLC</a>
      </p>
    </div>

  </div>
</main>

<!-- HLS.js pour lire les streams M3U8 -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
var currentCard = null;

function lancerChaine(url, nom, card) {
    var section = document.getElementById('playerSection');
    var video   = document.getElementById('videoPlayer');
    var title   = document.getElementById('playerTitle');
    var message = document.getElementById('playerMessage');
    var vlcLink    = document.getElementById('vlcLink');
    var directLink = document.getElementById('directLink');

    // Reset cartes
    if (currentCard) currentCard.style.border = '2px solid transparent';
    card.style.border = '2px solid #e50914';
    currentCard = card;

    // Afficher le lecteur
    section.style.display = 'block';
    title.textContent = '🔴 ' + nom + ' — En direct';
    message.style.display = 'none';

    // Liens alternatifs
    vlcLink.href    = 'vlc://' + url;
    directLink.href = url;

    // Essayer HLS.js
    if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(url);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
            video.play().catch(function() {
                message.style.display = 'block';
            });
        });
        hls.on(Hls.Events.ERROR, function(event, data) {
            if (data.fatal) {
                message.style.display = 'block';
            }
        });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = url;
        video.play();
    } else {
        message.style.display = 'block';
    }

    section.scrollIntoView({behavior: 'smooth'});
}

function fermerPlayer() {
    var video = document.getElementById('videoPlayer');
    document.getElementById('playerSection').style.display = 'none';
    video.pause();
    video.src = '';
    if (currentCard) currentCard.style.border = '2px solid transparent';
    currentCard = null;
}

// Hover effect
document.querySelectorAll('.chaine-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() { this.style.background = '#2a2a2a'; });
    card.addEventListener('mouseleave', function() { this.style.background = '#1e1e1e'; });
});
</script>

<?php require_once 'footer.php'; ?>