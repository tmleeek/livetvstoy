<?php

class Magalter_Crossup_Block_Products extends  Mage_Core_Block_Template { 

   protected $_upsellRules;
   
   protected $_helper;
   
   protected $_parser; 
   
   protected $_outputHelper;
    
   protected function _construct() {
      
       $this->_helper = Mage::helper('magalter_crossup');
       
       $this->_outputHelper = Mage::getBlockSingleton('catalog/product_list');
        
   }
   
   protected function _beforeToHtml() {
      
       return $this;
   }
   
   public function getRelatedUpsells()
   {      
      $rules = $this->getRules();
       
      if(empty($rules)) { 
          return new Varien_Data_Collection();
      }
     
      $productsByRule = $discountProducts = array();     
      foreach ($rules as $rule) {
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                    ->addFieldToFilter('entity_id', array('in' => $rule->getRelatedProductIds()))
                    ->addMinimalPrice()->addFinalPrice()->addTaxPercents()->addUrlRewrite()
                    ->addAttributeToSelect('description');
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);          
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            $limit = $this->getRuleUpsellsLimit($rule);

            if ($limit) {
                $collection->getSelect()->limit($limit);
            }
            $sort = $this->getRuleUpsellsSort($rule);
            if ($sort) {
                $collection->getSelect()->order($sort);
            }

            $this->preparePrices($collection, $rule);
            
            $productsByRule[$rule->getId()] = $collection;
             
            $discounters = array();            
            foreach($collection as $item) {
                array_push($discounters, $item->getId());
            }
            
            $discountProducts[$rule->getId()] = $discounters;
            
        } 
        
       Mage::getSingleton('customer/session')->setMagalterUpsellRules($discountProducts);
      
       uksort($productsByRule, array($this, 'sortRules'));
     
      return $productsByRule;
   }
   
   public function sortRules($a, $b)
   {
        $aRule = $this->getRuleByKey($a);
        $bRule = $this->getRuleByKey($b);

        if ($aRule->getPriority() > $bRule->getPriority()) {
            return 1;
        } elseif ($aRule->getPriority() < $bRule->getPriority()) {
            return -1;
        }

        return 0;
    }
   
   public function getRuleByKey($key)
    {
        if (isset($this->_upsellRules[$key])) {
            return $this->_upsellRules[$key];
        }
    }
   
   
   protected function _toHtml() {
       
       if(Mage::helper('magalter_crossup')->isEnabled()) {          
           return parent::_toHtml();
       }
       
       return null;       
   }
   
   public function getImage($_product, $w = 70, $h = 70)
   {
        $helper = $this->_outputHelper;

        return
                "<a href='{$_product->getProductUrl()}' title='{$this->stripTags($helper->getImageLabel($_product, 'small_image'), null, true)}' class='product-image magalter-crossup-image'>
             <img src='{$this->helper('catalog/image')->init($_product, 'small_image')->resize($w, $h)}' width='{$w}' height='{$h}' alt='{$this->stripTags($helper->getImageLabel($_product, 'small_image'), null, true)}' />
       </a>";
   }
     
   public function getImageLabel($product=null, $mediaAttributeCode='image') {
         
        $label = $product->getData($mediaAttributeCode . '_label');
        if (empty($label)) {
            $label = $product->getName();
        }

        return $label;
    }
    
    
    public function getPatterns() {
        
        $patterns = @unserialize(Mage::getStoreConfig('magalter_crossup/configuration/patterns'));
        
        if(empty($patterns)) {            
            $patterns = array(
               array('type' => 'button', 'info' => 'checkout/onepage'),
               array('type' => 'a', 'info' => 'checkout/multishipping')                
            );            
            return json_encode($patterns);
        }        
        $data = array();
        foreach($patterns as $value) {
            $data[] = array('type' => $value['type'], 'info' => $value['info']);
        }
       
        return json_encode($data);        
    }
 

   public function getCategoryHelper()
   {
      return Mage::getBlockSingleton('catalog/product_list');
   }
 
   public function getQuote()
   {
      if (null === $this->_quote) {       
         $this->_quote = Mage::getSingleton('checkout/session')->getQuote();
      }
      return $this->_quote;
   }

   public function getRuleUpsellsLimit($rule)
   {
      $additional = $rule->getAdditionalSettings();

      return (int) @$additional[0];
   }

   public function getRuleUpsellsSort($rule)
   {
      $additional = $rule->getAdditionalSettings();
     
      $sortBy = Magalter_Crossup_Model_Source_Sortorder::toOptionArray(isset($additional[1]) ? $additional[1] : 1);

      $direction = Magalter_Crossup_Model_Source_Sortdir::toOptionArray(isset($additional[2]) ? $additional[2] : 1);
      
     
      if($direction == 'RAND') {
          return "{$direction}()";
      }

      return "{$sortBy} {$direction}";
   }

   public function addRequiredOptionsFilter($collection)
   {
      $additional = $this->getRule()->getAdditionalSettings();
      
      if(!isset($additional[3])) {
         return $this;       
      }
      if($additional[3] == 2) {
          $collection->getSelect()->where('required_options = 0');
      }      
      return $this;
   }

   public function preparePrices($collection, $rule)
   {      
      $discountType = $rule->getDiscountType();

      foreach ($collection as $product) {  
        $product->setCheckoutSet(true);
        $discountType = $rule->getDiscountType();

        if ($discountType == Magalter_Crossup_Model_Crossup::DISCOUNT_TYPE_PERCENT) {
            $product->setFinalPrice($product->getFinalPrice() - ($product->getFinalPrice() * $rule->getDiscount()) / 100);
        } else {
            $product->setFinalPrice($product->getFinalPrice() - $rule->getDiscount());
        }
      }

      return $collection;
   }
   
   /**
    * @return array
    */
   public function getRules()
   {
      if (!$this->_upsellRules) {
         $items = $this->getQuoteProducts();
         if (empty($items)) {
             return array();
         }
       
         $this->_upsellRules = Mage::getModel('magalter_crossup/crossup')->getUpsellRule($items);    
          
          foreach($this->_upsellRules as $rule) {
              $rule->setRelatedProductIds(array_diff($rule->getRelatedProductIds(), $this->getQuoteProducts()));        
          }        
      }
     
      return $this->_upsellRules;
   }
   
   public function getPriceHtml($product)
   {
       return $this->_outputHelper->getPriceHtml($product, true);
   }

    public function getQuoteProducts()
   {
      $items = array();
      foreach ($this->getQuote()->getAllVisibleItems() as $item) {
         $items[] = $item->getProductId();
      }
       
      if (empty($items)) {
         return array();
      }

      return $items;
   }
 
   public function getPostAction() {
       
       return Mage::getUrl('magalter_crossup/cart/addSelected');

   }

}

