<?php
// ============================
// SYSLOG - JOURNAL DES ÉVÉNEMENTS
// ============================

define('LOG_FILE',    __DIR__ . '/logs/app.log');
define('LOG_MAX_SIZE', 5 * 1024 * 1024); // 5MB max

// Créer le dossier logs s'il n'existe pas
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}

// Niveaux de log
define('LOG_DEBUG',   'DEBUG');
define('LOG_INFO',    'INFO');
define('LOG_WARNING', 'WARNING');
define('LOG_ERROR',   'ERROR');
define('LOG_CRITICAL','CRITICAL');

// Écrire dans le log
function syslog_write($level, $message, $context = []) {
    // Rotation du log si trop grand
    if (file_exists(LOG_FILE) && filesize(LOG_FILE) > LOG_MAX_SIZE) {
        rename(LOG_FILE, LOG_FILE . '.' . date('Y-m-d-H-i-s') . '.bak');
    }

    $ip        = getClientIPLog();
    $user      = isset($_SESSION['user']) ? $_SESSION['user']['email'] : 'guest';
    $page      = $_SERVER['PHP_SELF'] ?? 'unknown';
    $timestamp = ntp_getTime(); // Utilise NTP pour l'heure exacte

    // Format du log
    $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
    $logLine = "[{$timestamp}] [{$level}] [IP:{$ip}] [User:{$user}] [Page:{$page}] {$message}{$contextStr}" . PHP_EOL;

    // Écrire dans le fichier
    file_put_contents(LOG_FILE, $logLine, FILE_APPEND | LOCK_EX);

    // Pour les erreurs critiques, envoyer aussi dans le syslog système
    if (in_array($level, [LOG_ERROR, LOG_CRITICAL])) {
        error_log("[CoolFlix] [{$level}] {$message}");
    }
}

function getClientIPLog() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Lire les derniers logs (pour admin)
function syslog_read($lines = 100) {
    if (!file_exists(LOG_FILE)) return [];
    $content = file(LOG_FILE);
    return array_reverse(array_slice($content, -$lines));
}

// Effacer les logs
function syslog_clear() {
    file_put_contents(LOG_FILE, '');
    syslog_write(LOG_INFO, 'Logs effacés par administrateur');
}

// Statistiques des logs
function syslog_stats() {
    if (!file_exists(LOG_FILE)) return [];
    $lines = file(LOG_FILE);
    $stats = ['DEBUG'=>0,'INFO'=>0,'WARNING'=>0,'ERROR'=>0,'CRITICAL'=>0];
    foreach ($lines as $line) {
        foreach ($stats as $level => $count) {
            if (strpos($line, "[{$level}]") !== false) {
                $stats[$level]++;
            }
        }
    }
    return $stats;
}
?>