<?php

class Magalter_Crossup_Model_RulesInstance extends Mage_Rule_Model_Condition_Combine
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('magalter_crossup/rulesInstance');
    }

    public function getNewChildSelectOptions()
    {
        $catalogruleConditions = Mage::getModel('magalter_crossup/conditionProduct') //Mage::getModel('catalogrule/rule_condition_product')
                ->loadAttributeOptions()->getAttributeOption();
       
        $attributes = array();
        foreach ($catalogruleConditions as $code => $label) {
            $attributes[] = array('value' => "magalter_crossup/conditionProduct|{$code}", 'label' => $label);
        }

        return array_merge_recursive(
                    parent::getNewChildSelectOptions(), 
                    array(
                        array('value' => 'magalter_crossup/conditionProduct', 'label' => Mage::helper('catalogrule')->__('Conditions Combination')),
                        array('label' => Mage::helper('catalogrule')->__('Product Attribute'), 'value' => $attributes)
                    )
        );
    }
}