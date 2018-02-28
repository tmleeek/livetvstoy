<?php

class Celigo_Celigoconnectorplus_Model_Sales_Order_Cancel extends Mage_Core_Model_Abstract {
    const ORDER_NOT_EXISTS_MSG = 'This Order does not exists';
    const ORDER_NOT_PUSHED_TO_NS = 'Order not pushed to NS';
    const PUSHTONS_DISABLED_MSG = 'Push to NetSuite functionality disabled for this store';
    const UNEXPECTED_ERROR_MSG = 'Unexpected error. Please try again';
    const POST_METHOD = 'post';
    const LOG_FILENAME = 'celigo-realtime-import.log';
    /**
     * Push the selected cancelled order to NetSuite
     */
    public function pushCancelledOrderToNS($orderId = '', $type = Celigo_Celigoconnectorplus_Helper_Async::TYPE_ASYNC, $isBatch = false) {
        if (Mage::helper('celigoconnectorplus')->hasCeligoconnectorModuleInstalled()) {
            if ($type == Celigo_Celigoconnectorplus_Helper_Async::TYPE_ASYNC) {
                if (Mage::helper('celigoconnectorplus/async')->makeAsyncOrderCancelImportCall($orderId)) {
                    
                    return true;
                }
            }
            $order = Mage::getModel('sales/order')->load($orderId);
            if (!$order->getId()) {
                
                return self::ORDER_NOT_EXISTS_MSG;
            }
            /*If the order not pushed to NS then return error message*/
            if ($order->getData('pushed_to_ns') != 1) {
                
                return self::ORDER_NOT_PUSHED_TO_NS;
            }
            try {
                $storeId = $order->getStoreId();
                /** If the Push to NetSuite setting was set to No then return false */
                if (!Mage::helper('celigoconnector')->getIsCeligoconnectorModuleEnabled($storeId)) {
                    
                    return self::PUSHTONS_DISABLED_MSG;
                }
                $postData = array();
                $postData['id'] = 1;
                $postData['jsonrpc'] = '2.0';
                $postData['method'] = 'order';
                $postData['params'] = array();
                /** Build an array of Meta data Flow ID */
                $realtimeDataFlowId = '';
                if ($isBatch) {
                    $realtimeDataFlowId = Mage::helper('celigoconnectorplus')->getBatchCancelOrderFlowId($storeId);
                } else {
                    $realtimeDataFlowId = Mage::helper('celigoconnectorplus')->getCancelOrderFlowId($storeId);
                }
                if (trim($realtimeDataFlowId) == '') 
                return 'Flow Id is blank';
                $postData['params'][] = array(
                    "realtimeDataFlowId" => $realtimeDataFlowId
                );
                $orderInfo = array();
                $orderIncrementId = $order->getIncrementId();
                $orderInfo[] = array(
                    "status" => $order->getStatus() ,
                    "increment_id" => $orderIncrementId,
                    "magentomoduleversion" => (string)Mage::helper('celigoconnector')->getExtensionVersion()
                );
                $postData['params'][] = $orderInfo;
                /** Convert the information into JSON format */
                $json = Mage::helper('core')->jsonEncode($postData);
                // Make a Rest call Here
                $result = Mage::getModel('celigoconnector/celigoconnector')->makeRestCall($json, $storeId, self::POST_METHOD);
                if ($result === true) {
                    $order->setCancelledInNetsuite(1)->save();
                    
                    return $result;
                } elseif (is_array($result)) {
                    
                    foreach ($result as $row) {
                        if ($row === true && !is_array($row)) {
                            $order->setCancelledInNetsuite(1)->save();
                            
                            return true;
                        } else {
                            
                            return $row;
                        }
                    }
                }
                Mage::helper('celigoconnector/celigologger')->error( 'errormsg="'.self::UNEXPECTED_ERROR_MSG.'"', self::LOG_FILENAME);
                
                return self::UNEXPECTED_ERROR_MSG;
            }
            catch(Exception $e) {
                Mage::helper('celigoconnector/celigologger')->error( 'errormsg="'.$e->getMessage().'"', self::LOG_FILENAME);
                return $e->getMessage();
            }
        }
    }
}
