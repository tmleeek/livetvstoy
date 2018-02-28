<?php

class Magalter_Crossup_Model_Mysql4_Crossup_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
     
    public function _construct()
    {
        $this->_init('magalter_crossup/crossup');
    }
    
    
    public function getRelatedUpsellRule($data = array())
    {
        $this->addStoreFilter()
                ->addTimeLimitFilter()
                ->addStatusFilter()
                ->addGroupsFilter()
                ->orderByPriority();
              

        if (isset($data['limit'])) {
            $this->getSelect()->limit($data['limit']);
        }
        if (isset($data['crossup'])) {
            $this->getSelect()->where('main_table.upsell_id IN(?)', $data['crossup']);
        }
      
        return $this;
    }
    
    public function addCookieFilter()
    {
        $cookie = Mage::getModel('core/cookie')->get('magalter-upsell-rule');
        
        if ($cookie) {
            $cookie = explode(',', $cookie);
            $cookie = array_unique($cookie);
            $scope = array();
            foreach ($cookie as $cook) {
                if (!(int) $cook) {
                    continue;
                }
                $scope[] = $cook;
            }
            if (empty($scope)) {
                return $this;
            }
            $this->getSelect()->where('not(main_table.upsell_id IN(?) AND main_table.once = 1)', $scope);
        }

        return $this;
    }
    
    public function addStoreFilter($storeId = null) {
        
        if(null === $storeId) {            
            $storeId = Mage::app()->getStore()->getId();
        }
        
         $this->getSelect()->where("stores = 0 OR FIND_IN_SET(?, stores)", $storeId);
        
         return $this;
    }
    
    public function orderByPriority() {
        
         $this->getSelect()->order('main_table.priority DESC');
        
         return $this;
    }
    
    public function addTimeLimitFilter() { 
 
        $this->getSelect()->where("if(main_table.available_to is null, true, main_table.available_to > UTC_TIMESTAMP()) AND if(main_table.available_from is null, true, main_table.available_from < UTC_TIMESTAMP())");
        
        return $this;
    }
    
    public function addStatusFilter() {
        
        $this->getSelect()->where('main_table.status = 1');   
        
        return $this;
        
    }
    
    public function addGroupsFilter($exclude = null) {
        
       if(null === $exclude) {
           $exclude = Magalter_Crossup_Helper_Data::getCustomerGroup();           
       }
        
       $this->getSelect()->where("`groups` IS NULL OR `groups` NOT REGEXP '(^|,){$exclude}(,|$)'");
        
       return $this;
    }
  
}
