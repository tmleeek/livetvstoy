<?php
class Celigo_Connector_Model_Observer extends Mage_Core_Model_Abstract
{
    /** @var Celigo_Connector_Helper_Data */
    protected $_helper;
    protected $_model;
	
    /**
     * Initialize Helper
     */
    public function _construct()
    {
        $this->_helper = Mage::helper('connector');
        $this->_model = Mage::getModel('connector/connector');
    }

	/**
	 * Customer save after send the customer data to RESt URL
	 * Module: admin --- Controller: customer --- Action: massSubscribe
	 * @param Varien_Event_Observer $observer
	 */
	public function customerSaveAfter($observer)
	{
		if($this->_isApiRequest()) { 
			return;
		}

		$customer = $observer->getEvent()->getCustomer();
		if (Mage::registry('customer_save_observer_executed')) {
			$custId = Mage::registry('customer_save_observer_executed');
			if ($custId == $customer->getId()) {
				return;
			}
		}
		
		$storeId = ''; $storeId = Mage::app()->getStore()->getId();
		$websiteId = ''; $websiteId = Mage::app()->getStore()->getWebsiteId();
		/** Check if it is triggered from backend */
		if (Mage::app()->getRequest()->getModuleName() == $this->_helper->getAdminModuleName()) {
			$storeId = $customer->getStoreId();
			if($storeId == 0) $storeId = '';
			$websiteId = $customer->getWebsiteId();
		}
		
		/** If the Push to NetSuite setting was set to No then return false */
		if (!$this->_helper->getIsConnectorModuleEnabled($storeId, $websiteId)) {
			return;
		}
		
		/** If the action is required then make following happen */
		try {
			$customerId = $customer->getId();
		
			$this->_model->pushCustomerToNetSuite($customerId, $storeId, $websiteId);

			/** Set the customer_save_observer_executed to customer ID */
			Mage::register('customer_save_observer_executed', $customer->getId());
				
		} catch (Exception $e) {
			$this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
		}
	}
	
    /**
     * Address after save event handler
     *
     * @param Varien_Event_Observer $observer
     */
    public function afterAddressSave($observer)
    {
    	if($this->_isApiRequest()) { 
			return;
    	}
		
        /** @var $customerAddress Mage_Customer_Model_Address */
        $customerAddress = $observer->getCustomerAddress();
        $customer = $customerAddress->getCustomer();
		
		if (Mage::registry('customer_save_observer_executed')) {
			$custId = Mage::registry('customer_save_observer_executed');
			if ($custId == $customer->getId()) {
				return;
			}
		}
		
		$storeId = ''; $storeId = Mage::app()->getStore()->getId();
		$websiteId = ''; $websiteId = Mage::app()->getStore()->getWebsiteId();
		/** Check if it is triggered from backend */
		if (Mage::app()->getRequest()->getModuleName() == $this->_helper->getAdminModuleName()) {
			$storeId = $customer->getStoreId();
			if ($storeId == 0) $storeId = '';
			$websiteId = $customer->getWebsiteId();
		}
		
		/** If the Push to NetSuite setting was set to No then return false */
		if (!$this->_helper->getIsConnectorModuleEnabled($storeId, $websiteId)) {
			return;
		}

		/** If the address is not default billing or shipping address then return nothing */
		if(!($customer->getDefaultBilling() == $customerAddress->getEntityId()  || $customer->getDefaultShipping() == $customerAddress->getEntityId())) {
			return;
		}
		
		/** If the action is required then make following happen */
        try {
			$customerId = $customer->getId();
		
			$this->_model->pushCustomerToNetSuite($customerId, $storeId, $websiteId);
			
			/** Set the customer_save_observer_executed to customer ID */
			Mage::register('customer_save_observer_executed', $customer->getId());
		
        } catch (Exception $e) {
            $this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
    }
	
    /**
     * Checkout Onepage Succuss Action
     *
     * @param Varien_Event_Observer $observer
     */
	/**
	 *	This event is triggered in following Module Controller Actions
	 *	Module: checkout --- Controller: onepage --- Action: success
	 *	Module: checkout --- Controller: multishipping --- Action: success
	 */
    public function checkoutOnepageSuccussAction($observer)
    {
		# $orderIds = $observer->getData('order_ids');
        $orderIds = $observer->getEvent()->getOrderIds();
		
		$storeId = '';
		$storeId = Mage::app()->getStore()->getId();
		
		/** If the Push to NetSuite setting was set to No then return false */
		if (!$this->_helper->getIsConnectorModuleEnabled($storeId)) {
			return;
		}
		
		/** Check if the order IDs or valid */
        if (empty($orderIds) || !is_array($orderIds)) {
			$this->_helper->addErrorMessageToLog("Method is checkoutOnepageSuccussAction, Error is: Order Ids ( {{$orderIds}} ) are empty or not an array", Zend_Log::ERR, $storeId);
            return;
        }
		
        try {
			
			foreach($orderIds as $orderid) {
				$result = $this->_model->pushOrderToNS($orderid);
			}

			/** Destroy the payment information once the order is successful */
			Mage::getSingleton('core/session')->setPaymentDetails('');

        } catch (Exception $e) {
            $this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, '');
			return;
        }
    }
	
