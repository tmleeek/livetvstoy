<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const STATUS_EXPIRED = -1;

    public function toOptionArray() {
        return array(
            self::STATUS_ENABLED => Mage::helper('crmcore')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('crmcore')->__('Disabled'),
            self::STATUS_EXPIRED => Mage::helper('crmcore')->__('Expired')
        );
    }

}