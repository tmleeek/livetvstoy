<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        /**
         * Tell Magento which Collection to use for displaying in the grid.
         */
        $sku=$this->getRequest()->getParam('sku');
        $collection = Mage::getResourceModel(
            'smashingmagazine_branddirectory/brand_collection'
        );
        $collection->getSelect()->where('cps_sku = ?', $sku);
       // $collection->printLogQuery(true);
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
            'smashingmagazine_branddirectory_admin/brand/edit', 
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
        $this->addColumn('entity_id', array(
            'header' => $this->_getHelper()->__('ID'),
            'type' => 'number',
            'index' => 'entity_id',
        ));
        
        $this->addColumn('part_no', array(
            'header' => $this->_getHelper()->__('Client SKU#'),
            'type' => 'text',
            'index' => 'part_no',
        ));
        
        $this->addColumn('product_name', array(
            'header' => $this->_getHelper()->__('Product Name'),
            'type' => 'text',
            'index' => 'product_name',
        ));
        
        $this->addColumn('partner_name', array(
            'header' => $this->_getHelper()->__('Partner Name'),
            'type' => 'text',
            'index' => 'partner_name',
        ));
        $this->addColumn('intro_date', array(
            'header' => $this->_getHelper()->__('Introduction Date'),
            'type' => 'text',
            'index' => 'intro_date',
        ));
       
        
        /**
         * Finally we add an action column with an edit link.
         */
        $this->addColumn('action', array(
            'header' => $this->_getHelper()->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => $this->_getHelper()->__('Edit'),
                    'url' => array(
                        'base' => 'smashingmagazine_branddirectory_admin'
                                  . '/brand/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'entity_id',
        ));
        
        return parent::_prepareColumns();
    }
    
    protected function _getHelper()
    {
        return Mage::helper('smashingmagazine_branddirectory');
    }
}