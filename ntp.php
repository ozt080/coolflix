<?php
// ============================
// NTP - NETWORK TIME PROTOCOL
// ============================

// Serveurs NTP publics
$NTP_SERVERS = [
    'pool.ntp.org',
    'time.google.com',
    'time.cloudflare.com',
    'time.windows.com',
    'fr.pool.ntp.org',   // Serveur français
];

// Obtenir l'heure via NTP
function ntp_getTime($format = 'Y-m-d H:i:s') {
    global $NTP_SERVERS;

    // Essayer chaque serveur NTP
    foreach ($NTP_SERVERS as $server) {
        $time = ntp_query($server);
        if ($time !== false) {
            return date($format, $time);
        }
    }

    // Fallback sur l'heure locale si NTP indisponible
    return date($format);
}

// Requête NTP
function ntp_query($server) {
    try {
        // Packet NTP (48 bytes)
        $packet = "\x1b" . str_repeat("\0", 47);

        $socket = @fsockopen("udp://{$server}", 123, $errno, $errstr, 3);
        if (!$socket) return false;

        // Envoyer la requête
        fwrite($socket, $packet);
        stream_set_timeout($socket, 3);
        $response = fread($socket, 48);
        fclose($socket);

        if (strlen($response) < 48) return false;

        // Extraire le timestamp (bytes 40-43)
        $data = unpack('N12', $response);
        $timestamp = $data[9]; // Transmit Timestamp

        // Convertir NTP epoch (1900) vers Unix epoch (1970)
        $ntpEpoch = 2208988800;
        $unixTime = $timestamp - $ntpEpoch;

        // Vérification basique
        if ($unixTime < 0 || $unixTime > PHP_INT_MAX) return false;

        return $unixTime;

    } catch (Exception $e) {
        return false;
    }
}

// Obtenir le timestamp NTP
function ntp_timestamp() {
    global $NTP_SERVERS;
    foreach ($NTP_SERVERS as $server) {
        $time = ntp_query($server);
        if ($time !== false) return $time;
    }
    return time();
}

// Vérifier la synchronisation
function ntp_checkSync() {
    $ntpTime   = ntp_timestamp();
    $localTime = time();
    $diff      = abs($ntpTime - $localTime);

    return [
        'synced'     => $diff < 60, // OK si moins d'1 minute d'écart
        'ntp_time'   => date('Y-m-d H:i:s', $ntpTime),
        'local_time' => date('Y-m-d H:i:s', $localTime),
        'diff_sec'   => $diff,
    ];
}

// Formater la durée
function ntp_formatDuration($seconds) {
    if ($seconds < 60)   return $seconds . 's';
    if ($seconds < 3600) return floor($seconds/60) . 'min';
    if ($seconds < 86400) return floor($seconds/3600) . 'h';
    return floor($seconds/86400) . 'j';
}
?>