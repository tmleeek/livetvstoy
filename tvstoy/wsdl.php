<?php

$client = new SoapClient('https://admin.tystoybox.com/index.php/api/index/index/?wsdl=1');



// If some stuff requires api authentification,

// then get a session token

$session = $client->login('testvtrio', 'test1234');

$complexFilter = array(

    'complex_filter' => array(

        array(

            'key' => 'type',

            'value' => array('key' => 'in', 'value' => 'configurable')

        )

    )

);

$result = $client->catalogProductList($session, $complexFilter);



var_dump ($result);

?>
