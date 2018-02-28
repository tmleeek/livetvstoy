<?php
 
class Magalter_Crossup_Block_System_Rules extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{ 
    public function __construct()
    {
        $this->addColumn('type', array(
            'label' => Mage::helper('magalter_crossup')->__('Event object'),
            'style' => 'width:220px',
        ));
        $this->addColumn('info', array(
            'label' => Mage::helper('magalter_crossup')->__('Pattern'),
            'style' => 'width:220px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('magalter_crossup')->__('Add event object');
        parent::__construct();
    }
}
