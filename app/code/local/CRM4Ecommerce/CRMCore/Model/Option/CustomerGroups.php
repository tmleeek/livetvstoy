<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_CustomerGroups extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    public function toOptionArray() {
        $groups = Mage::getModel('customer/group')->getCollection()
                ->setOrder('customer_group_code', 'asc');
        $_groups = array();
        $_groups[] = array(
            'value' => '',
            'label' => Mage::helper('crmcore')->__('-- None of All --')
        );
        foreach ($groups as $group) {
            $_group = Mage::getModel('customer/group')->load($group->getId());
            $_groups[] = array(
                'value' => $_group->getId(),
                'label' => $_group->getCode()
            );
        }
        return $_groups;
    }

}