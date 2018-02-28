<?php

class Celigo_Celigoconnectorplus_Block_Ordercancelimportondemand extends Mage_Adminhtml_Block_System_Config_Form_Field {
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $params = Mage::app()->getRequest()->getParams();
        unset($params['section'], $params['key']);
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/celigoconnectorplus/ordercancelimportondemand', $params);
        if (Mage::getModel('celigoconnector/cron')->isCeligoconnectorCronEnabled()) {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setClass('scalable')->setLabel('On Demand Order Cancel Import')->setOnClick("setLocation('$url')")->toHtml();
        } else {
            $html = $this->getLayout()->createBlock('adminhtml/widget_button')->setType('button')->setDisabled('disabled')->setClass('scalable')->setLabel('On Demand Order Cancel Import')->setOnClick("setLocation('$url')")->toHtml();
        }
        
        return $html;
    }
}
