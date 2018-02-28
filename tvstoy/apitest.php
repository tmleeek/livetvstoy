<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$client = new SoapClient('https://www.tystoybox.com/index.php/api/v2_soap/?wsdl'); // TODO : change url
$sessionId = $client->login("celigo-ws-cps","celigows4cps");

$result = $client->salesOrderInfo($sessionId, '500000235');

echo '<pre>';
print_r($result);
echo '</pre>';
