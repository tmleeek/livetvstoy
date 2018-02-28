<?php 
class Magalter_Crossup_Model_Source_Sortorder
{    
    public static function toOptionArray($item = false, $dbtranslation = true)
    {        
      
      $options = array(

         '1' => array(
               'label' => Mage::helper('magalter_crossup')->__('Price'),
               'value' => '1',
               'dbname' => 'price_index.price'
                ),
         '2' => array(
               'label' => Mage::helper('magalter_crossup')->__('Date added'),
               'value' => '2',
               'dbname' => 'e.created_at'
                ),
         '3' => array(
               'label' => Mage::helper('magalter_crossup')->__('Date updated'),
               'value' => '3',
               'dbname' => 'e.updated_at'
                ),
         '4' => array(
               'label' => Mage::helper('magalter_crossup')->__('Sku'),
               'value' => '4',
               'dbname' => 'e.sku'
                ),
          '5' => array(
               'label' => Mage::helper('magalter_crossup')->__('ID'),
               'value' => '5',
               'dbname' => 'e.entity_id'
                ),
          '6' => array( 
               'label' => Mage::helper('magalter_crossup')->__('Tax class'),
               'value' => '6',
               'dbname' => 'price_index.tax_class_id'
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
