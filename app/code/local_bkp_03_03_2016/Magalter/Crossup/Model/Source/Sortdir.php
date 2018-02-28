<?php 
class Magalter_Crossup_Model_Source_Sortdir
{    
    public static function toOptionArray($item = false, $dbtranslation = true)
    {        
      $options = array(

         '1' => array(
               'label' => Mage::helper('magalter_crossup')->__('Ascending'),
               'value' => '1',
               'dbname' => 'ASC'
                ),
         '2' => array(
               'label' => Mage::helper('magalter_crossup')->__('Decending'),
               'value' => '2',
               'dbname' => 'DESC'
                ),
         '3' => array(
               'label' => Mage::helper('magalter_crossup')->__('Random'),
               'value' => '3',
               'dbname' => 'RAND'
         ),    
       );  
      
      if($item) {
         if(isset($options[$item])) {
            if($dbtranslation) {
               return $options[$item]['dbname'];
            }  
            return false;
         }
         return $options[$item];         
      }
      
      return $options;
 
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
