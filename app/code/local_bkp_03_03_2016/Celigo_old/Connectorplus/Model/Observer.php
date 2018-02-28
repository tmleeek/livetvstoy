<?php
class Celigo_Connectorplus_Model_Observer extends Mage_Core_Model_Abstract
{
	/**
	 *	Listen to order_cancel_after event
	 *	Function to push the canceled order to NetSuite
	 */
    public function orderCancelAfter($observer)
    {
		if (Mage::helper('connectorplus')->hasConnectorModuleInstalled()) {
			$order = $observer->getEvent()->getOrder();
			try {
				if (Mage::helper('connector')->getIsConnectorModuleEnabled($order->getStoreId())) {
					if ($order->getCancelledInNetsuite() !== 1) {
						// Push to NetSuite
						$result = Mage::getModel('connectorplus/order_cancel')->pushCancelledOrderToNS($order->getId());
						if ($result === true) {
							$order->setCancelledInNetsuite(1)->save();
							Mage::getModel('adminhtml/session')->addSuccess('Canceled Order('.$order->getIncrementId().') has been pushed to NetSuite.');
						} elseif (is_array($result)) {
							$counting = 0;
							foreach ($result as $row) {
								if ($row === true && !is_array($row)) {
									$order->setCancelledInNetsuite(1)->save();
									Mage::getModel('adminhtml/session')->addSuccess('Canceled Order('.$order->getIncrementId().') has been pushed to NetSuite.');
								} else {
									Mage::getModel('adminhtml/session')->addError('Unable to push the canceled Order('.$order->getIncrementId().') to NetSuite. '. $row);
								}
								$counting++;				
							}
						}
					}		
				}
			} catch (Exception $e) {
				Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, '');
			}
		}
    }
}