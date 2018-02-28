<?php 
class Magalter_Lib_Model_Source_Interactive
{     
    public function toOptionArray()
    {   
      
     $options = array(
         
         1 => array(
               'label' => Mage::helper('magalter_lib')->__('Mouseover / Mouseout'),
               'value' => 1,               
              ),
         2 => array(
               'label' => Mage::helper('magalter_lib')->__('Click'),
               'value' => 2,              
                )       
          );
 
        return $options;
    }

 
    
}
