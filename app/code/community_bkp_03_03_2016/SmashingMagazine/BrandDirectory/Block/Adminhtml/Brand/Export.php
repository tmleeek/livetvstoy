<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Export
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {

	//parent::__construct(); 
$this->_removeButton('save');
        //$this->_updateButton('save', 'label', Mage::helper('smashingmagazine_branddirectory')->__('Save Item'));

        $this->_blockGroup = 'smashingmagazine_branddirectory_adminhtml';
        $this->_controller = 'brand';
        
        /**
         * The $_mode property tells Magento which folder to use to
         * locate the related form blocks to be displayed within this
         * form container. In our example this corresponds to 
         * BrandDirectory/Block/Adminhtml/Brand/Edit/.
         */
        $this->_mode = 'export';
        /*$this->_addButton('save', array(
            'label'     => Mage::helper('adminhtml')->__('Export'),
            'onclick'   => 'editForm.submit()',
            'class'     => 'save',
        ), -100);*/
        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('New') 
            : $this->__('New');
        $this->_headerText =  'Export Details';
      
     //$this->_addButtonLabel = Mage::helper('smashingmagazine_branddirectory')->__('ssss');
    }
    
}
