<?php

class Celigo_Celigoconnectorplus_Model_Observer extends Mage_Core_Model_Abstract {
    /**
     *	Listen to order_cancel_after event
     *	Function to push the canceled order to NetSuite
     */
    const LOG_FILENAME = 'celigo-realtime-import.log';
    public function orderCancelAfter($observer) {
        if (Mage::helper('celigoconnectorplus')->hasCeligoconnectorModuleInstalled()) {
            $order = $observer->getEvent()->getOrder();
            try {
                if (Mage::helper('celigoconnector')->getIsCeligoconnectorModuleEnabled($order->getStoreId())) {
                    if ($order->getCancelledInNetsuite() !== 1) {
                        // Push to NetSuite
                        Mage::getModel('celigoconnectorplus/sales_order_cancel')->pushCancelledOrderToNS($order->getId());
                    }
                }
            }
            catch(Exception $e) {
                Mage::helper('celigoconnector/celigologger')->error( 'errormsg="'.$e->getMessage().'" event="order_cancel_after" record="order"', self::LOG_FILENAME );
            }
        }
    }
}
