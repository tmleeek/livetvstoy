<?php

class Celigo_Celigoconnector_Model_System_Config_Source_Loglevels extends Mage_Core_Model_Abstract {
    public function toOptionArray() {
        
        return array(
            array(
                'value' => - 1,
                'label' => Mage::helper('adminhtml')->__('No Logging')
            ) ,
            array(
                'value' => 3,
                'label' => Mage::helper('adminhtml')->__('Error Logging')
            ) ,
            array(
                'value' => 6,
                'label' => Mage::helper('adminhtml')->__('Info Logging')
            )
        );
    }
}
