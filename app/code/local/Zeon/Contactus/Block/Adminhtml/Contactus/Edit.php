<?php
class Zeon_Contactus_Block_Adminhtml_Contactus_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'contactus';
        $this->_controller = 'adminhtml_contactus';

        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('contactus')->__('Delete')
        );

        $this->_removeButton('save');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('contactus')->__(
            "Information '%s'",
            $this->htmlEscape(Mage::registry('contactus_data')->getName())
        );
    }
}