<?php

class Magalter_Crossup_Block_Adminhtml_Rules extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {  
        
        parent::__construct(); 
        
        $this->_controller = 'adminhtml_rules';
        $this->_blockGroup = 'magalter_crossup';        
        $this->_headerText = $this->__('Upsell Rules');        
         
    }

}