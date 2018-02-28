<?php

function getIpAddress() {
    $ip = '';
    if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip .= getenv('HTTP_X_FORWARDED_FOR');
    }
    if (getenv('REMOTE_ADDR')) {
        $ip .= getenv('REMOTE_ADDR');
    }
    return $ip;
}

$ip = getIpAddress();

if (file_exists('config.php')) {
    $settings = parse_ini_file('config.php', true);
    if ($settings === FALSE) {
        die('Unable to parse config.php');
    }
} else {
    die('Config file: config.php not found');
}

if (isset($settings['ip_whitelist'])) {
    $isAllowed = FALSE;
    $whitelist = $settings['ip_whitelist'];
    foreach ($whitelist as $value) {
        if (strstr($ip, $value)) {
            $isAllowed = TRUE;
        }
    }
} else {
    die('[ip_whitelist] section not set in config.php');
}

if (! $isAllowed) {
    die('Your ip (' . $ip . ') is not allowed');
}

?>
