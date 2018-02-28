<?php
class Celigo_Connector_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
	const ORDER_NOT_EXISTS_MSG		= 'This Order does not exists';
	public function indexAction() {
		$this->_redirect('adminhtml/sales_order');
	}
	
	private function getStoreId() {
		$storeId = '';
		$params = Mage::app()->getRequest()->getParams();
		if (isset($params['store']) && $params['store'] != '') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		return $storeId;
	}
	
	private function getWebsiteId() {
		$websiteId = '';
		$params = Mage::app()->getRequest()->getParams();
		if (isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}
		return $websiteId;
	}
	
    /**
     * Push the selected orders to NetSuite if not pushed
     */
	public function massAction() 
	{
	   $storeId = $this->getStoreId(); $websiteId = $this->getWebsiteId();
       try {
			$params = Mage::app()->getRequest()->getParams();
			unset($params['key']);
			
			$orderIds = $this->getRequest()->getPost('order_ids', array());
			$countPushedOrder = 0; $countNonPushedOrder = 0;
			$pushedOrders = array(); $nonPushedOrders = array();
			foreach ($orderIds as $orderId) {
				$order = Mage::getModel('sales/order')->load($orderId);
				
				$storeId = $order->getStoreId();
				/** If the Push to NetSuite setting was set to No then return false */
				if (!Mage::helper('connector')->getIsConnectorModuleEnabled($storeId)) {
					continue;
				}
				/** Get all the order statuses from setting that need to be synced to NetSuite */
				$status = Mage::helper('connector')->getOrderStatusArray($storeId);
				if (count($status) == 0) {
					continue;
				}
				
				if ($order->getId() && !$order->getPushedToNs()) {
				
					// Push to NetSuite functionality goes Here
					$result = Mage::getModel('connector/connector')->pushOrderToNS($orderId);
					if($result === true) {
						$pushedOrders[] = $order->getIncrementId();
						$countPushedOrder++;
					} else {
						$this->_getSession()->addError($this->__('Error (Order#  '.$order->getIncrementId(). ') : '. $result));
					}
					
				} else {
					$countNonPushedOrder++;
					$nonPushedOrders[] = $order->getIncrementId();
				}
			}
			if ($countPushedOrder) {
				$this->_getSession()->addSuccess($this->__('%s order(s) have been pushed to NetSuite. <br/> %s', $countPushedOrder, implode(", ", $pushedOrders)));
			}
			if ($countNonPushedOrder) {
				$this->_getSession()->addError($this->__('%s order(s) were already pushed to NetSuite. <br/> %s', $countNonPushedOrder, implode(", ", $nonPushedOrders)));
			}
			
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
        $this->_redirect('adminhtml/sales_order');
	}
	
    /**
     * Push the selected order to NetSuite if not pushed
     */
	public function pushorderAction() 
	{
		$storeId = $this->getStoreId(); $websiteId = $this->getWebsiteId();
        try {
		
			$orderId = $this->getRequest()->getParam('order_id', array());
			$params = Mage::app()->getRequest()->getParams(); unset($params['key']);
			
			$order = Mage::getModel('sales/order')->load($orderId);
			if ($order->getId() && !$order->getPushedToNs()) {
			
				// Push to NS functionality goes Here
				$result = Mage::getModel('connector/connector')->pushOrderToNS($orderId);
				if($result === true) {
					$this->_getSession()->addSuccess($this->__('The Order (%s) has been pushed to NetSuite.', $order->getIncrementId()));
				} else {
					$this->_getSession()->addError($this->__('Error (Order#  '.$order->getIncrementId(). ') : '. $result));
				}
				
			} else {
				$this->_getSession()->addError($this->__('This Order (%s) was already pushed to NetSuite', $order->getIncrementId()));
			}
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
        $this->_redirect('adminhtml/sales_order/view', $params);
	}
	
    /**
     * Push the selected order to NetSuite if not pushed
     */
	public function repushorderAction() 
	{
		$storeId = $this->getStoreId(); $websiteId = $this->getWebsiteId();
        try {
			$orderId = $this->getRequest()->getParam('order_id', array());
			$params = Mage::app()->getRequest()->getParams(); unset($params['key']);
			
			$order = Mage::getModel('sales/order')->load($orderId);
			if ($order->getId()) {
			
				// Push to NetSuite functionality goes Here
				$result = Mage::getModel('connector/connector')->pushOrderToNS($orderId);
				if($result === true) {
					$this->_getSession()->addSuccess($this->__('The Order (%s) has been re pushed to NetSuite.', $order->getIncrementId()));
				} else {
					$this->_getSession()->addError($this->__('Error (Order#  '.$order->getIncrementId(). ') : '. $result));
				}
				
			} else {
				$this->_getSession()->addError($this->__(self::ORDER_NOT_EXISTS_MSG));
			}
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
        $this->_redirect('adminhtml/sales_order/view', $params);
	}
}