<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Block_Adminhtml_Dashboard_Status extends Mage_Adminhtml_Block_Template {

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->setTemplate('crm4ecommerce_adminstartuppage/dashboard/status.phtml');
    }

}