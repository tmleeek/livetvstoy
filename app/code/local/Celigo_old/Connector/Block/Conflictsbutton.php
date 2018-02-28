<?php
class Celigo_Connector_Block_Conflictsbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$params = Mage::app()->getRequest()->getParams();
		unset($params['section']); unset($params['key']);
		
        $this->setElement($element);
        $url = $this->getUrl('connector/adminhtml_connector/conflicts', $params);
		
		$html = $this->getLayout()->createBlock('adminhtml/widget_button')
					->setType('button')
					->setClass('scalable')
					->setLabel('Find Extension Conflicts')
					->setOnClick("setLocation('$url')")
					->toHtml();
		
        return $html;
    }
}