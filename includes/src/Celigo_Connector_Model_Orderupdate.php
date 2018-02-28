<?php
/*
*	This is specially for DJN
*	Mage::getModel('connector/orderupdate')->pushOrderUpdates($orderId)
*/
class Celigo_Connector_Model_Orderupdate extends Mage_Core_Model_Abstract
{
    /**
     * Push the selected order to NetSuite if not pushed
     */
	public function pushOrderUpdates($orderId = '') 
	{
		if ($orderId == '') return;
        try {
			$order = Mage::getModel('sales/order')->load($orderId);
			$storeId = $order->getStoreId();
			if ($order->getId()) {
			
				// Push to NS functionality goes Here
				$result = Mage::getModel('connector/connector')->pushOrderToNS($orderId);
				if ($result === true) {
					Mage::getModel('adminhtml/session')->addSuccess('The Order ('.$order->getIncrementId().') updates pushed to NetSuite.');
				} elseif (is_string($result)) {
					Mage::getModel('adminhtml/session')->addError('Error (Order#  '.$order->getIncrementId(). ') : '. $result);
				}
				
			} 
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId);
        }
	}
}