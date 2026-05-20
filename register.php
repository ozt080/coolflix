<?php
$pageTitle = "Inscription";
require_once 'config.php';
session_start();

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$nom || !$email || !$password) {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Email invalide.';
    } elseif (strlen($password) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $confirm) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } else {
        // Sauvegarder dans un fichier JSON (sans base de données)
        $usersFile = 'users.json';
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        // Vérifier si email déjà utilisé
        foreach ($users as $u) {
            if ($u['email'] === $email) {
                $erreur = 'Cet email est déjà utilisé.';
                break;
            }
        }

        if (!$erreur) {
            $users[] = [
                'id'       => uniqid(),
                'nom'      => $nom,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
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
      <div class="alert alert-danger"><?php echo $erreur; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="text-white-50 small mb-1">Nom complet</label>
          <input type="text" name="nom" class="form-control bg-dark text-white border-secondary"
                 placeholder="Ton prénom et nom" required>
        </div>
        <div class="mb-3">
          <label class="text-white-50 small mb-1">Email</label>
          <input type="email" name="email" class="form-control bg-dark text-white border-secondary"
                 placeholder="ton@email.com" required>
        </div>
        <div class="mb-3">
          <label class="text-white-50 small mb-1">Mot de passe</label>
          <input type="password" name="password" class="form-control bg-dark text-white border-secondary"
                 placeholder="Minimum 6 caractères" required>
        </div>
        <div class="mb-4">
          <label class="text-white-50 small mb-1">Confirmer le mot de passe</label>
          <input type="password" name="confirm" class="form-control bg-dark text-white border-secondary"
                 placeholder="Répète ton mot de passe" required>
        </div>
        <button type="submit" class="btn btn-danger w-100 btn-lg fw-bold">
          Créer mon compte
        </button>
      </form>

      <p class="text-center text-white-50 mt-3 mb-0">
        Déjà un compte ?
        <a href="login.php" class="text-danger text-decoration-none">Se connecter</a>
      </p>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>