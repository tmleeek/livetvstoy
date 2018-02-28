<?php
class Celigo_Connector_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
		
		$mageObj = new Mage();
		if (method_exists($mageObj, 'getEdition')) {
			$edition = Mage::getEdition();
			if($edition == Mage::EDITION_ENTERPRISE) {
				// Append new mass action option
				$storeId = ''; $websiteId = ''; $isMassActionAllowed = true;
				$params = Mage::app()->getRequest()->getParams();
				unset($params['key']);
				
				if (isset($params['store']) && $params['store'] != '') {
					$storeId = Mage::app()->getStore($params['store'])->getId();
				}
				
				if (isset($params['website']) && $params['website'] != '') {
					$websiteId = Mage::app()->getWebsite($params['website'])->getId();
					$websiteId = $params['website'];
				}
			
				/** If the Push to NetSuite setting was set to No then return false */
				if (!Mage::helper('connector')->getIsConnectorModuleEnabled($storeId)) {
					$isMassActionAllowed = false;
				}
				/** Get all the order statuses from setting that need to be synced to NetSuite */
				$status = Mage::helper('connector')->getOrderStatusArray($storeId);
				if (count($status) == 0) {
					$isMassActionAllowed = false;
				}
				
				if ($isMassActionAllowed) {
					$this->getMassactionBlock()->addItem(
						'connector',
						array('label' => $this->__('Push to NetSuite'),
							  'url'   => Mage::app()->getStore()->getUrl('connector/adminhtml_order/mass', $params)
						)
					);
				}
			}
		} 
    }
}