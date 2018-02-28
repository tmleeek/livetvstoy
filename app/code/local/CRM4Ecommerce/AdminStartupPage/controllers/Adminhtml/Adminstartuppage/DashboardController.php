<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Adminhtml_Adminstartuppage_DashboardController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('crm4ecommerce/adminstartuppage')
                ->_addBreadcrumb(
                        Mage::helper('adminstartuppage')->__('Admin Startup Page'), Mage::helper('adminstartuppage')->__('Admin Startup Page')
                )
                ->_addBreadcrumb(
                        Mage::helper('adminstartuppage')->__('Dashboard'), Mage::helper('adminstartuppage')->__('Dashboard')
                )
                ->_title(
                        Mage::helper('adminstartuppage')->__('Admin Startup Page'), Mage::helper('adminstartuppage')->__('Admin Startup Page')
                )
                ->_title(
                        Mage::helper('adminstartuppage')->__('Dashboard'), Mage::helper('adminstartuppage')->__('Dashboard')
        );
        return $this;
    }

    public function clearAllRegisterInformationAction() {
        if (Mage::helper('adminstartuppage')->clearAllRegisterInformation()) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminstartuppage')->__('Clear all register information successfully')
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('adminstartuppage')->__('Clear all register information unsuccessfully')
            );
        }

        $this->_redirect('*/*/');
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

}