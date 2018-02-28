<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Add
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
        $this->_mode = 'add';
        $sku=$this->getRequest()->getParam('sku');
        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('Edit') 
            : $this->__('New');
        $this->_headerText =  'External Equivalent SKU Configuration for SKU :'.$sku;
        $this->_addButton('somebuttonid', array(
'label' => Mage::helper('smashingmagazine_branddirectory')->__('Add Partner'),'onclick' => "setLocation('{$this->getUrl('*/*/newp/sku/'.$sku)}')"
));
        $this->_addButton('bulkupload', array(
'label' => Mage::helper('smashingmagazine_branddirectory')->__('Bulk Upload'),'onclick' => "setLocation('{$this->getUrl('*/*/bulkupload')}')"
));
       
    }
    public function getBackUrl()
    {
        $sku=$this->getRequest()->getParam('sku');
        parent::getBackUrl();
        return $this->getUrl('smashingmagazine_branddirectory_admin/brand/index/sku/'.$sku);
    }
}