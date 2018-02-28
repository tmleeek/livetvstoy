<?php

class Magalter_Crossup_Model_Crossup extends Mage_Rule_Model_Rule {
   
   const DISCOUNT_TYPE_FIXED = 1;
   const DISCOUNT_TYPE_PERCENT = 2;

   protected function _construct()
   {
      parent::_construct();
      $this->_init('magalter_crossup/crossup');
   }
   
   public function getRuleActions()
   {
        return Mage::getResourceModel('magalter_crossup/ruleactions');
   }

   public function getUpsellRule()
   {     
       $upsellRules = $this->getCollection()->getRelatedUpsellRule();       
       $quoteItems = $this->getQuoteItems();
       $ruleActions = $this->getRuleActions();
       
       $rulesScope = array();
       
       foreach($quoteItems as $item) { 
         $item->load($item->getId());              
       }
       
       $store = Mage::app()->getStore();
       foreach($upsellRules as $rule) {
           $rule->load($rule->getId());
           foreach($quoteItems as $item) { 
               if($rule->validate($this->getAddress($item))) {                  
                   $actions = $ruleActions->getActionProducts($rule->getId(), $store->getId());                   
                   if(!empty($actions)) {                    
                       $rule->getResource()->unserializeFields($rule);         
                       $rule->afterLoad();  
                       $rule->setRelatedProductIds(explode(',', $actions[0]));                      
                       $rulesScope[$rule->getId()] = $rule;
                   }               
               }
           }         
       }
       
      return $rulesScope;
   }
   
   public function getQuoteItems()
   {
       return Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
   }
   
    public function getAddress(Mage_Sales_Model_Quote_Item_Abstract $item)
    {
        if ($item instanceof Mage_Sales_Model_Quote_Address_Item) {
            $address = $item->getAddress();
        } elseif ($item->getQuote()->getItemVirtualQty() > 0) {
            $address = $item->getQuote()->getBillingAddress();
        } else {
            $address = $item->getQuote()->getShippingAddress();
        }
        return $address;
    }

   public function getUpsellsCollection($items, $data = array())
   {       
      return $this->getResource()->getRelatedUpsells($this, $items, $data);
   }
   
    public function getConditionsInstance()
    {
        return Mage::getModel('magalter_crossup/rules_conditions'); //Mage::getModel('salesrule/rule_condition_combine');
    }  
    
    public function getActionsInstance()
    {
        return Mage::getModel('magalter_crossup/rulesInstance');
    }
    
    public function loadPost(array $data)
    {
        $arr = $this->_convertFlatToRecursive($data);
        if (isset($arr['conditions'])) {
            $this->getConditions()->setConditions(array())->loadArray($arr['conditions'][1]);
        }
        if (isset($arr['actions'])) {
            $this->getActions()->setActions(array())->loadArray($arr['actions'][1], 'actions');
        }

        return $this;
    }

}