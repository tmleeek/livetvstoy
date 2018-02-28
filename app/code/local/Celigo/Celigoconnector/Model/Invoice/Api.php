<?php
/**
 * Order Invoice api
 *
 * @category   Celigo
 * @package    Celigo_Invoice
 * @author     Kiran, Tarun Gupta<tarun.gupta@celigo.com>
 */
$orderInvoiceApi = Mage::getBaseDir('code') . DS . 'core' . DS . 'Mage' . DS . 'Sales' . DS . 'Model' . DS . 'Order' . DS . 'Invoice' . DS . 'Api.php';
require_once ($orderInvoiceApi);

class Celigo_Celigoconnector_Model_Invoice_Api extends Mage_Sales_Model_Order_Invoice_Api {
    const XML_PATH_NETSUTE_CAPTURE_METHODS = 'celigoconnector/othersettings/allowedmethods';
    const LOG_FILENAME = 'celigo-connector-api.log';
    protected $_celigoconnectorhelper;
    /**
     * Check if the amount captured in NetSuite for the order
     *
     * @param object $order
     * @return boolean
     */
    private function isCapturedInNetSuite($order = '') {
        $returnValue = false;
        if ($order == '') {

            return $returnValue;
        }
        $this->_celigoconnectorhelper = Mage::helper('celigoconnector');
        if (!$order->getPushedToNs()) {

            return $returnValue;
        }
        $storeId = $order->getStoreId();
        $websiteId = '';
        if (!$this->_celigoconnectorhelper->getIsCeligoconnectorModuleEnabled($storeId, $websiteId)) {

            return $returnValue;
        }
        $orderPaymentMethod = $order->getPayment()->getMethod();
        $allowedMethods = $this->_celigoconnectorhelper->getConfigValue(self::XML_PATH_NETSUTE_CAPTURE_METHODS, $storeId, $websiteId);
        if ($allowedMethods == '' || trim($allowedMethods) == ",") {

            return $returnValue;
        } else {
            $allowedMethodsArray = explode(",", $allowedMethods);
            if (is_array($allowedMethodsArray) && count($allowedMethodsArray) > 0 && in_array($orderPaymentMethod, $allowedMethodsArray)) {
                $returnValue = true;
            }
        }

        return $returnValue;
    }
    /**
     * Create new invoice for order
     *
     * @param string $orderIncrementId
     * @param array $itemsQty
     * @param string $comment
     * @param booleam $email
     * @param boolean $includeComment
     * @return string
     */
    public function create($orderIncrementId, $itemsQty, $comment = null, $email = false, $includeComment = false) {
        Mage::helper('celigoconnector/celigologger')->info('infomsg="Inside Invoice.create" class="Celigo_Celigoconnector_Model_Invoice_Api" record="order" id="'.$orderIncrementId.'"', self::LOG_FILENAME);
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        /* @var $order Mage_Sales_Model_Order */
        /**
         * Check order existing
         */
        if (!$order->getId()) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="No order found with increment id "'.$orderIncrementId.'", record="order" api="Invoice.create"', self::LOG_FILENAME);
            $this->_fault('order_not_exists', "No order found with increment id ".$orderIncrementId);
        }
        /**
         * Check invoice create availability
         */
        if (!$order->canInvoice()) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="Cannot do invoice for order increment id "'.$orderIncrementId.'", record="order" api="Invoice.create"', self::LOG_FILENAME);
            $this->_fault('data_invalid', Mage::helper('sales')->__('Cannot do invoice for order.'));
        }
        $invoice = $order->prepareInvoice($itemsQty);
        /* Following two line were added by Kiran to fix the Invoice status issue when created by API / Celigoconnector. It applies for the payment gateways that has ability to capture amount online. */
        if ($this->isCapturedInNetSuite($order)) {
            Mage::helper('celigoconnector/celigologger')->info('infomsg="Capturing offline " class="Celigo_Celigoconnector_Model_Invoice_Api"', self::LOG_FILENAME);
            $capture_case = 'offline';
            $invoice->setRequestedCaptureCase($capture_case)->setCanVoidFlag(false)->pay() /*->save()*/;
        }
        $invoice->register();
        if ($comment !== null) {
            $invoice->addComment($comment, $email);
        }
        if ($email) {
            $invoice->setEmailSent(true);
        }
        $invoice->getOrder()->setIsInProcess(true);
        try {
            $transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();
            $invoice->sendEmail($email, ($includeComment ? $comment : ''));
        }
        catch(Mage_Core_Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$e->getMessage().'", record="order" api="Invoice.create" record="order" id="'.$orderIncrementId.'"', self::LOG_FILENAME);
            $this->_fault('data_invalid', $e->getMessage());
        }

        return $invoice->getIncrementId();
    }
}
