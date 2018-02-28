<?php
class Celigo_Connectorplus_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_CANCEL_ORDER_FLOW_ID = 'connector/othersettings/ordercancelflowid';
	
    /**
     * @return Boolean
	 * Usage: Mage::helper('connectorplus')->hasConnectorModuleInstalled()
     */
    public function hasConnectorModuleInstalled()
    {
		$modules = (array)Mage::getConfig()->getNode('modules')->children();
		if (isset($modules['Celigo_Connector']) && $modules['Celigo_Connector']->is('active')) {
			return true;
		}
		return false;
    }
	
    /**
     * @return Cancel Order Flow ID
     */
    public function getCancelOrderFlowId($storeId = '', $websiteId = '')
    {
		if (Mage::helper('connectorplus')->hasConnectorModuleInstalled()) {
			return Mage::helper('connector')->getConfigValue(self::XML_PATH_CANCEL_ORDER_FLOW_ID, $storeId, $websiteId);
		}
		return '';
    }
}