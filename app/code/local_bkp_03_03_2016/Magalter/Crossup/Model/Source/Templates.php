<?php 
class Magalter_Crossup_Model_Source_Templates
{    
    public static function toOptionArray()
    {   
        $data = Mage::getSingleton('magalter_crossup/templates')->getCollection()->toArray(array('template_id', 'name'));
       
        return $data['items'];
    } 


    public static function toFlatArray() {

        $options = self::toOptionArray();
        
        $flatOptions = array();
        
            foreach($options as $option) { 
                $flatOptions[$option['template_id']] = $option['name'];                 
            }
        
        return $flatOptions;
        
    }

 
}
