<?php

class Celigo_Celigoconnector_AsyncController extends Mage_Core_Controller_Front_Action {
    /** @var Celigo_Celigoconnector_Helper_Data */
    protected $_helper;
    protected $_model;
    const LOG_FILENAME = 'celigo-realtime-import.log';
    /**
     * Initialize Helper
     */
    public function _construct() {
        //$this->_helper = Mage::helper('celigoconnector');
        $this->_model = Mage::getModel('celigoconnector/celigoconnector');
    }
    public function indexAction() {
        $this->getResponse()->setRedirect(Mage::getBaseUrl());
    }
    public function orderImportAction() {
        Mage::helper('celigoconnector/celigologger')->info('infomsg="Inside OrderImportAction" class="Celigo_Celigoconnector_AsyncController"', self::LOG_FILENAME);
        ignore_user_abort(true);
        set_time_limit(0);
        try {
            $data = $this->getRequest()->getPost();
            if (isset($data['orderIds'][0]) && trim($data['orderIds'][0]) != '') {
                $orderIds = $data['orderIds'];
                if (isset($data['cc'])) {
                    Mage::getSingleton('core/session')->setPaymentDetails($data['cc']);
                }
                Mage::helper('celigoconnector/celigologger')->info('infomsg="Setting payment info and starting sync call to pushOrderToNS." class="Celigo_Celigoconnector_AsyncController" record="order" recprdid=' . implode(",", $orderIds), self::LOG_FILENAME);
                $this->_model->pushOrderToNS($orderIds, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC);
            } else {
                $this->getResponse()->setRedirect(Mage::getBaseUrl());
            }
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
            $this->getResponse()->setRedirect(Mage::getBaseUrl());
        }
    }
    public function customerImportAction() {
        Mage::helper('celigoconnector/celigologger')->info('infomsg="Inside customerImportAction" class="Celigo_Celigoconnector_AsyncController"', self::LOG_FILENAME);
        ignore_user_abort(true);
        set_time_limit(0);
        try {
            $data = $this->getRequest()->getPost();
            $customerId = $storeId = $websiteId = '';
            if (isset($data['customerId']) && trim($data['customerId']) != '') {
                $customerId = $data['customerId'];
            }
            if (isset($data['storeId']) && trim($data['storeId']) != '') {
                $storeId = $data['storeId'];
            }
            if (isset($data['websiteId']) && trim($data['websiteId']) != '') {
                $websiteId = $data['websiteId'];
            }
            if (isset($data['customerId']) && trim($data['customerId']) != '') {
                // Push the customer to NetSuite
                Mage::helper('celigoconnector/celigologger')->info('infomsg="starting sync call to pushCustomerToNetSuite." class="Celigo_Celigoconnector_AsyncController" record="customer" recprdid=' . $data['customerId'], self::LOG_FILENAME);
                $this->_model->pushCustomerToNetSuite($customerId, $storeId, $websiteId, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC);
            } else {
                $this->getResponse()->setRedirect(Mage::getBaseUrl());
            }
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
            $this->getResponse()->setRedirect(Mage::getBaseUrl());
        }
    }
}
