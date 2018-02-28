<?php

class Celigo_Celigoconnectorplus_Helper_Data extends Mage_Core_Helper_Abstract {
    const XML_PATH_CANCEL_ORDER_FLOW_ID = 'celigoconnector/othersettings/ordercancelflowid';
    const XML_PATH_BATCH_ORDER_CANCEL_FLOW_ID = 'celigoconnector/othersettings/batchordercancelflowid';
    /**
     * @return Boolean
     * Usage: Mage::helper('celigoconnectorplus')->hasCeligoconnectorModuleInstalled()
     */
    public function hasCeligoconnectorModuleInstalled() {
        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        if (isset($modules['Celigo_Celigoconnector']) && $modules['Celigo_Celigoconnector']->is('active')) {
            
            return true;
        }
        
        return false;
    }
    /**
     * @return Cancel Order Flow ID
     */
    public function getCancelOrderFlowId($storeId = '', $websiteId = '') {
        if (Mage::helper('celigoconnectorplus')->hasCeligoconnectorModuleInstalled()) {
            
            return Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_CANCEL_ORDER_FLOW_ID, $storeId, $websiteId);
        }
        
        return '';
    }
    /**
     * @return Batch Cancel Order Flow ID
     */
    public function getBatchCancelOrderFlowId($storeId = '', $websiteId = '') {
        if (Mage::helper('celigoconnectorplus')->hasCeligoconnectorModuleInstalled()) {
            
            return Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_BATCH_ORDER_CANCEL_FLOW_ID, $storeId, $websiteId);
        }
        
        return '';
    }
}
