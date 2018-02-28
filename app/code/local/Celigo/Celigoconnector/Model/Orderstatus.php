<?php

class Celigo_Celigoconnector_Model_Orderstatus extends Mage_Adminhtml_Model_System_Config_Source_Order_Status {
    const STATE_PENDING_FULFILLMENT = "pending_fulfillment";
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        $options = array();
        $excludeArray = array(
            self::STATE_PENDING_FULFILLMENT
        );
        
        foreach ($statuses as $code => $label) {
            if (in_array($code, $excludeArray)) continue;
            $options[] = array(
                'value' => $code,
                'label' => $label
            );
        }
        
        return $options;
    }
}
