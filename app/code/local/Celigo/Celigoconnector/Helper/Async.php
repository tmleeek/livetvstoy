<?php
/*
 *	Helper class to make Async calls
*/

class Celigo_Celigoconnector_Helper_Async extends Mage_Core_Helper_Abstract {
    const TYPE_ASYNC = 'async';
    const TYPE_SYNC = 'sync';
    const ROUTE_ASYNC_ORDER_IMPORT = 'celigomagentoconnector/async/orderImport';
    const ROUTE_ASYNC_CUSTOMER_IMPORT = 'celigomagentoconnector/async/customerImport';
    /** @var Celigo_Celigoconnector_Model_Asyncconnection */
    protected $_connection;
    /**
     * Initialize Helper
     */
    private function getConnection() {
        if (!$this->_connection) {
            $this->_connection = Mage::getSingleton('celigoconnector/asyncconnection');
        }
        
        return $this->_connection;
    }
    public function makeAsyncOrderImportCall($orderIds) {
        $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        $url = Mage::getModel('core/store')->load($storeId)->getUrl(self::ROUTE_ASYNC_ORDER_IMPORT, array(
            '_secure' => true
        ));
        $params = array(
            'orderIds' => $orderIds,
            'cc' => Mage::getSingleton('core/session')->getPaymentDetails()
        );
        
        return $this->getConnection()->makeAsyncCall($url, $params);
    }
    public function makeAsyncCustomerImportCall($customerId, $storeId = '', $websiteId = '') {
        $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        $url = Mage::getModel('core/store')->load($storeId)->getUrl(self::ROUTE_ASYNC_CUSTOMER_IMPORT, array(
            '_secure' => true
        ));
        $params = array(
            'customerId' => $customerId,
            'storeId' => $storeId,
            'websiteId' => $websiteId
        );
        
        return $this->getConnection()->makeAsyncCall($url, $params);
    }
}
