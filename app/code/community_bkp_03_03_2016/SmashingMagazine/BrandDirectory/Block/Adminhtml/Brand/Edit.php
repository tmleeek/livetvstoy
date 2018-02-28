<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Edit
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
        $this->_mode = 'edit';
         $resource = Mage::getSingleton('core/resource');
	$writeConnection = $resource->getConnection('core_write');
         $id=$this->getRequest()->getParam('id');
         $query_sel = "SELECT cps_sku FROM `site_link_configure_products` where entity_id=$id";
            $results = $writeConnection->fetchCol($query_sel);
        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('Edit') 
            : $this->__('New');
        $this->_headerText =  'External Equivalent SKU Configuration for SKU :'.$results[0];
    }
    public function getBackUrl()
    {
        $resource = Mage::getSingleton('core/resource');
	$writeConnection = $resource->getConnection('core_write');
         $id=$this->getRequest()->getParam('id');
         $query_sel = "SELECT cps_sku FROM `site_link_configure_products` where entity_id=$id";
            $results = $writeConnection->fetchCol($query_sel);
        parent::getBackUrl();
        return $this->getUrl('smashingmagazine_branddirectory_admin/brand/index/sku/'.$results[0]);
    }
}
