<?php
// ============================
// NTP - NETWORK TIME PROTOCOL
// ============================

// Serveurs NTP publics
$NTP_SERVERS = [
    'pool.ntp.org',
    'time.google.com',
    'time.cloudflare.com',
];

// Obtenir l'heure via NTP avec timeout court
function ntp_getTime($format = 'Y-m-d H:i:s') {
    // Sur environnement restreint, utilise l'heure locale
    $time = ntp_query_fast('pool.ntp.org');
    return date($format, $time ?: time());
}

// Requête NTP ultra-rapide (timeout 1 seconde)
function ntp_query_fast($server) {
    try {
        $packet = "\x1b" . str_repeat("\0", 47);
        $socket = @fsockopen("udp://{$server}", 123, $errno, $errstr, 1);
        if (!$socket) return false;

        stream_set_timeout($socket, 1);
        fwrite($socket, $packet);
        $response = @fread($socket, 48);
        fclose($socket);

        if (!$response || strlen($response) < 48) return false;

        $data = unpack('N12', $response);
        $timestamp = $data[9];
        $ntpEpoch = 2208988800;
        $unixTime = $timestamp - $ntpEpoch;

        if ($unixTime < 1000000000 || $unixTime > 9999999999) return false;
        return $unixTime;

    } catch (Exception $e) {
        return false;
    }
}

// Obtenir le timestamp NTP ou local
function ntp_timestamp() {
    $time = ntp_query_fast('pool.ntp.org');
    return $time ?: time();
}

// Vérifier la synchronisation
function ntp_checkSync() {
    $ntpTime   = ntp_query_fast('time.google.com');
    $localTime = time();

    if (!$ntpTime) {
        return [
            'synced'     => true,
            'ntp_time'   => date('Y-m-d H:i:s', $localTime),
            'local_time' => date('Y-m-d H:i:s', $localTime),
            'diff_sec'   => 0,
            'source'     => 'local'
        ];
    }

    $diff = abs($ntpTime - $localTime);
    return [
        'synced'     => $diff < 60,
        'ntp_time'   => date('Y-m-d H:i:s', $ntpTime),
        'local_time' => date('Y-m-d H:i:s', $localTime),
        'diff_sec'   => $diff,
        'source'     => 'ntp'
    ];
}

// Formater la durée
function ntp_formatDuration($seconds) {
    if ($seconds < 60)    return $seconds . 's';
    if ($seconds < 3600)  return floor($seconds/60) . 'min';
    if ($seconds < 86400) return floor($seconds/3600) . 'h';
    return floor($seconds/86400) . 'j';
}
?>