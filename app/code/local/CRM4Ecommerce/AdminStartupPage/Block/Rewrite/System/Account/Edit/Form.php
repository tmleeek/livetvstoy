<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Block_Rewrite_System_Account_Edit_Form extends Mage_Adminhtml_Block_System_Account_Edit_Form {

    protected function _prepareForm() {
        parent::_prepareForm();
        if (Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/status') == CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_ENABLED
                && Mage::getSingleton('admin/session')->isAllowed(CRM4Ecommerce_AdminStartupPage_Helper_Data::ACL_CHANGE_STARTUP_PAGE)) {
            $userId = Mage::getSingleton('admin/session')->getUser()->getId();
            $user = Mage::getModel('admin/user')
                    ->load($userId);

            $form = $this->getForm();
            $fieldset = $form->addFieldset('startup_fieldset', array(
                'legend' => Mage::helper('adminstartuppage')->__('Startup Page Information'))
            );
            
            $prefix = $form->getHtmlIdPrefix();
            if (is_null($prefix)) {
                $prefix = '';
            }
            
            $fieldset->addField('crm4e_startup_page_usedefault', 'checkbox', array(
                'name' => 'crm4e_startup_page_usedefault',
                'checked' => $user->getData('crm4e_startup_page_usedefault') == '1',
                'label' => Mage::helper('adminstartuppage')->__('Use Default Startup Page'),
                'title' => Mage::helper('adminstartuppage')->__('Use Default Startup Page'),
                'required' => false,
                'value'  => '1',
                'onchange' => "if (this.checked) { $('" . $prefix . "crm4e_startup_page').disable(); } else { $('" . $prefix . "crm4e_startup_page').enable(); }",
                    )
            );
            $fieldset->addField('crm4e_startup_page', 'select', array(
                'name' => 'crm4e_startup_page',
                'label' => Mage::helper('adminstartuppage')->__('Startup Page'),
                'title' => Mage::helper('adminstartuppage')->__('Startup Page'),
                'disabled' => $user->getData('crm4e_startup_page_usedefault') == '1',
                'value' => $user->getData('crm4e_startup_page'),
                'values' => Mage::getModel('adminhtml/system_config_source_admin_page')->toOptionArray(),
                'required' => false,
                    )
            );
        }
        return $this;
    }

}