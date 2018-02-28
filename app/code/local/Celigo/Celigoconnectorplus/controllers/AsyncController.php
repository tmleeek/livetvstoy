<?php

class Celigo_Celigoconnectorplus_AsyncController extends Mage_Core_Controller_Front_Action {
    /** @var Celigo_Celigoconnector_Helper_Data */
    protected $_helper;
    protected $_model;
    /**
     * Initialize Helper
     */
    public function _construct() {
        $this->_helper = Mage::helper('celigoconnector');
        $this->_model = Mage::getModel('celigoconnectorplus/sales_order_cancel');
    }
    public function indexAction() {
        $this->getResponse()->setRedirect(Mage::getBaseUrl());
    }
    public function orderCancelImportAction() {
        ignore_user_abort(true);
        set_time_limit(0);
        try {
            $data = $this->getRequest()->getPost();
            if (isset($data['orderId']) && trim($data['orderId']) != '') {
                $orderId = $data['orderId'];
                $order = Mage::getModel('sales/order')->load($orderId);
                $this->_model->pushCancelledOrderToNS($orderId, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC);
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
