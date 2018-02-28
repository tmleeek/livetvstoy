<?php
class SmashingmagazineReport_BrandDirectory_Block_Adminhtml_Brand_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        /**
         * Tell Magento which Collection to use for displaying in the grid.
         */
        $collection = Mage::getResourceModel(
            'smashingmagazinereport_branddirectory/brand_collection'
        );
        $collection->setOrder('createdate', 'DESC');
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }
    
    public function getRowUrl($row)
    {
        /**
         * When a grid row is clicked, this is where the user should
         * be redirected to. In our example, the method editAction of 
         * BrandController.php in BrandDirectory module.
         */
        return $this->getUrl(
            'smashingmagazinereport_branddirectory_admin/brand/edit', 
            array(
                'id' => $row->getId()
            )
        );
    }

    protected function _prepareColumns()
    {
        /**
         * Here we define which columns we want to be displayed in the grid.
         */
         $this->addColumn('token_id', array(
            'header' => $this->_getHelper()->__('Token'),
            'type' => 'text',
            'index' => 'token_id',
        ));
        $this->addColumn('walmart_order', array(
            'header' => $this->_getHelper()->__('Order No'),
            'type' => 'text',
            'index' => 'walmart_order',
        ));
        $this->addColumn('createdate', array(
            'header' => $this->_getHelper()->__('Order Date'),
            'type' => 'datetime',
            'index' => 'createdate',
        ));
        
        $this->addColumn('partnername', array(
            'header' => $this->_getHelper()->__('Partner'),
            'type' => 'text',
            'index' => 'partnername',
        ));
       
        $this->addColumn('external_prodid', array(
            'header' => $this->_getHelper()->__('Client Sku'),
            'type' => 'text',
            'index' => 'external_prodid',
        ));
        $this->addColumn('cps_sku', array(
            'header' => $this->_getHelper()->__('CPS Sku'),
            'type' => 'text',
            'index' => 'cps_sku',
        ));
        $this->addColumn('extpartno', array(
            'header' => $this->_getHelper()->__('Size'),
            'type' => 'text',
            'index' => 'extpartno',
        ));
        $this->addColumn('raw_pers_string', array(
            'header' => $this->_getHelper()->__('Personalization Field'),
            'type' => 'text',
            'index' => 'raw_pers_string',
        ));
        $this->addExportType('*/*/exportCsv',
         Mage::helper('smashingmagazinereport_branddirectory')->__('CSV'));
        return parent::_prepareColumns();
    }
    
    protected function _getHelper()
    {
        return Mage::helper('smashingmagazinereport_branddirectory');
    }
}