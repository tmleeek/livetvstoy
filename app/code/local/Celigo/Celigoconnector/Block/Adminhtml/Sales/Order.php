<?php

class Celigo_Celigoconnector_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_blockGroup = 'celigoconnector';
        $this->_controller = 'adminhtml_sales_order';
        $this->_headerText = Mage::helper('celigoconnector')->__('Manage NetSuite Orders');

        parent::__construct();
        $this->_removeButton('add');
    }

    /**
     * Get header CSS class
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'icon-head head-' . strtr($this->_controller, '_', '-') . ' head-sales-order';
    }

    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        $output = parent::_toHtml();
        $output .= "<style type='text/css'>#celigo_order_grid_massaction-form { display: none; }</style>";
        return $output;
    }

}