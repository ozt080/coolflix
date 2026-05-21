<?php
// ============================
// CONFIGURATION COOLFLIX
// ============================

// Clé API TMDB
define('TMDB_API_KEY', getenv('TMDB_API_KEY') ?: '6be9c082058ccbbe8dfea65d53509608');
define('TMDB_BASE_URL', 'https://api.themoviedb.org/3');
define('TMDB_IMG_URL',  'https://image.tmdb.org/t/p/w500');
define('TMDB_IMG_ORIGINAL', 'https://image.tmdb.org/t/p/original');

// Infos site
define('SITE_NAME', 'CoolFlix');
define('SITE_URL',  getenv('RAILWAY_STATIC_URL') ?: 'http://localhost:8000');

// Sécurité sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// Masquer les erreurs PHP en production
error_reporting(0);
ini_set('display_errors', 0);

// Protection XSS globale
function secure($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

// Protection contre injections
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Charger les modules de sécurité
require_once __DIR__ . '/ntp.php';
require_once __DIR__ . '/syslog_app.php';
require_once __DIR__ . '/acl.php';

// Fuseau horaire Paris
date_default_timezone_set('Europe/Paris');

?>
