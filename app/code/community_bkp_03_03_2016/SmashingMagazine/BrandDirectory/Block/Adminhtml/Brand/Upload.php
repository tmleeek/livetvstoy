<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Upload
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'smashingmagazine_branddirectory_adminhtml';
        $this->_controller = 'brand';
        
        /**
         * The $_mode property tells Magento which folder to use to
         * locate the related form blocks to be displayed within this
         * form container. In our example this corresponds to 
         * BrandDirectory/Block/Adminhtml/Brand/Edit/.
         */
        $this->_mode = 'upload';
        
        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('New') 
            : $this->__('New');
        $this->_headerText =  'Bulk Upload';
        
       
    }
    
}