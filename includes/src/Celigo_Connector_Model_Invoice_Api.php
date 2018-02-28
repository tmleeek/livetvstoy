<?php
/**
 * Order Invoice api
 *
 * @category   Celigo
 * @package    Celigo_Invoice
 * @author     Kiran
 */
$orderInvoiceApi = Mage::getBaseDir('code') . DS . 'core' . DS . 'Mage' . DS . 'Sales' . DS . 'Model' . DS . 'Order' . DS . 'Invoice' . DS . 'Api.php';
require_once ($orderInvoiceApi);
class Celigo_Connector_Model_Invoice_Api extends Mage_Sales_Model_Order_Invoice_Api
{
	const XML_PATH_NETSUTE_CAPTURE_METHODS 	= 'connector/othersettings/allowedmethods';
    protected $_connectorhelper;
	
    /**
     * Check if the amount captured in NetSuite for the order
     *
     * @param object $order
     * @return boolean
     */
	private function isCapturedInNetSuite($order = '')
	{
		$returnValue = false;
		
		if ($order == '') {
			return $returnValue;
		}
		
		$this->_connectorhelper = Mage::helper('connector');
		
		if(!$order->getPushedToNs()) {
			return $returnValue;
		}
		
		$storeId = $order->getStoreId(); $websiteId = '';
		if (!$this->_connectorhelper->getIsConnectorModuleEnabled($storeId, $websiteId)) {
			return $returnValue;
		}
		
		$orderPaymentMethod = $order->getPayment()->getMethod();
		$allowedMethods = $this->_connectorhelper->getConfigValue(self::XML_PATH_NETSUTE_CAPTURE_METHODS, $storeId, $websiteId);
		if ($allowedMethods == '' || trim($allowedMethods) == ",") {
			return $returnValue;
		} else {
			$allowedMethodsArray = explode(",", $allowedMethods);
			if (is_array($allowedMethodsArray) 
				&& count($allowedMethodsArray) > 0 
				&& in_array($orderPaymentMethod, $allowedMethodsArray)) {
				
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
    public function create($orderIncrementId, $itemsQty, $comment = null, $email = false, $includeComment = false)
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

        /* @var $order Mage_Sales_Model_Order */
        /**
          * Check order existing
          */
        if (!$order->getId()) {
             $this->_fault('order_not_exists');
        }

        /**
         * Check invoice create availability
         */
        if (!$order->canInvoice()) {
             $this->_fault('data_invalid', Mage::helper('sales')->__('Cannot do invoice for order.'));
        }

        $invoice = $order->prepareInvoice($itemsQty);
		
		/* Following two line were added by Kiran to fix the Invoice status issue when created by API / Connector. It applies for the payment gateways that has ability to capture amount online. */
		if($this->isCapturedInNetSuite($order)) {
			$capture_case = 'offline';
			$invoice->setRequestedCaptureCase($capture_case)->setCanVoidFlag(false)->pay()/*->save()*/;
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
            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();

            $invoice->sendEmail($email, ($includeComment ? $comment : ''));
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }

        return $invoice->getIncrementId();
    }
}