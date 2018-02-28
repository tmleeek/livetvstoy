<?php

class Celigo_Celigoconnector_Model_Observer extends Mage_Core_Model_Abstract {
    /** @var Celigo_Celigoconnector_Helper_Data */
    protected $_helper;
    /** @var Celigo_Celigoconnector_Model_Celigoconnector */
    protected $_model;
    const LOG_FILENAME = 'celigo-realtime-import.log';  
    /**
     * Initialize Helper & Model
     */
    public function _construct() {
        $this->_helper = Mage::helper('celigoconnector');
        $this->_model = Mage::getModel('celigoconnector/celigoconnector');
    }
    /**
     * Customer save after event observer
     * @param Varien_Event_Observer $observer
     */
    public function customerSaveAfter($observer) {
        $event = "customer_save_commit_after";
        $record = "customer";
        /* Check if the event triggered by API */
        if ($this->_isApiRequest()) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, Api request."', self::LOG_FILENAME);
            
            return;
        }
        /* Check if the event triggered by Checkout Module */
        if ($this->_isCheckoutRequest()) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring CheckoutRequest."', self::LOG_FILENAME);
            
            return;
        }
        $customer = $observer->getEvent()->getCustomer();
        $keyString = 'customer_save_observer_executed_' . $customer->getId();
        if (Mage::registry($keyString)) {
            
            return;
        }
        $websiteId = $customer->getWebsiteId();
        $storeId = $customer->getStoreId();
        if ($storeId == 0) $storeId = '';
        /** If the Push to NetSuite setting was set to No then return false */
        if (!$this->_helper->getIsCeligoconnectorModuleEnabled($storeId, $websiteId)) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, Celigoconnector is not enabled."', self::LOG_FILENAME);
            
            return;
        }
        /** If the action is required then make following happen */
        try {
            /** Push customer information to NetSuite */
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" recordid="' . $customer->getId() . '" infomsg="Pushing customer to Netsuite."', self::LOG_FILENAME);
            $this->_model->pushCustomerToNetSuite($customer->getId() , $storeId, $websiteId);
            /** Set the registry variable value to customer ID */
            Mage::register($keyString, $customer->getId());
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Address save after event observer
     * @param Varien_Event_Observer $observer
     */
    public function afterAddressSaveAfter($observer) {
        $event = "customer_address_save_commit_after";
        $record = "customer";
        /* Check if the event triggered by API */
        if ($this->_isApiRequest()) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, Api request."', self::LOG_FILENAME);
            
            return;
        }
        /* Check if the event triggered by Checkout Module */
        if ($this->_isCheckoutRequest()) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring CheckoutRequest."', self::LOG_FILENAME);
            
            return;
        }
        /** @var $customerAddress Mage_Customer_Model_Address */
        $customerAddress = $observer->getCustomerAddress();
        $customer = $customerAddress->getCustomer();
        $keyString = 'customer_save_observer_executed_' . $customer->getId();
        if (Mage::registry($keyString)) {
            
            return;
        }
        $websiteId = $customer->getWebsiteId();
        $storeId = $customer->getStoreId();
        if ($storeId == 0) $storeId = '';
        /** If the Push to NetSuite setting was set to No then return false */
        if (!$this->_helper->getIsCeligoconnectorModuleEnabled($storeId, $websiteId)) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, Celigoconnector is not enabled."', self::LOG_FILENAME);
            
            return;
        }
        $importAdditionalAddresses = $this->_helper->canImportCustomerAdditionalAddresses($storeId, $websiteId);
        /** If the address is not default billing or shipping address then return nothing */
        if (!$importAdditionalAddresses && !($customer->getDefaultBilling() == $customerAddress->getEntityId() || $customer->getDefaultShipping() == $customerAddress->getEntityId())) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, address is not default billing or shipping address."', self::LOG_FILENAME);
            
            return;
        }
        /** If the action is required then make following happen */
        try {
            /** Push customer information to NetSuite */
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" recordid="' . $customer->getId() . '" infomsg="Pushing customer to Netsuite."', self::LOG_FILENAME);
            $this->_model->pushCustomerToNetSuite($customer->getId() , $storeId, $websiteId);
            /** Set the registry variable value to customer ID */
            Mage::register($keyString, $customer->getId());
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Checkout Onepage Succuss Action
     *
     * @param Varien_Event_Observer $observer
     */
    /**
     *  This event triggered in following Module Controller Actions
     *  Module: checkout --- Controller: onepage --- Action: success
     *  Module: checkout --- Controller: multishipping --- Action: success
     */
    public function checkoutOnepageSuccussAction($observer) {
        $event = "checkout_onepage_controller_success_action";
        $record = "order";
        $orderIds = $observer->getEvent()->getOrderIds();
        /** Check if the order IDs or valid */
        if (empty($orderIds) || !is_array($orderIds)) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . "Method is checkoutOnepageSuccussAction, Error is: Order Ids ( {{$orderIds}} ) are empty or not an array" . '"', self::LOG_FILENAME);
            
            return;
        }
        try {
            Mage::helper('celigoconnector/celigologger')->info('event='.$event .' record="'.$record.'" infomsg="pushing orderIds to NS" recordid="' . implode($orderIds, ',') . '"', self::LOG_FILENAME);
            $this->_model->pushOrderToNS($orderIds);
            /** Destroy the payment information once the order is pushed */
            Mage::getSingleton('core/session')->setPaymentDetails('');
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
            
            return;
        }
    }
    /**
     * salesQuotePaymentImportDataBefore
     *
     * @param Varien_Event_Observer $observer
     */
    public function salesQuotePaymentImportDataBefore($observer) {
        $storeId = Mage::app()->getStore()->getId();
        /** If the Push to NetSuite setting was set to No then return false */
        if (!$this->_helper->getIsCeligoconnectorModuleEnabled($storeId)) {
            
            return;
        }
        /** Save the payment information into session */
        try {
            Mage::helper('celigoconnector/celigologger')->info(' infomsg="Saving payment information in session" record="order"', self::LOG_FILENAME);
            $paymentData = $observer->getData('input');
            Mage::getSingleton('core/session')->setPaymentDetails($paymentData->getData());
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Event Observer to listen sales order save after event on admin section
     *
     * @param Mage_Sales_Model_Order $observer
     */
    public function orderSaveAfter($observer) {
        $event = "order_save_commit_after";
        $record = "order";
        /* Check if the event triggered by API */
        if ($this->_isApiRequest()) {
            Mage::helper('celigoconnector/celigologger')->info(' event=' . $event . ' record="' . $record . '" infomsg="ignoring, Api request."', self::LOG_FILENAME);
            
            return;
        }
        $order = $observer->getEvent()->getOrder();
        try {
            if ($order->getPushedToNs() === 1) {
                Mage::helper('celigoconnector/celigologger')->info(' infomsg="ignoring as request Already pushed to Netsuite." record="order" recordid=' . $order->getId(), self::LOG_FILENAME);
                
                return;
            } elseif (!$this->_helper->getIsCeligoconnectorModuleEnabled($order->getStoreId())) {
                Mage::helper('celigoconnector/celigologger')->info(' infomsg="ignoring as request, CeligoconnectorModule is not enabled." record="order" recordid=' . $order->getId(), self::LOG_FILENAME);
                
                return;
            }
            $statuses = $this->_helper->getOrderStatusArray($order->getStoreId());
            if (count($statuses) > 0 && in_array($order->getStatus() , $statuses)) {
                $keyString = 'sales_order_save_after_observer_executed_' . $order->getId();
                if (Mage::registry($keyString)) {
                    
                    return;
                }
                // Push to NetSuite
                Mage::helper('celigoconnector/celigologger')->info(' infomsg="pushing Order to NS" record="order" recordid=' . $order->getId(), self::LOG_FILENAME);
                Mage::getModel('celigoconnector/celigoconnector')->pushOrderToNS($order->getId());
                /** Set the registry variable to order ID */
                Mage::register($keyString, $order->getId());
            }
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Return true if the reqest is made via the api
     *
     * @return boolean
     */
    private function _isApiRequest() {
        
        return Mage::app()->getRequest()->getModuleName() === 'api';
    }
    /**
     * Return true if the reqest is made via checkout module
     *
     * @return boolean
     */
    private function _isCheckoutRequest() {
        $modName = Mage::app()->getRequest()->getModuleName();
        if (isset($modName) && $modName != '') {
            $pos = strpos(strtolower($modName) , 'checkout');
            if ($pos !== false) {
                $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
                if ($quote->getId()) {
                    $quoteData = $quote->getData();
                    /* If user chooses Register */
                    if (isset($quoteData['checkout_method']) &&
                            $quoteData['checkout_method'] == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    /**
     * Event Observer to listen sales order save after event on frontend
     *
     * @param Mage_Sales_Model_Order $observer
     */
    public function paypalOrderSaveAfter($observer) {
        try {
            /* Check if the event triggered by PayPal IPN controller */
            if ($this->_isPaypalIpnRequest()) {
                $this->orderSaveAfter($observer);
            }
        } catch (Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }

    /**
     * Return true if the reqest is made via Paypal IPN controller
     *
     * @return boolean
     */
    private function _isPaypalIpnRequest() {
        return Mage::app()->getRequest()->getModuleName() === 'paypal' && Mage::app()->getRequest()->getControllerName() === 'ipn';
    }
}
