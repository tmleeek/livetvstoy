<?php
class Celigo_Connector_Model_System_Config_Source_Cronfrequency extends Mage_Core_Model_Abstract
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '*/15 * * * *', 'label'=>Mage::helper('connector')->__('Every 15 minutes')),
            array('value' => '0,30 * * * *', 'label'=>Mage::helper('connector')->__('Every 30 minutes')),
            array('value' => '0 * * * *', 'label'=>Mage::helper('connector')->__('Hourly')),
            array('value' => '0 0 * * *', 'label'=>Mage::helper('connector')->__('Daily')),
            array('value' => '0 0 * * 0', 'label'=>Mage::helper('connector')->__('Monthly')),
        );
    }
}