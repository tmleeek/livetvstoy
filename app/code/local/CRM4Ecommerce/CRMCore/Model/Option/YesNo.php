<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_YesNo extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    const YES = 1;
    const NO = 0;

    public function toOptionArray() {
        return array(
            self::YES => Mage::helper('core')->__('Yes'),
            self::NO => Mage::helper('core')->__('No')
        );
    }

}