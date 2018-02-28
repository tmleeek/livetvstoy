<?php
class Celigo_Connector_Block_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return "<strong>" . (string) Mage::helper('connector')->getExtensionVersion() . "</strong>";
    }
}