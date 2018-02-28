<?php

/**
 * Action Status
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
abstract class CRM4Ecommerce_CRMCore_Model_Option_Abstract {
    
    abstract public function toOptionArray();
    
    public function toFilterArray() {
        return $this->toOptionArray();
    }
    
    public function getDataType($type_code) {
        $data = $this->toFilterArray();
        return $data[$type_code];
    }

}