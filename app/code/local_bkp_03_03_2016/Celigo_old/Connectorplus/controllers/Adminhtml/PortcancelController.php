<?php
/**
 * Controller Class to push the canceled orders to Netsuite
 */
class Celigo_Connectorplus_Adminhtml_PortcancelController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction() {
		$this->_redirect('adminhtml/sales_order');
	}
	
	/**
	 * Get current store ID
	 */
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
     * Push the selected order to NetSuite if not pushed
     */
	public function pushorderAction() 
	{
		if (Mage::helper('connectorplus')->hasConnectorModuleInstalled()) {
			if (Mage::helper('connector')->getIsConnectorModuleEnabled($this->getStoreId())) {
			
				$storeId = $this->getStoreId(); $websiteId = $this->getWebsiteId();
				try {
					$orderId = $this->getRequest()->getParam('order_id', array());
					$params = Mage::app()->getRequest()->getParams(); unset($params['key']);
					
					$order = Mage::getModel('sales/order')->load($orderId);
					
					$itemCanceled = false;
					$items = $order->getAllItems();
					foreach ($items as $itemId => $item) {
						if ($item->getQtyCanceled() > 0) {
							$itemCanceled = true;
							break;
						}
					}					
					
					if ($order->getId()) {
						if (($order->getStatus() == Mage_Sales_Model_Order::STATE_CANCELED 
							|| $itemCanceled) 
							&& !$order->getCancelledInNetsuite()) {
						
							$result = Mage::getModel('connectorplus/order_cancel')->pushCancelledOrderToNS($order->getId());
							if ($result === true) {
								$order->setCancelledInNetsuite(1)->save();
								$this->_getSession()->addSuccess($this->__('The Order (%s) has been Canceled in NetSuite.', $order->getIncrementId()));
							} else {
								$this->_getSession()->addError($this->__('Error (Order#  '.$order->getIncrementId(). ') : '. $result));
							}
							
						} else {
							$this->_getSession()->addError($this->__('This Order (%s) was already Canceled in NetSuite', $order->getIncrementId()));
						}
					}
				} catch (Exception $e) {
					Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
				}

				$this->_redirect('adminhtml/sales_order/view', $params);

			} else {
				$this->_redirect('adminhtml/sales_order');
			}
		}
		$this->_redirect('adminhtml/sales_order');
	}
}