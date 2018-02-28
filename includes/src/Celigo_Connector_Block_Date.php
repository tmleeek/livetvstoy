<?php
class Celigo_Connector_Block_Date extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $date = new Varien_Data_Form_Element_Date;
        $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $data = array(
            'name'      => $element->getName(),
            'html_id'   => $element->getId(),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'readonly'  => 'readonly',
        );
        $date->setData($data);
        $date->setValue($element->getValue(), $format);
        //$date->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
        $date->setFormat("MM/dd/yyyy");
        $date->setForm($element->getForm());

        return $date->getElementHtml();
    }
}