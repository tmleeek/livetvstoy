<?php
    require_once('_check_ip.php');
    require_once('_check_auth.php');
?>

<?php

require_once '../app/Mage.php';

Mage::app()->getCacheInstance()->flush();

if (Mage::helper('core')->isModuleEnabled('Amasty_Fpc')) {
    Mage::getSingleton('amfpc/fpc')->flush();
}

echo 'Done.';
