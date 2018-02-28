<?php

/**
 *   
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Block_Adminhtml_Config_Html_Form extends Mage_Adminhtml_Block_System_Config_Form_Field implements Varien_Data_Form_Element_Renderer_Interface {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $html = $element->getBold() ? '<strong>' : '';
        $html.= $element->getValue();
        $html.= $element->getBold() ? '</strong>' : '';
        $html.= $element->getAfterElementHtml();
        return $html;
    }

}