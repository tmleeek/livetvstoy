<?php
/*
 *	Helper class to make Async calls
*/

class Celigo_Celigoconnectorplus_Helper_Async extends Mage_Core_Helper_Abstract {
    const TYPE_ASYNC = 'async';
    const TYPE_SYNC = 'sync';
    const ROUTE_ASYNC_ORDER_CANCEL_IMPORT = 'celigomagentoconnectorplus/async/orderCancelImport';
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
    public function makeAsyncOrderCancelImportCall($orderId) {
        $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        $url = Mage::getModel('core/store')->load($storeId)->getUrl(self::ROUTE_ASYNC_ORDER_CANCEL_IMPORT, array(
            '_secure' => true
        ));
        $params = array(
            'orderId' => $orderId
        );
        
        return $this->getConnection()->makeAsyncCall($url, $params);
    }
}
