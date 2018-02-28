<?php

class Celigo_Celigoconnector_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    const LOG_FILENAME = 'celigo-connector-adminhtml.log';

    public function __construct() {
        parent::__construct();
        $this->setId('celigo_order_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();
        $html.= '<button id="push_to_ns_button" title="Push To NetSuite" type="button" class="scalable " onclick="document.getElementById(\'celigo_order_grid_massaction-select\').value = \'push_to_netsuite\';celigo_order_grid_massactionJsObject.apply();"><span><span><span>Push To NetSuite</span></span></span></button>';
        return $html;
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass() {
        return 'sales/order_collection';
    }

    protected function _prepareCollection() {
        $this->setDefaultFilter(array('pushed_to_ns' => 0));
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $resource = Mage::getSingleton('core/resource');
        $collection->getSelect()->join(
            array('order' => $resource->getTableName('sales/order_grid')),
            'main_table.entity_id = order.entity_id',
            array('billing_name' => 'billing_name', 'shipping_name' => 'shipping_name')
        );
        $collection->addFilterToMap('increment_id', 'main_table.increment_id')
                ->addFilterToMap('store_id', 'main_table.store_id')
                ->addFilterToMap('created_at', 'main_table.created_at')
                ->addFilterToMap('base_grand_total', 'main_table.base_grand_total')
                ->addFilterToMap('grand_total', 'main_table.grand_total')
                ->addFilterToMap('status', 'main_table.status')
                ->addFilterToMap('store_id', 'main_table.store_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('real_order_id', array(
            'header' => Mage::helper('sales')->__('Order #'),
            'type' => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('sales')->__('Purchased From (Store)'),
                'index' => 'store_id',
                'type' => 'store',
                'store_view' => true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        $this->addColumn('pushed_to_ns', array(
            'header' => Mage::helper('sales')->__('Is Imported'),
            'index' => 'pushed_to_ns',
            'type' => 'options',
            'sortable' => false,
            'options' => array(
                '1' => Mage::helper('sales')->__('Yes'),
                '0' => Mage::helper('sales')->__('No'),
            ),
            'filter_condition_callback' => array($this, '_isImportedFilter'),
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action', array(
                'header' => Mage::helper('sales')->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('sales')->__('View'),
                        'url' => array('base' => 'adminhtml/sales_order/view'),
                        'target' => 'blank',
                        'field' => 'order_id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
        }
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('push_to_netsuite', array(
            'label' => Mage::helper('sales')->__('Push To NetSuite'),
            'url' => $this->getUrl('adminhtml/celigoconnector_order/massimport')
        ));

        return $this;
    }

    public function getRowUrl($row) {
        return false;
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _isImportedFilter($collection, $column) {
        $value = $column->getFilter()->getValue();
        if ($value == 1) {
            $collection->addFieldToFilter('main_table.pushed_to_ns', 1);
        } elseif ($value == 0) {
            $collection->addFieldToFilter('main_table.pushed_to_ns', $value);
            $startdate = Mage::helper('celigoconnector')->getConfigValue(Celigo_Celigoconnector_Model_Cron::XML_PATH_CONNECTOR_CRON_STARTDATE);
            if (trim($startdate) != '') {
                $startdate = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($startdate));
                $collection->addFieldToFilter('main_table.created_at', array(
                    'from' => $startdate
                ));
            }
            $storeSettings = array();
            foreach (Mage::app()->getStores() as $store) {
                $storeId = $store->getStoreId();
                if (!Mage::helper('celigoconnector')->getIsCeligoconnectorModuleEnabled($storeId)) {
                    continue;
                }
                $statuses = Mage::helper('celigoconnector')->getOrderStatusArray($storeId);
                if (count($statuses) == 0) {
                    continue;
                }
                $storeSettings[] = "main_table.store_id = $storeId AND main_table.status IN ('" . implode("','", $statuses) . "')";
            }
            if (count($storeSettings) > 0) {
                $collection->getSelect()->where(implode(" OR ", $storeSettings));
            } else {
                $collection->addFieldToFilter('main_table.store_id', -1);
            }
        }
        return $this;
    }

}