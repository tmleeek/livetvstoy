<?php

/** 
 * The array params for config module
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_GetListAttributeSets extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {
    
    public function toOptionArray() {
        return Mage::getResourceModel('eav/entity_attribute_set_collection')
                        ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getEntityType()->getId())
                        ->load()
                        ->toOptionArray();
    }
    
    public function getValue($label) {
        $_attributeSets = $this->toOptionArray();
        foreach ($_attributeSets as $_attributeSet) {
            if ($_attributeSet['label'] == $label) {
                return $_attributeSet['value'];
            }
        }
        return null;
    }

    public function getLabel($value) {
        $_attributeSets = $this->toOptionArray();
        foreach ($_attributeSets as $_attributeSet) {
            if ($_attributeSet['value'] == $value) {
                return $_attributeSet['label'];
            }
        }
        return null;
    }
    
    public function getListNames() {
        $_attributeSets = $this->toOptionArray();
        $array = array();
        foreach ($_attributeSets as $_attributeSet) {
            $array[] = $_attributeSet['label'];
        }
        return $array;
    }

}