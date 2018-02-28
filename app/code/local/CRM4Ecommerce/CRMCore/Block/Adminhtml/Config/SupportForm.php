<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Block_Adminhtml_Config_SupportForm extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        return $this->_getInfoHtml($element);
    }

    protected function _getInfoHtml($fieldset) {
        $field = $fieldset->addField('support_form', 'label',
                        array(
                            'name' => 'support_form',
                            'value' => Mage::getBlockSingleton('crmcore/adminhtml_config_html_form_support')
                                ->toHtml()
                ))->setRenderer(Mage::getBlockSingleton('crmcore/adminhtml_config_html_form'));
        return $field->toHtml();
    }

}