<?php
class Zeon_Bannerslider_Block_Adminhtml_Banner_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $storeId = $this->getRequest()->getParam('store');
        $collection = Mage::getModel('bannerslider/banner')
            ->getCollection()
            ->setStoreId($storeId);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'banner_id',
            array(
                'header'       => Mage::helper('bannerslider')->__('ID'),
                'align'        => 'right',
                'width'        => '50px',
                'filter_index' => 'main_table.banner_id',
                'index'        => 'banner_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('bannerslider')->__('Name'),
                'align'  => 'left',
                'width'  => '100px',
                'index'  => 'name',
            )
        );

        $this->addColumn(
            'click_url',
            array(
                'header' => Mage::helper('bannerslider')->__('URL'),
                'align'  => 'left',
                'index'  => 'click_url',
            )
        );

        $this->addColumn(
            'start_time',
            array(
                'header' => Mage::helper('bannerslider')->__('Start Date'),
                'align'  => 'left',
                'type'   => 'date',
                'index'  => 'start_time',
            )
        );

        $this->addColumn(
            'end_time',
            array(
                'header' => Mage::helper('bannerslider')->__('End Date'),
                'align' => 'left',
                'type' => 'date',
                'index' => 'end_time',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'       => Mage::helper('bannerslider')->__('Status'),
                'align'        => 'left',
                'width'        => '80px',
                'filter_index' => 'main_table.status',
                'index'        => 'status',
                'type'         => 'options',
                'options'      => array(
                    0 => 'Disabled',
                    1 => 'Enabled',
                ),
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'    => Mage::helper('bannerslider')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('bannerslider')->__('Edit'),
                        'url'     => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            )
        );
        $this->addColumn(
            'imagename',
            array(
                'header' => Mage::helper('bannerslider')->__('Image'),
                'align' => 'center',
                'width' => '150px',
                'index' => 'imagename',
                'renderer' => 'bannerslider/adminhtml_renderer_imagebanner'
            )
        );
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('banner');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'   => Mage::helper('bannerslider')->__('Delete'),
                'url'     => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('bannerslider')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'status1',
            array(
                'label'    => Mage::helper('bannerslider')->__('Enable Status'),
                'url'      => $this->getUrl('*/*/massStatus/status/1'),
                'confirm'  => Mage::helper('bannerslider')
                    ->__('Are you sure want to enable?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'status2',
            array(
                'label'    => Mage::helper('bannerslider')
                    ->__('Disable Status'),
                'url'      => $this->getUrl('*/*/massStatus/status/0'),
                'confirm'  => Mage::helper('bannerslider')
                    ->__('Are you sure want to disable?')
           )
        );

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array(
                'id'    => $row->getId(),
                'store' => $this->getRequest()->getParam('store')
            )
        );
    }

}