<?php

class Celigo_Celigoconnector_Block_Restbutton extends Mage_Adminhtml_Block_System_Config_Form_Field {
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $params = Mage::app()->getRequest()->getParams();
        unset($params['section']);
        unset($params['key']);
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/celigoconnector/resturl', $params);
        $enabled = Mage::helper('celigoconnector')->showCheckNowButton();
        if ($enabled) {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setClass('scalable')->setLabel('Fetch RESTlet URL')->setOnClick("setLocation('$url')")->toHtml();
        } else {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setDisabled('disabled')->setClass('scalable')->setLabel('Fetch RESTlet URL')->setOnClick("setLocation('$url')")->toHtml();
        }
        
        return $html;
    }
}
