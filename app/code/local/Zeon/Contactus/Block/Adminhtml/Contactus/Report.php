<?php
class Zeon_Contactus_Block_Adminhtml_Contactus_Report
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('contactusGrid');
        $this->setDefaultSort('contactus_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('contactus/contactus')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'contactus_id',
            array(
                'header'    => Mage::helper('contactus')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'contactus_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('contactus')->__('Name'),
                'align'     =>'left',
                'index'     => 'name',
            )
        );
        $this->addColumn(
            'email',
            array(
                'header'    => Mage::helper('contactus')->__('Email'),
                'align'     =>'left',
                'index'     => 'email',
            )
        );
        $this->addColumn(
            'phone',
            array(
                'header'    => Mage::helper('contactus')->__('Phone'),
                'align'     =>'left',
                'index'     => 'phone',
            )
        );

        $this->addColumn(
            'message',
            array(
                'header'    => Mage::helper('contactus')->__('Message'),
                'align'     =>'left',
                'index'     => 'message',
            )
        );

        $this->addColumn(
            'contacted_on',
            array(
                'header'    => Mage::helper('contactus')->__('Date'),
                'type'      => 'datetime',
                'align'     => 'center',
                'gmtoffset' => true,
                'index'     => 'contacted_on',
            )
        );


        $this->addColumn(
            'action',
            array(
                'header'    =>  Mage::helper('contactus')->__('Action'),
                'width'     => '50',
                'type'      => 'action',
                'align'     => 'center',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('contactus')->__('View'),
                        'url'       => array('base'=> '*/*/view'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            )
        );

        $this->addExportType(
            '*/*/exportCsv',
            Mage::helper('contactus')->__('CSV')
        );
        $this->addExportType(
            '*/*/exportXml',
            Mage::helper('contactus')->__('XML')
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('contactus_id');
        $this->getMassactionBlock()->setFormFieldName('contactus');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'    => Mage::helper('contactus')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('contactus')->__('Are you sure?')
            )
        );
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }

}