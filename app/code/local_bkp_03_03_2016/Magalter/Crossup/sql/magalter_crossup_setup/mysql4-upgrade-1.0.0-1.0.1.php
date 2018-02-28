<?php
 
$this->startSetup();

$default = Mage::getModel('core/config_data')
        ->getCollection()
        ->addFieldToFilter('path', 'magalter_crossup/configuration/position')
        ->addFieldToFilter('scope', 'default')
        ->addFieldToFilter('scope_id', 0)
        ->getSize();
 
if (!$default) {
    if (Mage::helper('magalter_crossup')->isEe()) {
        $value = "$$('.totals')[0]";
    } else {
        $value = "$$('.cart-collaterals')[0]";
    }
    Mage::getModel('core/config_data')
            ->setPath('magalter_crossup/configuration/position')
            ->setValue($value)
            ->save();     
}


$this->endSetup();



