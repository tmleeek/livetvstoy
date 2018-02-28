<?php

class Celigo_Celigoconnector_Model_System_Config_Source_Paymentmethods extends Mage_Core_Model_Abstract {
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $storeId = '';
        $params = Mage::app()->getRequest()->getParams();
        if (isset($params['store']) && $params['store'] != '') {
            $storeId = Mage::app()->getStore($params['store'])->getId();
        }
        $payments = Mage::getSingleton('payment/config')->getActiveMethods($storeId);
        $options = array();
        $options[] = array(
            'value' => '',
            'label' => 'None'
        );
        if (count($payments) > 0) {
            
            foreach ($payments as $paymentCode => $paymentModel) {
                //if ($paymentCode != "free" && $paymentModel->canAuthorize()) {
                $paymentTitle = Mage::getStoreConfig('payment/' . $paymentCode . '/title');
                $options[] = array(
                    'value' => $paymentCode,
                    'label' => $paymentTitle
                );
                //}
                
            }
        }
        
        return $options;
    }
}
