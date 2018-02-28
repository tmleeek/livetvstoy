<?php

$paths = array(
    dirname(dirname(dirname(dirname(__FILE__)))) . '/app/Mage.php',
    '../../../app/Mage.php',
    '../../app/Mage.php',
    '../app/Mage.php',
    'app/Mage.php',
);

foreach ($paths as $path) {
    if (file_exists($path)) {
        require $path; 
        break;
    }
}

Mage::app('admin')->setUseSessionInUrl(false);
error_reporting(E_ALL | E_STRICT);
if (file_exists(BP.DS.'maintenance.flag')) exit;
if (class_exists('Kwanso_Getresponse_Model_Cron') === false) exit;


try {
	@set_time_limit(0);
	Kwanso_Getresponse_Model_Cron::importContacts();
} catch (Exception $e) {
	Mage::logException($e);
}