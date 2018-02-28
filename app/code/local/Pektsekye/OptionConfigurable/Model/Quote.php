<?php

class Pektsekye_OptionConfigurable_Model_Quote extends Mage_Sales_Model_Quote
{
    
   
    public function addProductAdvanced(Mage_Catalog_Model_Product $product, $request = null, $processMode = null)
    {
		  if ($product->getRequiredOptions() && $request != null && (isset($request['options']) || isset($request['super_attribute']))){		
		 
        $requestOptions = array('a' => array(), 'o' => array());
        $hasSelected = false;
                
        if (isset($request['options'])){
          foreach ($request['options'] as $v){
            if (!empty($v)){
              $hasSelected = true;
              break;
            }                 	  
          }        
          $requestOptions['o'] = $request['options'];          
        }
        
        if (isset($request['super_attribute'])){
          $hasSelected = true;                 
          $requestOptions['a'] = $request['super_attribute'];                     
        }
        
        if ($hasSelected) {	        
          $productModel = Mage::getModel('catalog/product')->load($product->getId());
          $hiddenOIds = Mage::helper('optionconfigurable')->getHiddenOptions($productModel, $requestOptions); 
 
          foreach ($product->getOptions() as $option){
            if (isset($hiddenOIds[$option->getId()])) 
              $option->setIsRequire(false);		  
          }
				}  
		  }
		  
		  return parent::addProductAdvanced($product, $request, $processMode);    
    }
}
