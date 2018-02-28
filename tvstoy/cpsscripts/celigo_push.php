<?php
/*
* This script is to invoke Celigo push script which is scheduled at main menu System->Scheduler->Schedule Configuration->push_orders_to_ns
* which is the same as System->Configuraion->Celigo Magento Connector Plus->Cron Settings->'On Demand Order Import' button
* So this script can be treat as a place holder for the above two 
*/
include_once "/var/www/CPS/public_html/app/Mage.php";
include_once "/var/www/CPS/public_html/app/code/local/Celigo/Celigoconnector/Model/Cron.php";

Mage::app('admin')->setCurrentStore(0);
Mage::app('default');
$cron = Mage::getModel('celigoconnector/cron');
$cron->pushOrdersToNs();
?>
