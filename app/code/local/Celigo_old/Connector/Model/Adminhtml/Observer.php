<?php
class Celigo_Connector_Model_Adminhtml_Observer extends Mage_Adminhtml_Block_Widget_Form_Container //extends Mage_Core_Model_Abstract
{
    protected $_allowResend = false;

	/**
	 * Customer save after send the customer data to RESt URL
	 * Module: admin --- Controller: customer --- Action: massSubscribe
	 * @param Varien_Event_Observer $observer
	 */
	public function addMassAction($observer)
    {
		$storeId = ''; $websiteId = '';
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		if (isset($params['store']) && $params['store'] != '' && $params['store'] != 'undefined') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		
		if (isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}
	
        $block = $observer->getEvent()->getBlock();
		// Order Gris Mass Action
        if (get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && ($block->getRequest()->getControllerName() == 'sales_order' || $block->getRequest()->getControllerName() == 'adminhtml_sales_order'))
        {
			/** If the Push to NetSuite setting was set to No then return false */
			if (!Mage::helper('connector')->getIsConnectorModuleEnabled($storeId)) {
				return;
			}
			/** Get all the order statuses from setting that need to be synced to NetSuite */
			$status = Mage::helper('connector')->getOrderStatusArray($storeId);
			if (count($status) == 0) {
				return;
			}

			$params = Mage::app()->getRequest()->getParams();
            $block->addItem('connector', array(
                'label' => 'Push to NetSuite',
                'url' => Mage::app()->getStore()->getUrl('connector/adminhtml_order/mass', $params),
            ));
        }
		
		// Order View Page button
        if ((get_class($block) =='Mage_Adminhtml_Block_Sales_Order_View' || in_array(get_class($block), Mage::helper('connector/extensionconflict')->getConflictListByCalssName('adminhtml/sales_order_view')))
            && ($block->getRequest()->getControllerName() == 'sales_order' || $block->getRequest()->getControllerName() == 'adminhtml_sales_order'))
        {
			$params = Mage::app()->getRequest()->getParams();
			unset($params['key']);
			if (isset($params['order_id']) && $params['order_id'] != '') {
			
				$configUrl = $this->getUrl('connector/adminhtml_order/pushorder', $params);
				$order = Mage::getModel('sales/order')->load($params['order_id']);
				
				$storeId = $order->getStoreId();
				/** If the Push to NetSuite setting was set to No then return false */
				if (!Mage::helper('connector')->getIsConnectorModuleEnabled($storeId)) {
					return;
				}
				/** Get all the order statuses from setting that need to be synced to NetSuite */
				$status = Mage::helper('connector')->getOrderStatusArray($storeId);
				if (count($status) == 0) {
					return;
				}
				
				/** Check if the current order status is allowed to be synced to NetSuite */
				if (in_array($order->getStatus(), $status)) {

					if ($order->getPushedToNs() == 0) {
					
						$block->addButton('pushtons', array(
							'label'     => 'Push to NetSuite',
							'onclick'   => 'setLocation(\'' . $configUrl . '\')',
							'class'     => 'go'
						));
					
					} elseif ($this->_allowResend) {
						$configUrl = $this->getUrl('connector/adminhtml_order/repushorder', $params);
						
						$block->addButton('repushtons', array(
							'label'     => 'Re Push to NetSuite',
							'onclick'   => 'setLocation(\'' . $configUrl . '\')',
							'class'     => 'go'
						));
					}
					
				} //if(in_array($order->getStatus(), $status)) {
			}
			
        }
    }	
}