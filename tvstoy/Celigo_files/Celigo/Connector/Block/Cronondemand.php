<?php
class Celigo_Connector_Block_Cronondemand extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$params = Mage::app()->getRequest()->getParams();
		unset($params['section']); unset($params['key']);
		
        $this->setElement($element);
        $url = $this->getUrl('connector/adminhtml_connector/cronondemand', $params);
		
		if (Mage::getModel('connector/cron')->isConnectorCronEnabled()) {
			$html = $this->getLayout()->createBlock('adminhtml/widget_button')
						->setType('button')
						->setClass('scalable')
						->setLabel('On Demand Order Import')
						->setOnClick("setLocation('$url')")
						->toHtml();
		} else {
			$html = $this->getLayout()->createBlock('adminhtml/widget_button')
						->setType('button')
						->setDisabled('disabled')
						->setClass('scalable')
						->setLabel('On Demand Order Import')
						->setOnClick("setLocation('$url')")
						->toHtml();
		}
		return $html;
    }
}