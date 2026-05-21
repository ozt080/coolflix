<?php
// ============================
// ACL - ACCESS CONTROL LIST
// ============================

// Définition des rôles
define('ROLE_GUEST',  'guest');   // Non connecté
define('ROLE_USER',  'user');    // Utilisateur normal
define('ROLE_ADMIN', 'admin');   // Administrateur

// Permissions par rôle
$ACL_PERMISSIONS = [
    ROLE_GUEST => [
        'view_home'     => true,
        'view_films'    => true,
        'view_series'   => true,
        'view_live'     => true,
        'view_detail'   => true,
        'search'        => true,
        'ma_liste'      => false,
        'admin_panel'   => false,
    ],
    ROLE_USER => [
        'view_home'     => true,
        'view_films'    => true,
        'view_series'   => true,
        'view_live'     => true,
        'view_detail'   => true,
        'search'        => true,
        'ma_liste'      => true,
        'admin_panel'   => false,
    ],
    ROLE_ADMIN => [
        'view_home'     => true,
        'view_films'    => true,
        'view_series'   => true,
        'view_live'     => true,
        'view_detail'   => true,
        'search'        => true,
        'ma_liste'      => true,
        'admin_panel'   => true,
    ],
];

// Obtenir le rôle de l'utilisateur connecté
function getCurrentRole() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user'])) return ROLE_GUEST;
    return $_SESSION['user']['role'] ?? ROLE_USER;
}

// Vérifier une permission
function canAccess($permission) {
    global $ACL_PERMISSIONS;
    $role = getCurrentRole();
    return $ACL_PERMISSIONS[$role][$permission] ?? false;
}

// Bloquer l'accès si pas la permission
function requirePermission($permission, $redirect = 'login.php') {
    if (!canAccess($permission)) {
        syslog_write('WARNING', "Accès refusé à '$permission' pour IP: " . getClientIP());
        header("Location: $redirect");
        exit;
    }
}

// Vérifier si admin
function isAdmin() {
    return getCurrentRole() === ROLE_ADMIN;
}

// Vérifier si connecté
function isLoggedIn() {
    return getCurrentRole() !== ROLE_GUEST;
}

// Obtenir l'IP du visiteur
function getClientIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
?>