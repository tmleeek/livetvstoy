<?php

/**
 * The array params for config module
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_GetListStoreViews extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {
    public function toOptionArray() {
        $collection = Mage::getModel('core/store')
                        ->getCollection();

        $array = array();
        foreach ($collection as $store) {
            $array[$store->getId()] = $store->getName() . ' (' . $store->getCode() . ')';
        }
        return $array;
    }
    
    public function getListNames() {
        $collection = Mage::getModel('core/store')
                        ->getCollection();

        $array = array();
        foreach ($collection as $store) {
            $array[] = $store->getName();
        }
        return $array;
    }

}