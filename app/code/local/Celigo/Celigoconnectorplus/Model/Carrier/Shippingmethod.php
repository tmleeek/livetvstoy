<?php

class Celigo_Celigoconnectorplus_Model_Carrier_Shippingmethod extends Mage_Shipping_Model_Carrier_Abstract {
    protected $_code = 'customshipping';
    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        if (!Mage::getStoreConfig('carriers/' . $this->_code . '/active')) {
            
            return false;
        }
        $result = false;
        $customPrice = Mage::getModel('core/session')->getCustomShippingPrice();
        if (isset($customPrice) && $customPrice != '' && $customPrice >= 0) {
            $result = Mage::getModel('shipping/rate_result');
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setMethod($this->_code);
            $method->setPrice($customPrice);
            $customTitle = Mage::getModel('core/session')->getCustomShippingTitle();
            if (isset($customTitle) && $customTitle != '') {
                $method->setCarrierTitle($customTitle);
            } else {
                $method->setCarrierTitle(Mage::getStoreConfig('carriers/' . $this->_code . '/title'));
                $method->setMethodTitle('NetSuite Order');
            }
            $result->append($method);
        }
        Mage::getModel('core/session')->getCustomShippingPrice();
        
        return $result;
    }
}
