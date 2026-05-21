<?php
$pageTitle = "Connexion";
require_once 'config.php';
session_start();

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $usersFile = 'users.json';
  $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

  $found = false;
  foreach ($users as $u) {
    if ($u['email'] === $email && password_verify($password, $u['password'])) {
      $_SESSION['user'] = [
        'id' => $u['id'],
        'nom' => $u['nom'],
        'email' => $u['email'],
        'role' => $u['role'] ?? 'user'
      ];
      $found = true;
      header('Location: index.php');
      exit;
    }
  }
  if (!$found)
    $erreur = 'Email ou mot de passe incorrect.';
}

require_once 'header.php';
?>

<div
  style="background:#0a0a0a; min-height:100vh; display:flex; align-items:center; justify-content:center; padding-top:80px;">
  <div class="container" style="max-width:420px;">

    <div class="text-center mb-4">
      <h2 class="fw-bold" style="font-size:2rem;">
        <span style="color:#e50914;">Cool</span><span class="text-white">Flix</span>
      </h2>
      <h4 class="text-white mt-3">Se connecter</h4>
    </div>

    <div class="p-4 rounded" style="background:#141414; border:1px solid #333;">

      <?php if ($erreur): ?>
        <div class="alert alert-danger"><?php echo $erreur; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="text-white-50 small mb-1">Email</label>
          <input type="email" name="email" class="form-control bg-dark text-white border-secondary"
            placeholder="ton@email.com" required autofocus>
        </div>
        <div class="mb-4">
          <label class="text-white-50 small mb-1">Mot de passe</label>
          <input type="password" name="password" class="form-control bg-dark text-white border-secondary"
            placeholder="Ton mot de passe" required>
        </div>
        <button type="submit" class="btn btn-danger w-100 btn-lg fw-bold">
          Se connecter
        </button>
      </form>

      <p class="text-center text-white-50 mt-3 mb-0">
        Pas encore de compte ?
        <a href="register.php" class="text-danger text-decoration-none">S'inscrire gratuitement</a>
      </p>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>