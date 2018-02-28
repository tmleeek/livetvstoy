<?php

class Magalter_Crossup_Model_Rules_Conditions extends Mage_SalesRule_Model_Rule_Condition_Combine
{    
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        
        foreach($conditions as $key => &$condition) {            
            if(is_array($condition) && isset($condition['value']) && $condition['value'] == 'salesrule/rule_condition_product_found') {                
                $conditions[$key]['value'] = 'magalter_crossup/rules_conditions_combine';                
            }          
        }        
       return $conditions;       
    }
}