<?php
class Celigo_Connector_Model_Sales_Order extends Mage_Sales_Model_Order
{
    /**
     * Order state setter.
     * If status is specified, will add order status history with specified comment
     * the setData() cannot be overriden because of compatibility issues with resource model
     *
     * @param string $state
     * @param string|bool $status
     * @param string $comment
     * @param bool $isCustomerNotified
     * @return Mage_Sales_Model_Order
     */
	 
    public function orderSaveBefore($observer)
    {
		$order = $observer->getEvent()->getOrder();
		
		$orderStatuses[$order->getId()] = $order->getStatus();
		Mage::getModel('core/session')->setCeligoConnectorOrderInfo($orderStatuses);
    }
	 
    public function orderSaveAfter($observer)
    {
		$order = $observer->getEvent()->getOrder();
		$orderStatuses = Mage::getModel('core/session')->getCeligoConnectorOrderInfo();
		if (isset($orderStatuses[$order->getId()]) && is_array($orderStatuses) && $orderStatuses[$order->getId()] != $order->getStatus()) {
			
			/** Listen Order Status Change event */
			$statuses = Mage::helper('connector')->getOrderStatusArray($order->getStoreId());
			if (Mage::helper('connector')->getIsConnectorModuleEnabled($order->getStoreId())
					&& count($statuses) > 0 && in_array($order->getStatus(), $statuses) && $order->getPushedToNs() !== 1) { 
					
					// Push to NetSuite
					$result = Mage::getModel('connector/connector')->pushOrderToNS($order->getId());
			}
			Mage::getModel('core/session')->setCeligoConnectorOrderInfo('');
		}
    }
}