    /**
     * Checkout Submit All After
     *
     * @param Varien_Event_Observer $observer
     */
	/**
	 *	Module: admin --- Controller: sales_order_create --- Action: save
	 */
    public function checkoutSubmitAllAfter($observer)
    {
	
		if ($this->_checkRequestRoute("checkout", "onepage", "success"))
			return;
		if ($this->_checkRequestRoute("checkout", "onepage", "saveorder"))
			return;
		if ($this->_checkRequestRoute("checkout", "multishipping", "success"))
			return;
		if ($this->_checkRequestRoute("checkout", "multishipping", "overviewpost"))
			return;
		if ($this->_isApiRequest()) return '';
	
		$orders = $observer->getData('order');
		$quote = $observer->getData('quote');
		
		$storeId = '';
		$storeId = Mage::app()->getStore()->getId();
		/** Check if it is triggered from backend */
		if (Mage::app()->getRequest()->getModuleName() == $this->_helper->getAdminModuleName()) {
			$storeId = $orders->getStoreId();
		}
		
		if ($storeId == '') {
			$storeId = $orders->getStoreId();
		}
		
		/** If the Push to NetSuite setting was set to No then return false */
		if (!$this->_helper->getIsConnectorModuleEnabled($storeId)) {
			return;
		}
	
        $orderId = $orders->getId();
        if ($orderId == "" ) {
			$this->_helper->addErrorMessageToLog("Method is checkoutSubmitAllAfter, Error is: Order Id ( {{$orderId}} ) is empty", Zend_Log::ERR, $storeId);
            return;
        }
		
        try {

			// Make a Rest call Here
			$result = $this->_model->pushOrderToNS($orderId);

			/** Destroy the payment information once the order is successful */
			Mage::getSingleton('core/session')->setPaymentDetails('');
			
        } catch (Exception $e) {
            $this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, '');
        }
    }
	
    /**
     * salesQuotePaymentImportDataBefore
     *
     * @param Varien_Event_Observer $observer
     */
    public function salesQuotePaymentImportDataBefore($observer)
    {
		/**
		 *	This method is triggered in following senarios
		 *	Module: checkout --- Controller: onepage --- Action: savePayment
		 *	Module: checkout --- Controller: onepage --- Action: saveOrder
		 *	Module: checkout --- Controller: multishipping --- Action: overview
		 */
		 $storeId = '';
		 $storeId = Mage::app()->getStore()->getId();
		/** If the Push to NetSuite setting was set to No then return false */
		if (!$this->_helper->getIsConnectorModuleEnabled($storeId)) {
			return;
		}
		
		$paymentData = $observer->getData('input');
		
		/** Save the payment information into session */
        try {
		
			Mage::getSingleton('core/session')->setPaymentDetails($paymentData->getData());
		
        } catch (Exception $e) {
            $this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, '');
        }
    }
	
	/** All the following methods are connector supporting methods used or called from Observer methods above  */

	/**
	 * Return true if the reqest is made via the api
	 *
	 * @return boolean
	 */
	private function _isApiRequest()
	{
		return Mage::app()->getRequest()->getModuleName() === 'api';
	}

	/**
	 * Check the current module, controller and action against the given values.
	 *
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @return bool
	 */
	private function _checkRequestRoute($module, $controller, $action)
	{
		if ($module == "admin")
			$module = $this->_helper->getAdminModuleName();
			
		$req = Mage::app()->getRequest();
		if (strtolower($req->getModuleName()) == $module
				&& strtolower($req->getControllerName()) == $controller && strtolower($req->getActionName()) == $action) {
			return true;
		}
		return false;
	}
}