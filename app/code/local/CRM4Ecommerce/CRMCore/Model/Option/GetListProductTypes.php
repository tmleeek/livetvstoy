<?php

/**
 * The array params for config module
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_GetListProductTypes extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {
    public function toOptionArray() {
        $_productTypes = Mage::getModel('catalog/product_type')->getOptionArray();
        $productTypes = array();
        foreach ($_productTypes as $type_k => $type_v) {
            $productTypes[] = array(
                'value' => $type_k,
                'label' => $type_v
            );
        }
        return $productTypes;
    }
    
    public function getValue($label) {
        $_productTypes = Mage::getModel('catalog/product_type')->getOptionArray();
        foreach ($_productTypes as $type_k => $type_v) {
            if ($type_v == $label) {
                return $type_k;
            }
        }
        return null;
    }
    
    public function getLabel($value) {
        $_productTypes = Mage::getModel('catalog/product_type')->getOptionArray();
        foreach ($_productTypes as $type_k => $type_v) {
            if ($type_k == $value) {
                return $type_v;
            }
        }
        return null;
    }
    
    public function getListNames() {
        $_productTypes = Mage::getModel('catalog/product_type')->getOptionArray();
        $array = array();
        foreach ($_productTypes as $type_k => $type_v) {
            $array[] = $type_v;
        }
        return $array;
    }

}