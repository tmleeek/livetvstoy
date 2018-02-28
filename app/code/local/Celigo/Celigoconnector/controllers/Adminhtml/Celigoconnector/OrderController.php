<?php

class Celigo_Celigoconnector_Adminhtml_Celigoconnector_OrderController extends Mage_Adminhtml_Controller_Action {

    const LOG_FILENAME = 'celigo-massorder-import.log';

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/celigoconnector_orders');
    }

    public function indexAction() {
        $this->_title($this->__('Sales'))->_title($this->__('NetSuite Orders'));
        $this->loadLayout();
        $this->_setActiveMenu('sales/sales');
        $this->_addContent($this->getLayout()->createBlock('celigoconnector/adminhtml_sales_order'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('celigoconnector/adminhtml_sales_order_grid')->toHtml()
        );
    }

    /**
     * Push the selected orders to NetSuite if not pushed
     */
    public function massimportAction() {
        try {
            $orderIds = $this->getRequest()->getPost('order_ids', array());
            $countPushedOrder = 0;
            $countNonPushedOrder = 0;
            $pushedOrders = array();
            $nonPushedOrders = array();
            foreach ($orderIds as $orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);
                if ($order->getId() && !$order->getPushedToNs()) {
                    $result = Mage::getModel('celigoconnector/celigoconnector')->pushOrderToNS($orderId, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC);
                    if ($result === true) {
                        $pushedOrders[] = $order->getIncrementId();
                        $countPushedOrder++;
                    } else {
                        $this->_getSession()->addError($this->__('Error (Order#  ' . $order->getIncrementId() . ') : ' . $result));
                    }
                } else {
                    $countNonPushedOrder++;
                    $nonPushedOrders[] = $order->getIncrementId();
                }
            }
            if ($countPushedOrder) {
                $this->_getSession()->addSuccess($this->__('%s order(s) have been pushed to NetSuite. <br/> %s', $countPushedOrder, implode(", ", $pushedOrders)));
            }
            if ($countNonPushedOrder) {
                $this->_getSession()->addError($this->__('%s order(s) were already pushed to NetSuite. <br/> %s', $countNonPushedOrder, implode(", ", $nonPushedOrders)));
            }
        } catch (Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . "Method name is massimportAction and Error is: " . $e->getMessage() . '"', self::LOG_FILENAME);
        }
        $this->_redirect('*/celigoconnector_order');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'netsuite_orders.csv';
        $grid       = $this->getLayout()->createBlock('celigoconnector/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'netsuite_orders.xml';
        $grid       = $this->getLayout()->createBlock('celigoconnector/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}