<?php

class Celigo_Celigoconnector_Model_Sales_Order_Api extends Mage_Sales_Model_Order_Api
//Mage_Api_Model_Resource_Abstract
{
    /**
     * Initialize basic order model
     *
     * @param mixed $orderIncrementId
     * @return Mage_Sales_Model_Order
     */
    const LOG_FILENAME = 'celigo-connector-api.log';
    protected function _initOrder($orderIncrementId) {
        $order = Mage::getModel('sales/order');
        /* @var $order Mage_Sales_Model_Order */
        $order->loadByIncrementId($orderIncrementId);
        if (!$order->getId()) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="No order found with increment id "'.$orderIncrementId.'", record="order" api="_initOrder"', self::LOG_FILENAME);
            $this->_fault('not_exists', 'errormsg="No order found with increment id "'.$orderIncrementId.'"');
        }
        
        return $order;
    }
    /**
     * Update Order information
     *
     * @param string $orderIncrementId
     * @param array order data
     * @return string
     */
    public function update($orderIncrementId, $data) {
        if (!is_array($data)) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="Order data must be an array." recordid="'.$orderIncrementId.'", record="order" api="order.update"', self::LOG_FILENAME);
            $this->_fault('order_data_invalid', 'Order data must be an array.');
        }
        $notAllowedAttributes = array(
            'grand_total',
        );
        $order = $this->_initOrder($orderIncrementId);
        try {
            $isCancelCallBack = false;
            
            foreach ($data as $key => $value) {
                if (strtolower(trim($key)) == "operationtype" && strtolower(trim($value)) == "ordercancellation") {
                    $isCancelCallBack = true;
                    unset($data[$key]);
                } elseif (in_array($key, $notAllowedAttributes, true)) {
                    unset($data[$key]);
                } else {
                    $order->setData($key, $value);
                }
            }
            $pushedOrderStatus = Mage::helper('celigoconnector')->getPushedOrderStatus($order->getStoreId());
            if (trim($pushedOrderStatus) !== ""
                    && $isCancelCallBack === false
                    && Mage_Sales_Model_Order::STATE_COMPLETE !== $order->getStatus()
                    && Mage_Sales_Model_Order::STATE_CANCELED !== $order->getStatus()
                    && Mage_Sales_Model_Order::STATE_HOLDED !== $order->getStatus()
                    && Mage_Sales_Model_Order::STATE_CLOSED !== $order->getStatus()) {
                $order->addStatusToHistory($pushedOrderStatus, "", false);
            } elseif (count($data) == 0) {
                throw new Exception('Order data should not blank.');
            }
            $order->save();
        }
        catch(Mage_Core_Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$e->getMessage().'", record="order" api="order.update"', self::LOG_FILENAME);
            $this->_fault('order_data_invalid', $e->getMessage());
        }
        
        return true;
    }
}
?>