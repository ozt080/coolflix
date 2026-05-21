<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$pageTitle = "Administration";
require_once 'config.php';
require_once 'ntp.php';
require_once 'syslog_app.php';
require_once 'acl.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Vérifier accès admin
requirePermission('admin_panel', 'index.php');

// Actions
if ($_GET['action'] ?? '' === 'clear_logs') {
    syslog_clear();
    header('Location: admin.php');
    exit;
}

// Données
$logs      = syslog_read(50);
$logStats  = syslog_stats();
$ntpStatus = ntp_checkSync();

// Stats utilisateurs
$usersFile = __DIR__ . '/users.json';
$users     = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

syslog_write(LOG_INFO, 'Accès au panneau admin');
require_once 'header.php';
?>

<div style="background:linear-gradient(135deg,#0a0a1a,#1a0a00); padding:80px 0 40px; margin-top:56px;">
  <div class="container px-4">
    <h1 class="text-white fw-bold display-5">🛡️ Panneau Admin</h1>
    <p class="text-white-50">Gestion de CoolFlix — Logs, Utilisateurs, Sécurité</p>
  </div>
</div>

<main style="background:#141414; min-height:100vh; padding-bottom:60px;">
  <div class="container-fluid px-4 px-md-5 py-4">

    <!-- CARTES STATS -->
    <div class="row g-3 mb-5">

      <!-- Utilisateurs -->
      <div class="col-6 col-md-3">
        <div class="p-4 rounded text-center" style="background:#1e1e1e; border:1px solid #333;">
          <i class="fas fa-users text-danger fs-1 mb-2"></i>
          <h3 class="text-white fw-bold"><?php echo count($users); ?></h3>
          <p class="text-white-50 small mb-0">Utilisateurs</p>
        </div>
      </div>

      <!-- Logs INFO -->
      <div class="col-6 col-md-3">
        <div class="p-4 rounded text-center" style="background:#1e1e1e; border:1px solid #333;">
          <i class="fas fa-info-circle text-info fs-1 mb-2"></i>
          <h3 class="text-white fw-bold"><?php echo $logStats['INFO'] ?? 0; ?></h3>
          <p class="text-white-50 small mb-0">Logs INFO</p>
        </div>
      </div>

      <!-- Logs WARNING -->
      <div class="col-6 col-md-3">
        <div class="p-4 rounded text-center" style="background:#1e1e1e; border:1px solid #333;">
          <i class="fas fa-exclamation-triangle text-warning fs-1 mb-2"></i>
          <h3 class="text-white fw-bold"><?php echo $logStats['WARNING'] ?? 0; ?></h3>
          <p class="text-white-50 small mb-0">Warnings</p>
        </div>
      </div>

      <!-- Logs ERROR -->
      <div class="col-6 col-md-3">
        <div class="p-4 rounded text-center" style="background:#1e1e1e; border:1px solid #333;">
          <i class="fas fa-times-circle text-danger fs-1 mb-2"></i>
          <h3 class="text-white fw-bold"><?php echo ($logStats['ERROR'] ?? 0) + ($logStats['CRITICAL'] ?? 0); ?></h3>
          <p class="text-white-50 small mb-0">Erreurs</p>
        </div>
      </div>

    </div>

    <!-- NTP STATUS -->
    <div class="mb-5">
      <h2 class="section-title mb-3">⏰ Synchronisation NTP</h2>
      <div class="p-4 rounded" style="background:#1e1e1e; border:1px solid #333;">
        <div class="row">
          <div class="col-md-4">
            <p class="text-white-50 small mb-1">Statut</p>
            <?php if($ntpStatus['synced']): ?>
            <span class="badge bg-success px-3 py-2">✅ Synchronisé</span>
            <?php else: ?>
            <span class="badge bg-danger px-3 py-2">❌ Désynchronisé</span>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <p class="text-white-50 small mb-1">Heure NTP</p>
            <p class="text-white fw-bold mb-0"><?php echo $ntpStatus['ntp_time']; ?></p>
          </div>
          <div class="col-md-4">
            <p class="text-white-50 small mb-1">Écart</p>
            <p class="text-white fw-bold mb-0"><?php echo $ntpStatus['diff_sec']; ?> secondes</p>
          </div>
        </div>
      </div>
    </div>

    <!-- UTILISATEURS -->
    <div class="mb-5">
      <h2 class="section-title mb-3">👥 Utilisateurs inscrits</h2>
      <div class="table-responsive rounded" style="background:#1e1e1e;">
        <table class="table table-dark table-hover mb-0">
          <thead style="background:#111;">
            <tr>
              <th>Nom</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Inscrit le</th>
              <th>Ma Liste</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $u): ?>
            <tr>
              <td class="text-white"><?php echo htmlspecialchars($u['nom']); ?></td>
              <td class="text-white-50"><?php echo htmlspecialchars($u['email']); ?></td>
              <td>
                <span class="badge <?php echo ($u['role'] ?? 'user') === 'admin' ? 'bg-danger' : 'bg-secondary'; ?>">
                  <?php echo $u['role'] ?? 'user'; ?>
                </span>
              </td>
              <td class="text-white-50"><?php echo $u['created'] ?? 'N/A'; ?></td>
              <td class="text-white"><?php echo count($u['maListe'] ?? []); ?> films</td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($users)): ?>
            <tr><td colspan="5" class="text-center text-white-50">Aucun utilisateur</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- LOGS -->
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">📋 Syslog — Derniers événements</h2>
        <a href="admin.php?action=clear_logs" class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Effacer tous les logs ?')">
          <i class="fas fa-trash me-1"></i>Effacer
        </a>
      </div>
      <div class="rounded p-3" style="background:#0a0a0a; border:1px solid #333; max-height:400px; overflow-y:auto; font-family:monospace;">
        <?php if(empty($logs)): ?>
        <p class="text-white-50 mb-0">Aucun log disponible.</p>
        <?php else: ?>
        <?php foreach($logs as $log): ?>
        <?php
          $color = 'text-white-50';
          if (strpos($log, '[ERROR]') !== false || strpos($log, '[CRITICAL]') !== false) $color = 'text-danger';
          elseif (strpos($log, '[WARNING]') !== false) $color = 'text-warning';
          elseif (strpos($log, '[INFO]') !== false) $color = 'text-info';
        ?>
        <div class="<?php echo $color; ?> small mb-1">
          <?php echo htmlspecialchars(trim($log)); ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</main>

<?php require_once 'footer.php'; ?>