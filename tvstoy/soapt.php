<?php

$url = 'https://admin1.tystoybox.com/index.php/api/index/?wsdl=1';
echo "\nConnecting to\n$url\n";

for ($i=0; $i<100; $i++) {
    /** @var SoapClient $client */
    $client = new SoapClient($url);
    echo $i."\t";
    try {
        echo $client->login('username', 'key')."\n";
    } catch (SoapFault $e) {
        echo substr($e->getMessage(), 0, 50)."\n";
    }
    unset($client);
}
