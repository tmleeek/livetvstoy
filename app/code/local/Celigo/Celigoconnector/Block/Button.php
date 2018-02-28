<?php

class Celigo_Celigoconnector_Block_Button extends Mage_Adminhtml_Block_System_Config_Form_Field {
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $params = Mage::app()->getRequest()->getParams();
        unset($params['section']);
        unset($params['key']);
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/celigoconnector', $params);
        $enabled = Mage::helper('celigoconnector')->showCheckNowButton();
        if ($enabled) {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setClass('scalable')->setLabel('Check Now')->setOnClick("setLocation('$url')")->toHtml();
        } else {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setDisabled('disabled')->setClass('scalable')->setLabel('Check Now')->setOnClick("setLocation('$url')")->toHtml();
        }
        
        return $html;
    }
}
