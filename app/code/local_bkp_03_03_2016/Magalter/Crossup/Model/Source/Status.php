<?php 
class Magalter_Crossup_Model_Source_Status
{    
    public static function toOptionArray()
    {        
      
      return array(

         '0' => array(
               'label' => Mage::helper('magalter_crossup')->__('Enabled'),
               'value' => '1'
                ),
         '1' => array(
               'label' => Mage::helper('magalter_crossup')->__('Disabled'),
               'value' => '0'
                )        
       );           

       
    }


    public static function toFlatArray() {

        $options = self::toOptionArray();
        
        $flatOptions = array();
        
            foreach($options as $option) { 
                $flatOptions[$option['value']] = $option['label'];                 
            }
        
        return $flatOptions;
        
    }

 
}
