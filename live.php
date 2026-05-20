<?php
$pageTitle = "Live TV";
require_once 'config.php';
require_once 'header.php';

// Chaînes françaises
$chainesFR = [
    ['nom' => 'TF1',       'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/TF1_logo_2013.svg/200px-TF1_logo_2013.svg.png',      'url' => 'https://raw.githubusercontent.com/iptv-org/iptv/master/streams/fr.m3u8', 'pays' => 'FR'],
    ['nom' => 'France 2',  'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/France_2_logo_2018.svg/200px-France_2_logo_2018.svg.png', 'url' => '#', 'pays' => 'FR'],
    ['nom' => 'France 3',  'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/France_3_logo_2018.svg/200px-France_3_logo_2018.svg.png', 'url' => '#', 'pays' => 'FR'],
    ['nom' => 'M6',        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/M6_logo.svg/200px-M6_logo.svg.png',                      'url' => '#', 'pays' => 'FR'],
    ['nom' => 'Arte',      'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Arte_Logo_2019.svg/200px-Arte_Logo_2019.svg.png',         'url' => '#', 'pays' => 'FR'],
    ['nom' => 'BFM TV',    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/BFM_TV_logo_2012.svg/200px-BFM_TV_logo_2012.svg.png',    'url' => '#', 'pays' => 'FR'],
    ['nom' => 'CNews',     'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/CNews_logo_2017.svg/200px-CNews_logo_2017.svg.png',      'url' => '#', 'pays' => 'FR'],
    ['nom' => 'Canal+',    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Canal%2B.svg/200px-Canal%2B.svg.png',                    'url' => '#', 'pays' => 'FR'],
];

// Chaînes turques
$chainesTR = [
    ['nom' => 'TRT 1',     'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/TRT_1_logo.svg/200px-TRT_1_logo.svg.png',               'url' => '#', 'pays' => 'TR'],
    ['nom' => 'Show TV',   'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/Show_TV_logo.svg/200px-Show_TV_logo.svg.png',            'url' => '#', 'pays' => 'TR'],
    ['nom' => 'Kanal D',   'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Kanal_D_logo.svg/200px-Kanal_D_logo.svg.png',           'url' => '#', 'pays' => 'TR'],
    ['nom' => 'ATV',       'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/ATV_Turkey_logo.svg/200px-ATV_Turkey_logo.svg.png',     'url' => '#', 'pays' => 'TR'],
    ['nom' => 'Star TV',   'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/Star_TV_Turkey_logo.svg/200px-Star_TV_Turkey_logo.svg.png', 'url' => '#', 'pays' => 'TR'],
    ['nom' => 'FOX TR',    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/FOX_TV_Turkey_logo.svg/200px-FOX_TV_Turkey_logo.svg.png', 'url' => '#', 'pays' => 'TR'],
    ['nom' => 'TRT Haber', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/TRT_1_logo.svg/200px-TRT_1_logo.svg.png',               'url' => '#', 'pays' => 'TR'],
    ['nom' => 'CNN Türk',  'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/CNN_T%C3%BCrk_logo.svg/200px-CNN_T%C3%BCrk_logo.svg.png', 'url' => '#', 'pays' => 'TR'],
];
?>

<!-- HERO LIVE -->
<div style="background:linear-gradient(135deg,#0a0a0a,#1a0a00); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <div class="d-flex align-items-center gap-3 mb-2">
      <span class="badge-live px-3 py-2" style="font-size:1rem;">● LIVE</span>
      <h1 class="text-white fw-bold display-5 mb-0">📡 Live TV</h1>
    </div>
    <p class="text-white-50">Chaînes françaises et turques en direct et gratuitement</p>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">

  <!-- Lecteur vidéo -->
  <div id="playerSection" style="display:none; background:#000; border-bottom:3px solid #e50914;">
    <div class="container-fluid px-0">
      <div class="d-flex justify-content-between align-items-center px-4 py-2" style="background:#111;">
        <span class="text-white fw-bold" id="playerTitle">🔴 En direct</span>
        <button class="btn btn-sm btn-outline-danger" onclick="fermerPlayer()">✕ Fermer</button>
      </div>
      <video id="videoPlayer" controls autoplay style="width:100%; max-height:500px; background:#000;">
        Votre navigateur ne supporte pas la vidéo.
      </video>
    </div>
  </div>

  <div class="container-fluid px-4 px-md-5 py-4">

    <!-- 🇫🇷 Chaînes Françaises -->
    <h2 class="section-title mb-4">🇫🇷 Chaînes Françaises</h2>
    <div class="row g-3 mb-5">
      <?php foreach($chainesFR as $chaine): ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="chaine-card text-center p-3 rounded"
             onclick="lancerChaine('<?php echo $chaine['url']; ?>', '<?php echo $chaine['nom']; ?>')"
             style="background:#1e1e1e; cursor:pointer; transition:all 0.3s; border:2px solid transparent;">
          <div style="height:60px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
            <img src="<?php echo $chaine['logo']; ?>"
                 alt="<?php echo $chaine['nom']; ?>"
                 style="max-height:50px; max-width:100px; object-fit:contain;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="text-white fw-bold" style="display:none; font-size:1.2rem;"><?php echo $chaine['nom']; ?></span>
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
             onclick="lancerChaine('<?php echo $chaine['url']; ?>', '<?php echo $chaine['nom']; ?>')"
             style="background:#1e1e1e; cursor:pointer; transition:all 0.3s; border:2px solid transparent;">
          <div style="height:60px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
            <img src="<?php echo $chaine['logo']; ?>"
                 alt="<?php echo $chaine['nom']; ?>"
                 style="max-height:50px; max-width:100px; object-fit:contain;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="text-white fw-bold" style="display:none; font-size:1.2rem;"><?php echo $chaine['nom']; ?></span>
          </div>
          <p class="text-white small fw-bold mb-1"><?php echo $chaine['nom']; ?></p>
          <span class="badge-live" style="font-size:0.65rem;">● LIVE</span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Info box -->
    <div class="p-4 rounded" style="background:#1a1a1a; border-left:4px solid #e50914;">
      <h5 class="text-white mb-2">ℹ️ Comment regarder en direct ?</h5>
      <p class="text-white-50 mb-0">
        Clique sur une chaîne pour lancer la lecture. Certaines chaînes nécessitent 
        une connexion internet stable. Si une chaîne ne fonctionne pas, réessaie plus tard.
      </p>
    </div>

  </div>
</main>

<script>
function lancerChaine(url, nom) {
    if (url === '#') {
        alert('⚠️ La chaîne ' + nom + ' sera disponible prochainement !');
        return;
    }
    document.getElementById('playerSection').style.display = 'block';
    document.getElementById('playerTitle').textContent = '🔴 ' + nom + ' — En direct';
    document.getElementById('videoPlayer').src = url;
    document.getElementById('playerSection').scrollIntoView({behavior: 'smooth'});

    // Highlight chaîne active
    document.querySelectorAll('.chaine-card').forEach(c => {
        c.style.border = '2px solid transparent';
    });
    event.currentTarget.style.border = '2px solid #e50914';
}

function fermerPlayer() {
    document.getElementById('playerSection').style.display = 'none';
    document.getElementById('videoPlayer').src = '';
    document.querySelectorAll('.chaine-card').forEach(c => {
        c.style.border = '2px solid transparent';
    });
}

// Hover effect sur les chaînes
document.querySelectorAll('.chaine-card').forEach(card => {
    card.addEventListener('mouseenter', () => card.style.background = '#2a2a2a');
    card.addEventListener('mouseleave', () => card.style.background = '#1e1e1e');
});
</script>

<?php require_once 'footer.php'; ?>