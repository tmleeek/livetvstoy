<?php 
class Magalter_Lib_Model_Source_Effect
{     
    public function toOptionArray()
    {   
      
     $options = array(
         
         1 => array(
               'label' => Mage::helper('magalter_lib')->__('Fade in / Fade out'),
               'value' => 1,               
              ),
         2 => array(
               'label' => Mage::helper('magalter_lib')->__('Slide up / Slide down'),
               'value' => 2,              
                ),
         3 => array(
               'label' => Mage::helper('magalter_lib')->__('Blind up / Blind down'),
               'value' => 3,              
             ),
         
         4 => array( 
               'label' => Mage::helper('magalter_lib')->__('Grow / Shrink'),
               'value' => 4,          
             ),
        
          );
 
        return $options;
    }

 
    
}
