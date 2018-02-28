<?php
class Celigo_Connector_Block_Restbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$storeId = ''; $websiteId = '';

		$params = Mage::app()->getRequest()->getParams();
		unset($params['section']); unset($params['key']);
		
		if(isset($params['store']) && $params['store'] != '') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		
		if(isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}
		
        $this->setElement($element);
        $url = $this->getUrl('connector/adminhtml_connector/resturl', $params);
		
		
		$enabled = Mage::helper('connector')->showCheckNowButton($storeId, $websiteId);

		if ($enabled) {
			$html = $this->getLayout()->createBlock('adminhtml/widget_button')
						->setType('button')
						->setClass('scalable')
						->setLabel('Fetch RESTlet URL')
						->setOnClick("setLocation('$url')")
						->toHtml();
		} else {
			$html = $this->getLayout()->createBlock('adminhtml/widget_button')
						->setType('button')
						->setDisabled('disabled')
						->setClass('scalable')
						->setLabel('Fetch RESTlet URL')
						->setOnClick("setLocation('$url')")
						->toHtml();
		}
		
        return $html;
    }
}