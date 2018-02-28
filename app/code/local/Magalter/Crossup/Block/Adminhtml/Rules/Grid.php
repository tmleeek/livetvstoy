<?php

class Magalter_Crossup_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();
        $this->setId('magalterUpsellsGrid');
        $this->setUseAjax(false);
        $this->setSaveParametersInSession(true);
    }

    protected function _toHtml() {

        if (Mage::app()->isSingleStoreMode()) {
            return parent::_toHtml();
        }

        return Mage::getBlockSingleton('adminhtml/store_switcher')->setUseConfirm(false)->renderView() . parent::_toHtml();
    }

    protected function _prepareCollection() {

        $collection = Mage::getModel('magalter_crossup/crossup')->getCollection();
         
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('upsell_id', array(
            'header' => $this->__('Upsell ID'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'upsell_id'
        ));
        
        $this->addColumn('label', array(
            'header' => $this->__('Upsell Label'), 
            'align' => 'left',
            'width' => '50px',
            'index' => 'label'
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Upsell Name'), 
            'align' => 'left',
            'width' => '50px',
            'index' => 'name'
        ));
   
        $this->addColumn('status', array(
            'header' => $this->__('Upsell Status'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'status',
            'type' => 'options',
            'sortable' => false,
            'options' => Magalter_Crossup_Model_Source_Status::toFlatArray()
        ));
 
        $this->addColumn('available_from', array(
            'header' => $this->__('Available From'),
            'index' => 'available_from',
            'type' => 'datetime',
            'width' => '150px',
            'gmtoffset' => true,
            'default' => '--'
        ));

        $this->addColumn('available_to', array(
            'header' => $this->__('Available To'),
            'index' => 'available_to',
            'type' => 'datetime',
            'width' => '150px',
            'gmtoffset' => true,
            'default' => '--'
        ));

        $this->addColumn('action', array(
            'header' => $this->__('Action'),
            'width' => '150px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => $this->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array('store' => $this->_getStore()->getId())
                    ),
                    'field' => 'id'
                ),
                array(
                    'caption' => $this->__('Delete'),
                    'url' => array(
                        'base' => '*/*/delete',
                        'params' => array('store' => $this->_getStore()->getId())
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));
 
        $this->addExportType('*/*/exportCsv', Mage::helper('magalter_crossup')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('magalter_crossup')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('upsell_id');
        $this->getMassactionBlock()->setFormFieldName('magalter_crossup');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->__('Are you sure?')
        )); 

        return $this;
    }

    protected function _getStore() {

        if (!$this->getData('store')) {

            $storeId = (int) $this->getRequest()->getParam('store', 0);
            $this->setData('store', Mage::app()->getStore($storeId));
        }

        return $this->getData('store');
    }

    protected function filterByPosition($collection, $column) {

        $val = $column->getFilter()->getValue();
       
        $cond = "FIND_IN_SET('$val', {$column->getIndex()})";

        $collection->getSelect()->where($cond);
        
    }
    
     protected function filterByProductName($collection, $column) {
          
        $val = $column->getFilter()->getValue();
         
        if (!$val) {
            return $this;
        }
        else {
            $cond = "IF(at_name_store.value_id > 0, at_name_store.value, at_name_default.value) LIKE '%{$val}%'";
        }

        $collection->getSelect()->where($cond);
        
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array(
                    'store' => $this->_getStore()->getId(),
                    'id' => $row->getId())
        );
    }

}
