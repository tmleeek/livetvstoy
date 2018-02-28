<?php

/**
 * Helper Data
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Observer {

    /**
     * Get new message after User login
     *
     * @param $observer
     * @return CRM4Ecommerce_CRMCore_Model_Observer
     */
    public function adminUserAuthenticatedAfter($observer) {
        if (Mage::getStoreConfig('crm4ecommerce_notification/settings/status')) {
            Mage::helper('crmcore')->loadNotification();
        }
        return $this;
    }

}