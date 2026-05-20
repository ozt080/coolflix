<?php
$pageTitle = "Inscription";
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Si déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_token']) {
        $erreur = 'Requête invalide.';
    } else {
        $nom      = secure($_POST['nom'] ?? '');
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';

        if (!$nom || !$email || !$password) {
            $erreur = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'Email invalide.';
        } elseif (strlen($password) < 8) {
            $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $erreur = 'Le mot de passe doit contenir au moins une majuscule.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $erreur = 'Le mot de passe doit contenir au moins un chiffre.';
        } elseif ($password !== $confirm) {
            $erreur = 'Les mots de passe ne correspondent pas.';
        } else {
            $usersFile = __DIR__ . '/users.json';
            $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

            foreach ($users as $u) {
                if ($u['email'] === $email) {
                    $erreur = 'Cet email est déjà utilisé.';
                    break;
                }
            }

            if (!$erreur) {
                $users[] = [
                    'id'       => bin2hex(random_bytes(16)),
                    'nom'      => $nom,
                    'email'    => $email,
                    'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
                    'maListe'  => [],
                    'created'  => date('Y-m-d H:i:s')
                ];
                file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                $_SESSION['user'] = ['nom' => $nom, 'email' => $email];
                header('Location: index.php');
                exit;
            }
        }
    }
}

// Générer token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'header.php';
?>

<div style="background:#0a0a0a; min-height:100vh; display:flex; align-items:center; justify-content:center; padding-top:80px;">
  <div class="container" style="max-width:450px;">

    <div class="text-center mb-4">
      <h2 class="fw-bold" style="font-size:2rem;">
        <span style="color:#e50914;">Cool</span><span class="text-white">Flix</span>
      </h2>
      <h4 class="text-white mt-3">Créer un compte</h4>
    </div>

    <div class="p-4 rounded" style="background:#141414; border:1px solid #333;">

      <?php if($erreur): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $erreur; ?>
      </div>
      <?php endif; ?>

      <form method="POST">
        <!-- Token CSRF caché -->
        <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="mb-3">
          <label class="text-white-50 small mb-1">Nom complet</label>
          <input type="text" name="nom"
                 class="form-control bg-dark text-white border-secondary"
                 placeholder="Ton prénom et nom"
                 maxlength="50" required>
        </div>

        <div class="mb-3">
          <label class="text-white-50 small mb-1">Email</label>
          <input type="email" name="email"
                 class="form-control bg-dark text-white border-secondary"
                 placeholder="ton@email.com"
                 maxlength="100" required>
        </div>

        <div class="mb-3">
          <label class="text-white-50 small mb-1">Mot de passe</label>
          <input type="password" name="password"
                 class="form-control bg-dark text-white border-secondary"
                 placeholder="Min. 8 caractères, 1 majuscule, 1 chiffre"
                 minlength="8" required>
          <div class="mt-1">
            <div id="strengthBar" style="height:4px; border-radius:2px; background:#333; transition:all 0.3s;">
              <div id="strengthFill" style="height:100%; width:0%; border-radius:2px; transition:all 0.3s;"></div>
            </div>
            <small id="strengthText" class="text-white-50"></small>
          </div>
        </div>

        <div class="mb-4">
          <label class="text-white-50 small mb-1">Confirmer le mot de passe</label>
          <input type="password" name="confirm"
                 class="form-control bg-dark text-white border-secondary"
                 placeholder="Répète ton mot de passe"
                 minlength="8" required>
        </div>

        <button type="submit" class="btn btn-danger w-100 btn-lg fw-bold">
          <i class="fas fa-user-plus me-2"></i>Créer mon compte
        </button>
      </form>

      <p class="text-center text-white-50 mt-3 mb-0">
        Déjà un compte ?
        <a href="login.php" class="text-danger text-decoration-none">Se connecter</a>
      </p>
    </div>
  </div>
</div>

<!-- Indicateur force mot de passe -->
<script>
document.querySelector('input[name="password"]').addEventListener('input', function() {
    const pwd = this.value;
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    let score = 0;
    if (pwd.length >= 8) score++;
    if (/[A-Z]/.test(pwd)) score++;
    if (/[0-9]/.test(pwd)) score++;
    if (/[^A-Za-z0-9]/.test(pwd)) score++;
    const levels = [
        { width: '0%',   color: '#333',    label: '' },
        { width: '25%',  color: '#e50914', label: '🔴 Très faible' },
        { width: '50%',  color: '#ff6b35', label: '🟠 Faible' },
        { width: '75%',  color: '#ffc107', label: '🟡 Moyen' },
        { width: '100%', color: '#28a745', label: '🟢 Fort' },
    ];
    fill.style.width  = levels[score].width;
    fill.style.background = levels[score].color;
    text.textContent  = levels[score].label;
});
</script>

<?php require_once 'footer.php'; ?>