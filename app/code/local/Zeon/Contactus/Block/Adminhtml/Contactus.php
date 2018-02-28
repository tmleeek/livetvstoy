<?php
class Zeon_Contactus_Block_Adminhtml_Contactus
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_contactus';
        $this->_blockGroup = 'contactus';
        $this->_headerText = Mage::helper('contactus')->__('Contactus Manager');
        $this->_addButtonLabel = Mage::helper('contactus')->__('Add Item');
        parent::__construct();
        $this->_removeButton('add');
    }

    protected function _enabledAddNewButton()
    {
        return false;
    }
}