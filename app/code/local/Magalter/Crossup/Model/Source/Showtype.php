<?php 
class Magalter_Crossup_Model_Source_Showtype
{    
    public static function toOptionArray()
    {        
      
      return array(

         '1' => array(
               'label' => Mage::helper('magalter_crossup')->__('Random'),
               'value' => '1'
                ),
         '2' => array(
               'label' => Mage::helper('magalter_crossup')->__('All'),
               'value' => '2'
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
