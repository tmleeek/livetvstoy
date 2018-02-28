<?php
 /**
  */
class SFC_Kount_Model_Source_PaymentMethods
{

    public function toOptionArray()
    {
        $allAvailablePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
        $config = Mage::getConfig()->loadModulesConfiguration('system.xml')->applyExtends();
        
        $options = array(
            array(
                'value' => '',
                'label' => 'none'
            )
        );

        foreach($allAvailablePaymentMethods as $code => $method) {
            $options[] = array(
                'value' => $code,
                'label' => $code
            );  
        }
        
        return $options;
    }
    
}
