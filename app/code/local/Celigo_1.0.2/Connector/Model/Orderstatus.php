<?php
class Celigo_Connector_Model_Orderstatus extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{
	const STATE_PENDING_FULFILLMENT = "pending_fulfillment";
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
		$statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        $options = array(); 
		$excludeArray = array(
			Mage_Sales_Model_Order::STATE_CLOSED,
			Mage_Sales_Model_Order::STATE_CANCELED, 
			Mage_Sales_Model_Order::STATE_HOLDED,
			Mage_Sales_Model_Order::STATE_COMPLETE,
			self::STATE_PENDING_FULFILLMENT
		);
        foreach ($statuses as $code=>$label) {
			if (in_array($code, $excludeArray)) continue;
            $options[] = array(
               'value' => $code,
               'label' => $label
            );
        }
		return $options;
    }

}
