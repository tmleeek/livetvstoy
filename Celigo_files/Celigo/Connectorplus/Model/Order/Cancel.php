<?php
class Celigo_Connectorplus_Model_Order_Cancel extends Mage_Core_Model_Abstract
{
	const ORDER_NOT_EXISTS_MSG 	= 'This Order does not exists';
	const PUSHTONS_DISABLED_MSG = 'Push to NetSuite functionality disabled for this store';
	const UNEXPECTED_ERROR_MSG 	= 'Unexpected error. Please try again';
	const POST_METHOD			= 'post';
    /**
     * Push the selected order to NetSuite if not pushed
	 * Usage: Mage::getModel('connectorplus/order_cancel')->pushCancelledOrderToNS($orderId);
     */ 
	public function pushCancelledOrderToNS($orderId = '') 
	{
		if (Mage::helper('connectorplus')->hasConnectorModuleInstalled()) {
			$order = Mage::getModel('sales/order')->load($orderId);
			if (!$order->getId()) {
				return self::ORDER_NOT_EXISTS_MSG;
			}
			
			try {
			
				$storeId = $order->getStoreId();
				
				/** If the Push to NetSuite setting was set to No then return false */
				if (!Mage::helper('connector')->getIsConnectorModuleEnabled($storeId)) {
					return self::PUSHTONS_DISABLED_MSG;
				}
				
				// Make a Rest call Here
				$postData = array();
				$postData['id'] = 1;
				$postData['jsonrpc'] = '2.0';
				$postData['method'] = 'order';
				$postData['params'] = array();
				/** Build an array of Meta data Flow ID */
				$realtimeDataFlowId = Mage::helper('connectorplus')->getCancelOrderFlowId($storeId);
				if (trim($realtimeDataFlowId) == '') return;
				$postData['params'][] = array("realtimeDataFlowId" => $realtimeDataFlowId);
				
				$orderInfo = array();
				
				$orderIncrementId = $order->getIncrementId();
				$orderInfo[] = array("status" => $order->getStatus(), "increment_id" => $orderIncrementId);
				
				$postData['params'][] = $orderInfo;
				
				/** Convert the information into JSON format */
				$json = Mage::helper('core')->jsonEncode($postData);
	
				// Make a Rest call Here
				$result = Mage::getModel('connector/connector')->makeRestCall($json, $storeId, self::POST_METHOD);
				
				if ($result === true) {
					return $result;
				} elseif (is_array($result)) {
					foreach ($result as $row) {
						if ($row === true && !is_array($row)) {
							return true;
						} else {
							return $row;
						}
					}				
				}
				
				/** Start :: Code to log the json content for testing */
				/* Mage::helper('connector')->addErrorMessageToLog($json, Zend_Log::DEBUG);
				/** End :: Code to log the json content for testing */
				
				Mage::helper('connector')->addErrorMessageToLog(self::UNEXPECTED_ERROR_MSG, Zend_Log::ERR, $storeId, '');
				return self::UNEXPECTED_ERROR_MSG; //true;
				
			} catch (Exception $e) {
				Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, '');
				return $e->getMessage();
			}
		}
	}
}