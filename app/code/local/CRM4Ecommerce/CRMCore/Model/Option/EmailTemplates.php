<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_EmailTemplates extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    public function toOptionArray() {
        $_templates = Mage_Core_Model_Email_Template::getDefaultTemplates();
        $templates = array();
        $default_templates = array();
        foreach ($_templates as $_template_id => $_template_value) {
            $default_templates[] = array(
                'value' => $_template_id,
                'label' => $_template_value['label']
            );
        }

        $custom_templates = array();
        $collection = Mage::getResourceModel('core/email_template_collection')->load();
        if (count($collection)) {
            foreach ($collection as $template) {
                $custom_templates[] = array(
                    'value' => $template->getTemplateId(),
                    'label' => $template->getTemplateCode()
                );
            }
        }

        $templates[] = array(
            'label' => Mage::helper('crmcore')->__('Template Default'),
            'value' => $default_templates
        );

        if (count($custom_templates)) {
            $templates[] = array(
                'label' => Mage::helper('crmcore')->__('Template Custom'),
                'value' => $custom_templates
            );
        }
        return $templates;
    }

}