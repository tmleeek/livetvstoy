<?php
if (class_exists('Mage_Sales_Model_Order_Creditmemo_Api')) {

    class Celigo_Celigoconnectorplus_Model_Sales_Order_Creditmemo_Api extends Mage_Sales_Model_Order_Creditmemo_Api {
        const EXPECTED_ARGUMENT_MISSING_MSG = 'Expected argument is_imported is missing';
        const INVALID_ARGUMENT_VALUE_MSG = 'The argument is_imported accepts 0 and 1 only. 0 for not imported and 1 for imported.';
        const IS_IMPORTED_CREDITMEMO_FLAG = 1;
        const UNABLE_TO_CREATE_CREDITMEMO = 'Unable to create credit memo.';
        const CANNOT_CREATE_CREDITMEMO = 'Cannot create credit memo for the order';
        const INCORRECT_DATA_CANNOT_CREATE = 'The data is incorrect, cannot create credit memo';
        const LOG_FILENAME = 'celigo-connector-api.log';

        /**
         * Create credit memo
         */
        public function create($data, $creditmemoData = null, $comment = null, $notifyCustomer = false,
        $includeComment = false, $refundToStoreCreditAmount = null) {
            Mage::helper('celigoconnector/celigologger')->info('infomsg="api=credit memo - create"' , self::LOG_FILENAME);
            if (!isset($data['creditmemo']['do_offline'])) {
                $data['creditmemo']['do_offline'] = true;
            }
            $order = null;
            if (!isset($data['order_id']) && isset($data['invoice_id'])) {
                $_invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($data['invoice_id']);
                if (!$_invoice->getId()) {
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="Requested Invoice do not exist,id '.$data['invoice_id'].'"', self::LOG_FILENAME);
                    $this->_fault("data_invalid", "Invoice Number: " . $data['invoice_id'] . " Does not Exists");
                }
                $order = Mage::getModel('sales/order')->load($_invoice->getOrderId());
                if (!$order->getId()) {
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="Requested order do not exist for invoice id '.$data['invoice_id'].'"', self::LOG_FILENAME);
                    $this->_fault("data_invalid", "Order does not exists for Invoice Id: " . $data['invoice_id']);
                }
                $data['order_id'] = $order->getIncrementId();
            }
            //load if order not already loaded
            if(isset($order)){
                $order = Mage::getModel('sales/order');
                $order->loadByIncrementId($data['order_id']);
                if (!$order->getId()) {
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="Requested order do not exist '.$data['order_id'].'"', self::LOG_FILENAME);
                    $this->_fault("data_invalid", "Order Number: " . $data['order_id'] . " Does not Exists");
                }
            }
            $orderId = $order->getId();
            $invoiceId = '';
            $invoiceGrandTotal = 0;
            if ($order->hasInvoices()) {
                $invoiceCollection = $order->getInvoiceCollection();
                if ($invoiceCollection->count() > 0) {

                    foreach ($invoiceCollection as $invoice) {
                        if ($invoice->getIncrementId() == $data['invoice_id']) {
                            $invoiceId = $invoice->getId();
                            $invoiceGrandTotal = $invoice->getGrandTotal();
                            break;
                        }
                    }
                }
            } else {
                Mage::helper('celigoconnector/celigologger')->error('errormsg="Requested order '.$data['order_id'].' has no invoices."', self::LOG_FILENAME);
                $this->_fault("data_invalid", "Invoice Number " . $data['invoice_id'] . " for Order Number: " . $data['order_id'] . " Does not Exists");
            }
            if ($orderId != '' && $invoiceId != '') {
                $data['order_id'] = $orderId;
                $data['invoice_id'] = $invoiceId;
            } else {
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'."Invoice Number " . $data['invoice_id'] . " for Order Number: " . $data['order_id'] .'"', self::LOG_FILENAME);
                $this->_fault("data_invalid", "Invoice Number " . $data['invoice_id'] . " for Order Number: " . $data['order_id'] . " Does not Exists");
            }
            /*Currency check*/
            $baseCurrency = $order->getBaseCurrencyCode();
            $orderCurrency = $order->getOrderCurrencyCode();
            if ($orderCurrency != $baseCurrency) {
                $baseToOrderRate = $order->getBaseToOrderRate();
                if ($data['creditmemo']['shipping_amount']) {
                    $data['creditmemo']['shipping_amount'] = round($data['creditmemo']['shipping_amount'] / $baseToOrderRate, 2);
                }
                if ($data['creditmemo']['adjustment_positive']) {
                    $data['creditmemo']['adjustment_positive'] = round($data['creditmemo']['adjustment_positive'] / $baseToOrderRate, 2);
                }
                if ($data['creditmemo']['adjustment_negative']) {
                    $data['creditmemo']['adjustment_negative'] = round($data['creditmemo']['adjustment_negative'] / $baseToOrderRate, 2);
                }
            }
            /**/
            $invoiceGrandTotal = $invoiceGrandTotal + $data['creditmemo']['shipping_amount'] + $data['creditmemo']['adjustment_positive'];
            if ($data['creditmemo']['adjustment_negative'] > $invoiceGrandTotal) {
                $this->_fault("data_invalid", "The Invoice amount is less than the refund amount. Please try again.");
            }
            if (isset($data['creditmemo']['refund_customerbalance_return_enable'])) {
                $data['creditmemo']['refund_customerbalance_return'] = $invoiceGrandTotal - $data['creditmemo']['adjustment_negative'];
            }
            try {
                $createCreditMemoResult = $this->createCreditMemo($data);

                return $createCreditMemoResult;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                $this->_fault('data_invalid', $msg );
            }

            return false;
        }
        /**
         * Create new creditmemo from order
         *
         * @param array 	$data // Built the with the same structure as the AdminHtml form used on backend.
         * @param integer 	$data[order_id]
         * @param integer 	$data[invoice_id]
         * @param array 	$data[creditmemo][items] // for each of the items on order/invoice
         * @param array 	$data[creditmemo][items][{item_id}] // item_id from sales_flat_order_item
         * @param boolean 	$data[creditmemo][items][{item_id}]['back_to_stock']
         * @param integer 	$data[creditmemo][items][{item_id}]['qty'] // Qty to return
         * @param string 	$data[creditmemo][comment_text]
         * @param string 	$data[creditmemo][comment_customer_notify]
         * @param float 	$data[creditmemo][shipping_amount]
         * @param float 	$data[creditmemo][adjustment_positive]
         * @param float 	$data[creditmemo][adjustment_negative]
         * @param booleam 	$data[creditmemo][do_offline]
         * @param boolean 	$data[creditmemo][send_email]
         * @return array
         */
        private function createCreditMemo($data = array()) {
            $this->tempData = $data;
            $creditmemo = $this->_initCreditmemo();
            if ($creditmemo) {
                if (($creditmemo->getGrandTotal() <= 0) && (!$creditmemo->getAllowZeroGrandTotal())) {
                    Mage::throwException(self::INCORRECT_DATA_CANNOT_CREATE);
                }
                $comment = '';
                if (!empty($data['creditmemo']['comment_text'])) {
                    $creditmemo->addComment($data['creditmemo']['comment_text'], isset($data['creditmemo']['comment_customer_notify']));
                    if (isset($data['creditmemo']['comment_customer_notify'])) {
                        $comment = $data['creditmemo']['comment_text'];
                    }
                }
                if (isset($data['creditmemo']['do_refund'])) {
                    $creditmemo->setRefundRequested(true);
                }
                if ($creditmemo->canRefund()) {
                    if ($creditmemo->getInvoice() && $creditmemo->getInvoice()->getTransactionId()) {
                        $data['creditmemo']['do_offline'] = false;
                    }
                }
                if (isset($data['creditmemo']['do_offline'])) {
                    $creditmemo->setOfflineRequested((bool)(int)$data['creditmemo']['do_offline']);
                }
                try {
                    $creditmemo->register();
                    if (!empty($data['creditmemo']['send_email'])) {
                        $creditmemo->setEmailSent(true);
                    }
                    $creditmemo->getOrder()->setCustomerNoteNotify(!empty($data['creditmemo']['send_email']));
                    $creditmemo->setIsImported(self::IS_IMPORTED_CREDITMEMO_FLAG);
                    $this->_saveCreditmemo($creditmemo);
                    $creditmemo->sendEmail(!empty($data['creditmemo']['send_email']) , $comment);
                    $this->tempData['message'] = array();
                    $this->tempData['message'][0] = array();
                    $this->tempData['message'][0][0] = $creditmemo->getIncrementId();
                    $this->tempData['message'][0][1] = $data['creditmemo']['do_offline'] ;
                    return $this->tempData['message'][0];
                }
                catch(Mage_Core_Exception $e) {
                    $msg = $e->getMessage();
                    if(method_exists($e, 'getCustomMessage')){
                        $msg =  $msg.", custom message:".$e->getCustomMessage();
                    }
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                    Mage::throwException($msg);
                }
            }

            return false;
        }
        private function _saveCreditmemo($creditmemo) {
            try {
                $transactionSave = Mage::getModel('core/resource_transaction')->addObject($creditmemo)->addObject($creditmemo->getOrder());
                if ($creditmemo->getInvoice()) {
                    $transactionSave->addObject($creditmemo->getInvoice());
                }
                $transactionSave->save();

                return;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        /**
         * Initialize creditmemo model instance
         *
         * @return Mage_Sales_Model_Order_Creditmemo
         */
        private function _initCreditmemo() {
            $creditmemo = false;
            if(isset($this->tempData['creditmemo_id']))
                $creditmemoId = $this->tempData['creditmemo_id'];
            $orderId = $this->tempData['order_id'];
            $invoiceId = $this->tempData['invoice_id'];
            try {
                $order = Mage::getModel('sales/order')->load($orderId);
                if (!$order->canCreditmemo()) {
                    if ($invoiceId) {
                        $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)->setOrder($order);
                        $capture_case = 'offline';
                        $invoice->setRequestedCaptureCase($capture_case)->setCanVoidFlag(false)->pay() /*->save()*/;
                        $transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder());
                        $transactionSave->save();
                    }
                    $this->tempData['message'][] = self::UNABLE_TO_CREATE_CREDITMEMO." for order : ".$orderId;
                }
            }
            catch(Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
            if (isset($creditmemoId)) {
                $creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId);
            } elseif ($orderId) {
                $data = $this->tempData['creditmemo'];
                $order = Mage::getModel('sales/order')->load($orderId);
                if ($invoiceId) {
                    $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)->setOrder($order);
                }
                if (!$order->canCreditmemo()) {
                    $this->tempData['message'][] = self::CANNOT_CREATE_CREDITMEMO . " " . $order->getIncrementId();
                    Mage::throwException(self::CANNOT_CREATE_CREDITMEMO . " " . $order->getIncrementId());
                }
                if (isset($data['items'])) {
                    $savedData = $data['items'];
                } else {
                    $savedData = array();
                }
                $qtys = array();
                $backToStock = array();

                foreach ($savedData as $orderItemId => $itemData) {
                    if (isset($itemData['qty'])) {
                        $qtys[$orderItemId] = $itemData['qty'];
                    }
                    if (isset($itemData['back_to_stock']) && $itemData['back_to_stock'] == true) {
                        $backToStock[$orderItemId] = true;
                    }
                }
                $data['qtys'] = $qtys;
                try {
                    $service = Mage::getModel('sales/service_order', $order);
                    if ($invoice) {
                        $creditmemo = $service->prepareInvoiceCreditmemo($invoice, $data);
                    } else {
                        $creditmemo = $service->prepareCreditmemo($data);
                    }
                    /**
                     * Process back to stock flags
                     */

                    foreach ($creditmemo->getAllItems() as $creditmemoItem) {
                        $orderItem = $creditmemoItem->getOrderItem();
                        $parentId = $orderItem->getParentItemId();
                        if (isset($backToStock[$orderItem->getId() ])) {
                            $creditmemoItem->setBackToStock(true);
                        } elseif ($orderItem->getParentItem() && isset($backToStock[$parentId]) && $backToStock[$parentId]) {
                            $creditmemoItem->setBackToStock(true);
                        } elseif (empty($savedData)) {
                            $creditmemoItem->setBackToStock(Mage::helper('cataloginventory')->isAutoReturnEnabled());
                        } else {
                            $creditmemoItem->setBackToStock(false);
                        }
                    }
                }
                catch(Mage_Core_Exception $e) {
                    $msg = $e->getMessage();
                    if(method_exists($e, 'getCustomMessage')){
                        $msg =  $msg.", custom message:".$e->getCustomMessage();
                    }
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                    Mage::throwException($msg);
                }
            }
            try {

                foreach ($this->tempData as $index => $value) {
                    Mage::app()->getRequest()->setParam($index, $value);
                }
                $args = array(
                    'creditmemo' => $creditmemo,
                    'request' => Mage::app()->getRequest()
                );
                Mage::dispatchEvent('adminhtml_sales_order_creditmemo_register_before', $args);
                $this->creditmemoDataImport($creditmemo, $this->tempData);

                return $creditmemo;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        private function creditmemoDataImport($creditmemo, $request) {
            try {
                $input = $request['creditmemo'];
                if (isset($input['refund_customerbalance_return']) && isset($input['refund_customerbalance_return_enable'])) {
                    $enable = $input['refund_customerbalance_return_enable'];
                    $amount = $input['refund_customerbalance_return'];
                    if ($enable && is_numeric($amount)) {
                        $amount = max(0, min($creditmemo->getBaseCustomerBalanceReturnMax() , $amount));
                        if ($amount) {
                            $amount = $creditmemo->getStore()->roundPrice($amount);
                            $creditmemo->setBaseCustomerBalanceTotalRefunded($amount);
                            $amount = $creditmemo->getStore()->roundPrice($amount * $creditmemo->getOrder()->getStoreToOrderRate());
                            $creditmemo->setCustomerBalanceTotalRefunded($amount);
                            //setting flag to make actual refund to customer balance after creditmemo save
                            $creditmemo->setCustomerBalanceRefundFlag(true);
                            $creditmemo->setPaymentRefundDisallowed(true);
                        }
                    }
                }
                if (isset($input['refund_customerbalance']) && $input['refund_customerbalance']) {
                    $creditmemo->setRefundCustomerBalance(true);
                }
                if (isset($input['refund_real_customerbalance']) && $input['refund_real_customerbalance']) {
                    $creditmemo->setRefundRealCustomerBalance(true);
                    $creditmemo->setPaymentRefundDisallowed(true);
                }
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        /**
         * Update credit memo information
         *
         * @param string $creditmemoIncrementId
         * @param array credit memo data
         * @return string
         */
        public function update($creditmemoIncrementId, $data) {
            if (!isset($data['is_imported'])) {
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.self::EXPECTED_ARGUMENT_MISSING_MSG.'"', self::LOG_FILENAME);
                Mage::throwException(self::EXPECTED_ARGUMENT_MISSING_MSG);
            }
            if ($data['is_imported'] != 0 && $data['is_imported'] != 1) {
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.self::INVALID_ARGUMENT_VALUE_MSG.'"', self::LOG_FILENAME);
                Mage::throwException(self::INVALID_ARGUMENT_VALUE_MSG);
            }
            try {
                $is_imported = $data['is_imported'];
                $creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
                if ($is_imported != $creditmemo->getIsImported()) {
                    $creditmemo->setIsImported($is_imported)
                            ->setAutomaticallyCreated(true)
                            ->save();
                }
            }
            catch(Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                $this->_fault('data_invalid', $msg );
            }

            return $creditmemoIncrementId;
        }
        /**
         * Retrieve credit memos by filters
         *
         * @param array|null $filter
         * @return array
         */
        public function items($filter = null) {

            return parent::items($filter);
        }
        /**
         * Retrieve credit memo information
         *
         * @param string $creditmemoIncrementId
         * @return array
         */
        public function info($creditmemoIncrementId) {

            return parent::info($creditmemoIncrementId);
        }
    }
} else {

    class Celigo_Celigoconnectorplus_Model_Sales_Order_Creditmemo_Api extends Mage_Sales_Model_Api_Resource {
        const EXPECTED_ARGUMENT_MISSING_MSG = 'Expected argument is_imported is missing';
        const INVALID_ARGUMENT_VALUE_MSG = 'The argument is_imported accepts 0 and 1 only. 0 for not imported and 1 for imported.';
        const IS_IMPORTED_CREDITMEMO_FLAG = 1;
        const UNABLE_TO_CREATE_CREDITMEMO = 'Unable to create credit memo.';
        const INCORRECT_DATA_CANNOT_CREATE = 'The data is incorrect, cannot create credit memo';
        /**
         * Create credit memo
         */
        public function create($data) {
            if (!isset($data['creditmemo']['do_offline'])) {
                $data['creditmemo']['do_offline'] = true;
            }
            if (!isset($data['order_id']) && isset($data['invoice_id'])) {
                $_invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($data['invoice_id']);
                if (!$_invoice->getId()) {
                    $this->_fault("data_invalid", "Invoice Number: " . $data['invoice_id'] . " Does not Exists");
                }
                $_order = Mage::getModel('sales/order')->load($_invoice->getOrderId());
                if (!$_order->getId()) {
                    $this->_fault("data_invalid", "Order does not exists for Invoice Id: " . $data['invoice_id']);
                }
                $data['order_id'] = $_order->getIncrementId();
            }
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($data['order_id']);
            if (!$order->getId()) {
                $this->_fault("data_invalid", "Order Number: " . $data['order_id'] . " Does not Exists");
            }
            $orderId = $order->getId();
            $invoiceId = '';
            $invoiceGrandTotal = 0;
            if ($order->hasInvoices()) {
                $invoiceCollection = $order->getInvoiceCollection();
                if ($invoiceCollection->count() > 0) {

                    foreach ($invoiceCollection as $invoice) {
                        if ($invoice->getIncrementId() == $data['invoice_id']) {
                            $invoiceId = $invoice->getId();
                            $invoiceGrandTotal = $invoice->getGrandTotal();
                            break;
                        }
                    }
                }
            } else {
                $this->_fault("data_invalid", "Invoice Number " . $data['invoice_id'] . " for Order Number: " . $data['order_id'] . " Does not Exists");
            }
            if ($orderId != '' && $invoiceId != '') {
                $data['order_id'] = $orderId;
                $data['invoice_id'] = $invoiceId;
            } else {
                $this->_fault("data_invalid", "Invoice Number " . $data['invoice_id'] . " for Order Number: " . $data['order_id'] . " Does not Exists");
            }
            /*Currency check*/
            $baseCurrency = $order->getBaseCurrencyCode();
            $orderCurrency = $order->getOrderCurrencyCode();
            if ($orderCurrency != $baseCurrency) {
                $baseToOrderRate = $order->getBaseToOrderRate();
                if ($data['creditmemo']['shipping_amount']) {
                    $data['creditmemo']['shipping_amount'] = round($data['creditmemo']['shipping_amount'] / $baseToOrderRate, 2);
                }
                if ($data['creditmemo']['adjustment_positive']) {
                    $data['creditmemo']['adjustment_positive'] = round($data['creditmemo']['adjustment_positive'] / $baseToOrderRate, 2);
                }
                if ($data['creditmemo']['adjustment_negative']) {
                    $data['creditmemo']['adjustment_negative'] = round($data['creditmemo']['adjustment_negative'] / $baseToOrderRate, 2);
                }
            }
            /**/
            $invoiceGrandTotal = $invoiceGrandTotal + $data['creditmemo']['shipping_amount'] + $data['creditmemo']['adjustment_positive'];
            if ($data['creditmemo']['adjustment_negative'] > $invoiceGrandTotal) {
                $this->_fault("data_invalid", "The Invoice amount is less than the refund amount. Please try again.");
            }
            if (isset($data['creditmemo']['refund_customerbalance_return_enable'])) {
                $data['creditmemo']['refund_customerbalance_return'] = $invoiceGrandTotal - $data['creditmemo']['adjustment_negative'];
            }
            try {
                $createCreditMemoResult = $this->createCreditMemo($data);

                return $createCreditMemoResult;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                $this->_fault('data_invalid', $msg );
            }

            return false;
        }
        /**
         * Create new creditmemo from order
         *
         * @param array 	$data // Built the with the same structure as the AdminHtml form used on backend.
         * @param integer 	$data[order_id]
         * @param integer 	$data[invoice_id]
         * @param array 	$data[creditmemo][items] // for each of the items on order/invoice
         * @param array 	$data[creditmemo][items][{item_id}] // item_id from sales_flat_order_item
         * @param boolean 	$data[creditmemo][items][{item_id}]['back_to_stock']
         * @param integer 	$data[creditmemo][items][{item_id}]['qty'] // Qty to return
         * @param string 	$data[creditmemo][comment_text]
         * @param string 	$data[creditmemo][comment_customer_notify]
         * @param float 	$data[creditmemo][shipping_amount]
         * @param float 	$data[creditmemo][adjustment_positive]
         * @param float 	$data[creditmemo][adjustment_negative]
         * @param booleam 	$data[creditmemo][do_offline]
         * @param boolean 	$data[creditmemo][send_email]
         * @return array
         */
        private function createCreditMemo($data = array()) {
            $this->tempData = $data;
            $creditmemo = $this->_initCreditmemo();
            if ($creditmemo) {
                if (($creditmemo->getGrandTotal() <= 0) && (!$creditmemo->getAllowZeroGrandTotal())) {
                    Mage::throwException(self::INCORRECT_DATA_CANNOT_CREATE);
                }
                $comment = '';
                if (!empty($data['creditmemo']['comment_text'])) {
                    $creditmemo->addComment($data['creditmemo']['comment_text'], isset($data['creditmemo']['comment_customer_notify']));
                    if (isset($data['creditmemo']['comment_customer_notify'])) {
                        $comment = $data['creditmemo']['comment_text'];
                    }
                }
                if (isset($data['creditmemo']['do_refund'])) {
                    $creditmemo->setRefundRequested(true);
                }
                if ($creditmemo->canRefund()) {
                    if ($creditmemo->getInvoice() && $creditmemo->getInvoice()->getTransactionId()) {
                        $data['creditmemo']['do_offline'] = false;
                    }
                }
                if (isset($data['creditmemo']['do_offline'])) {
                    $creditmemo->setOfflineRequested((bool)(int)$data['creditmemo']['do_offline']);
                }
                try {
                    $creditmemo->register();
                    if (!empty($data['creditmemo']['send_email'])) {
                        $creditmemo->setEmailSent(true);
                    }
                    $creditmemo->getOrder()->setCustomerNoteNotify(!empty($data['creditmemo']['send_email']));
                    $creditmemo->setIsImported(self::IS_IMPORTED_CREDITMEMO_FLAG);
                    $this->_saveCreditmemo($creditmemo);
                    $creditmemo->sendEmail(!empty($data['creditmemo']['send_email']) , $comment);
                    $this->tempData['message'] = array();
                    $this->tempData['message'][0] = array();
                    $this->tempData['message'][0][0] = $creditmemo->getIncrementId();
                    $this->tempData['message'][0][1] = $data['creditmemo']['do_offline'] ;
                    return $this->tempData['message'][0];
                }
                catch(Mage_Core_Exception $e) {
                    $msg = $e->getMessage();
                    if(method_exists($e, 'getCustomMessage')){
                        $msg =  $msg.", custom message:".$e->getCustomMessage();
                    }
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                    Mage::throwException($msg);
                }
            }

            return false;
        }
        private function _saveCreditmemo($creditmemo) {
            try {
                $transactionSave = Mage::getModel('core/resource_transaction')->addObject($creditmemo)->addObject($creditmemo->getOrder());
                if ($creditmemo->getInvoice()) {
                    $transactionSave->addObject($creditmemo->getInvoice());
                }
                $transactionSave->save();

                return;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        /**
         * Initialize creditmemo model instance
         *
         * @return Mage_Sales_Model_Order_Creditmemo
         */
        private function _initCreditmemo() {
            $creditmemo = false;
            if(isset($this->tempData['creditmemo_id']))
                $creditmemoId = $this->tempData['creditmemo_id'];
            $orderId = $this->tempData['order_id'];
            $invoiceId = $this->tempData['invoice_id'];
            try {
                $order = Mage::getModel('sales/order')->load($orderId);
                if (!$order->canCreditmemo()) {
                    if ($invoiceId) {
                        $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)->setOrder($order);
                        $capture_case = 'offline';
                        $invoice->setRequestedCaptureCase($capture_case)->setCanVoidFlag(false)->pay() /*->save()*/;
                        $transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder());
                        $transactionSave->save();
                    }
                    $this->tempData['message'][] = self::UNABLE_TO_CREATE_CREDITMEMO." for order id : ".$orderId;;
                }
            }
            catch(Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
            if (isset($creditmemoId)) {
                $creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId);
            } elseif ($orderId) {
                $data = $this->tempData['creditmemo'];
                $order = Mage::getModel('sales/order')->load($orderId);
                if ($invoiceId) {
                    $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)->setOrder($order);
                }
                if (!$order->canCreditmemo()) {
                    $this->tempData['message'][] = self::CANNOT_CREATE_CREDITMEMO . " " . $order->getIncrementId();
                    Mage::throwException(self::CANNOT_CREATE_CREDITMEMO . " " . $order->getIncrementId());
                }
                if (isset($data['items'])) {
                    $savedData = $data['items'];
                } else {
                    $savedData = array();
                }
                $qtys = array();
                $backToStock = array();

                foreach ($savedData as $orderItemId => $itemData) {
                    if (isset($itemData['qty'])) {
                        $qtys[$orderItemId] = $itemData['qty'];
                    }
                    if (isset($itemData['back_to_stock']) && $itemData['back_to_stock'] == true) {
                        $backToStock[$orderItemId] = true;
                    }
                }
                $data['qtys'] = $qtys;
                try {
                    $service = Mage::getModel('sales/service_order', $order);
                    if ($invoice) {
                        $creditmemo = $service->prepareInvoiceCreditmemo($invoice, $data);
                    } else {
                        $creditmemo = $service->prepareCreditmemo($data);
                    }
                    /**
                     * Process back to stock flags
                     */

                    foreach ($creditmemo->getAllItems() as $creditmemoItem) {
                        $orderItem = $creditmemoItem->getOrderItem();
                        $parentId = $orderItem->getParentItemId();
                        if (isset($backToStock[$orderItem->getId() ])) {
                            $creditmemoItem->setBackToStock(true);
                        } elseif ($orderItem->getParentItem() && isset($backToStock[$parentId]) && $backToStock[$parentId]) {
                            $creditmemoItem->setBackToStock(true);
                        } elseif (empty($savedData)) {
                            $creditmemoItem->setBackToStock(Mage::helper('cataloginventory')->isAutoReturnEnabled());
                        } else {
                            $creditmemoItem->setBackToStock(false);
                        }
                    }
                }
                catch(Mage_Core_Exception $e) {
                    $msg = $e->getMessage();
                    if(method_exists($e, 'getCustomMessage')){
                        $msg =  $msg.", custom message:".$e->getCustomMessage();
                    }
                    Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                    Mage::throwException($msg);
                }
            }
            try {

                foreach ($this->tempData as $index => $value) {
                    Mage::app()->getRequest()->setParam($index, $value);
                }
                $args = array(
                    'creditmemo' => $creditmemo,
                    'request' => Mage::app()->getRequest()
                );
                Mage::dispatchEvent('adminhtml_sales_order_creditmemo_register_before', $args);
                $this->creditmemoDataImport($creditmemo, $this->tempData);

                return $creditmemo;
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        private function creditmemoDataImport($creditmemo, $request) {
            try {
                $input = $request['creditmemo'];
                if (isset($input['refund_customerbalance_return']) && isset($input['refund_customerbalance_return_enable'])) {
                    $enable = $input['refund_customerbalance_return_enable'];
                    $amount = $input['refund_customerbalance_return'];
                    if ($enable && is_numeric($amount)) {
                        $amount = max(0, min($creditmemo->getBaseCustomerBalanceReturnMax() , $amount));
                        if ($amount) {
                            $amount = $creditmemo->getStore()->roundPrice($amount);
                            $creditmemo->setBaseCustomerBalanceTotalRefunded($amount);
                            $amount = $creditmemo->getStore()->roundPrice($amount * $creditmemo->getOrder()->getStoreToOrderRate());
                            $creditmemo->setCustomerBalanceTotalRefunded($amount);
                            //setting flag to make actual refund to customer balance after creditmemo save
                            $creditmemo->setCustomerBalanceRefundFlag(true);
                            $creditmemo->setPaymentRefundDisallowed(true);
                        }
                    }
                }
                if (isset($input['refund_customerbalance']) && $input['refund_customerbalance']) {
                    $creditmemo->setRefundCustomerBalance(true);
                }
                if (isset($input['refund_real_customerbalance']) && $input['refund_real_customerbalance']) {
                    $creditmemo->setRefundRealCustomerBalance(true);
                    $creditmemo->setPaymentRefundDisallowed(true);
                }
            }
            catch(Mage_Core_Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }
        }
        /**
         * Update credit memo information
         *
         * @param string $creditmemoIncrementId
         * @param array credit memo data
         * @return string
         */
        public function update($creditmemoIncrementId, $data) {
            if (!isset($data['is_imported'])) {
                $this->_fault('data_invalid', self::EXPECTED_ARGUMENT_MISSING_MSG);
            }
            if ($data['is_imported'] != 0 && $data['is_imported'] != 1) {
                $this->_fault('data_invalid', self::INVALID_ARGUMENT_VALUE_MSG);
            }
            try {
                $is_imported = $data['is_imported'];
                $creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
                if ($is_imported != $creditmemo->getIsImported()) {
                    $creditmemo->setIsImported($is_imported)
                            ->setAutomaticallyCreated(true)
                            ->save();
                }
            }
            catch(Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                $this->_fault('data_invalid', $msg );
            }

            return $creditmemoIncrementId;
        }
        /**
         * Initialize attributes' mapping
         */
        public function __construct() {
            $this->_attributesMap['creditmemo'] = array(
                'creditmemo_id' => 'entity_id'
            );
            $this->_attributesMap['creditmemo_item'] = array(
                'item_id' => 'entity_id'
            );
            $this->_attributesMap['creditmemo_comment'] = array(
                'comment_id' => 'entity_id'
            );
        }
        /**
         * Retrieve credit memos by filters
         *
         * @param array|null $filter
         * @return array
         */
        public function items($filter = null) {
            $filter = $this->_prepareListFilter($filter);
            try {
                $result = array();
                /** @var $creditmemoModel Mage_Sales_Model_Order_Creditmemo */
                $creditmemoModel = Mage::getModel('sales/order_creditmemo');
                // map field name entity_id to creditmemo_id
                //foreach ($creditmemoModel->getFilteredCollectionItems($filter) as $creditmemo) {
                $collection = $creditmemoModel->getResourceCollection();
                if (is_array($filter)) {

                    foreach ($filter as $field => $value) {
                        $collection->addFieldToFilter($field, $value);
                    }
                }
                //foreach ($creditmemoModel->getResourceCollection()->getFiltered($filter) as $creditmemo) {

                foreach ($collection as $creditmemo) {
                    $result[] = $this->_getAttributes($creditmemo, 'creditmemo');
                }
            }
            catch(Exception $e) {
                $msg = $e->getMessage();
                if(method_exists($e, 'getCustomMessage')){
                    $msg =  $msg.", custom message:".$e->getCustomMessage();
                }
                Mage::helper('celigoconnector/celigologger')->error('errormsg="'.$msg.'"', self::LOG_FILENAME);
                Mage::throwException($msg);
            }

            return $result;
        }
        /**
         * Make filter of appropriate format for list method
         *
         * @param array|null $filter
         * @return array|null
         */
        protected function _prepareListFilter($filter = null) {
            // prepare filter, map field creditmemo_id to entity_id
            if (is_array($filter)) {

                foreach ($filter as $field => $value) {
                    if (isset($this->_attributesMap['creditmemo'][$field])) {
                        $filter[$this->_attributesMap['creditmemo'][$field]] = $value;
                        unset($filter[$field]);
                    }
                }
            }

            return $filter;
        }
        /**
         * Retrieve credit memo information
         *
         * @param string $creditmemoIncrementId
         * @return array
         */
        public function info($creditmemoIncrementId) {
            $creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
            // get credit memo attributes with entity_id' => 'creditmemo_id' mapping
            $result = $this->_getAttributes($creditmemo, 'creditmemo');
            $result['order_increment_id'] = $creditmemo->getOrder()->load($creditmemo->getOrderId())->getIncrementId();
            // items refunded
            $result['items'] = array();

            foreach ($creditmemo->getAllItems() as $item) {
                $result['items'][] = $this->_getAttributes($item, 'creditmemo_item');
            }
            // credit memo comments
            $result['comments'] = array();

            foreach ($creditmemo->getCommentsCollection() as $comment) {
                $result['comments'][] = $this->_getAttributes($comment, 'creditmemo_comment');
            }

            return $result;
        }
        /**
         * Load CreditMemo by IncrementId
         *
         * @param mixed $incrementId
         * @return Mage_Core_Model_Abstract|Mage_Sales_Model_Order_Creditmemo
         */
        protected function _getCreditmemo($incrementId) {
            /** @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
            $creditmemo = Mage::getModel('sales/order_creditmemo')->load($incrementId, 'increment_id');
            if (!$creditmemo->getId()) {
                Mage::throwException('Does not exists');

            }

            return $creditmemo;
        }
    }
}
?>
