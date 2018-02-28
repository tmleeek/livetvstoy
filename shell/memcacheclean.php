<?php
define('ADMIN_USERNAME','memcache'); 	// Admin Username
define('ADMIN_PASSWORD','password');  	// Admin Password

$MEMCACHE_SERVERS[] = '10.0.0.27:11211'; // add more as an array
$MEMCACHE_SERVERS[] = '10.0.0.27:11212'; // add more as an array


////////// END OF DEFAULT CONFIG AREA /////////////////////////////////////////////////////////////

///////////////// Password protect ////////////////////////////////////////////////////////////////
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
           $_SERVER['PHP_AUTH_USER'] != ADMIN_USERNAME ||$_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD) {
			Header("WWW-Authenticate: Basic realm=\"Memcache Login\"");
			Header("HTTP/1.0 401 Unauthorized");

			echo <<<EOB
				<html><body>
				<h1>Rejected!</h1>
				<big>Wrong Username or Password!</big>
				</body></html>
EOB;
			exit;
}

clearmemcache('10.0.0.27', '11211', 'flush_all');
clearmemcache('10.0.0.27', '11212', 'flush_all');

function clearmemcache($server, $port, $command)
{
    $s = @fsockopen($server, $port);
    if (!$s) {
        die("Cant connect to:" . $server . ':' . $port);
    }

    fwrite($s, $command . "\r\n");

    $buf = '';
    while ((!feof($s))) {
        $buf .= fgets($s, 256);
        if (strpos($buf, "END\r\n") !== false) { // stat says end
            break;
        }
        if (strpos($buf, "DELETED\r\n") !== false || strpos($buf, "NOT_FOUND\r\n") !== false) { // delete says these
            break;
        }
        if (strpos($buf, "OK\r\n") !== false) { // flush_all says ok
            echo '<br/><br/>Success';
            break;
        }
    }
    fclose($s);
}
?>