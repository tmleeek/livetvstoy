<?php
// initialize Magento environment
include_once "app/Mage.php";

// initialize M2E Model for cron job
include_once "app/code/community/Ess/M2ePro/Model/Cron/Type/Magento.php";

Mage::app('admin')->setCurrentStore(0);
Mage::app('default');

$cron = Mage::getModel('M2ePro/Cron_Type_Magento');

$cron->process();
?>
