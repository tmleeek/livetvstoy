<?php
class Celigo_Connectorplus_Model_Orders_Api extends Mage_Api_Model_Resource_Abstract
{
	const PUSHED_TO_NETSUITE_FLAG 	= 1;
	const ORDER_NOT_CREATED 		= "Order not created. Check the data and try again.";
	const INVALID_REQUEST 			= "Invalid Request";
	const CONNOT_CREATE_CREDITMEMO	= 'Cannot create credit memo';
	const INCORRECT_DATA_CANNOT_CREATE = "Incorrect Data. Creditmemo cannot be created.";
	const PAYMENT_METHOD_MISSING 	= "Please specify Payment Method";
	const SHIPPING_METHOD_MISSING 	= "Please specify a shipping method.";
	const CUSTOMER_ID_EMAIL_MISSING = "Customer Id / Email is missing.";
	const CUSTOMER_NOT_EXISTS 		= "Customer does not Exists";
	const IS_IMPORTED_CREDITMEMO_FLAG 	= 1;
	
	protected $tempData;
	
	private function getDefaultStoreID()
	{
		try {
			return $iDefaultStoreId = Mage::app()
				->getWebsite(true)
				->getDefaultGroup()
				->getDefaultStoreId();
	
		} catch (Exception $e) {
		
			return 0;
		}
	}
	
	public function create($orderData)
	{
		try {
				
			$storeId = 0;
			if (isset($orderData['store']) and is_numeric($orderData['store'])) {
			
				$store = Mage::getModel('core/store')->load($orderData['store']);
				if ($store->getId()) {
					$storeId = $store->getId();
				} else {
					return "Store Id ".$orderData['store']." does not exists";
				}
			}
			
			if ($storeId == 0 || $storeId == "")	{
				$store = Mage::getModel('core/store')->load($this->getDefaultStoreID());
			}
			
			$quote = Mage::getModel('sales/quote')
				->setStoreId($store->getId())->load(null);

			if ($orderData['customer_id']) {
				$customer = Mage::getModel('customer/customer')
					->setWebsiteId($store->getWebsiteId())
					->load($orderData['customer_id']);
					
				$quote->assignCustomer($customer);
				$quote->setCustomer($customer)->setCheckoutMethod('customer');
				
			} elseif ($orderData['customerEmail']) {
			
				$customer = Mage::getModel('customer/customer')
					->setWebsiteId($store->getWebsiteId())
					->loadByEmail($orderData['customerEmail']);
					
				$quote->assignCustomer($customer);
				$quote->setCustomer($customer)->setCheckoutMethod('customer');
				
			} elseif ($orderData['guestCustomerEmail']) {
				$quote->setCustomerEmail($orderData['guestCustomerEmail']);
			} else {
				return self::CUSTOMER_ID_EMAIL_MISSING;
			}
			
			if(!$customer->getId()) {
				return self::CUSTOMER_NOT_EXISTS;
			}

			if(!$orderData['shippingMethod']) {
				return self::SHIPPING_METHOD_MISSING;
			}

			if(!$orderData['paymentMethod']) {			
				return self::PAYMENT_METHOD_MISSING;
			}

			/* Remove existing quote items */
			foreach ($quote->getAllItems() as $item) {
				$item->isDeleted(true);
				if ($item->getHasChildren()) {
					foreach ($item->getChildren() as $child) {
						$child->isDeleted(true);
					}
				}
			}
			
			/* Add products to quote */
			$subTotal = 0;
			$productObj = Mage::getModel('catalog/product');
			foreach ($orderData['products'] as $id => $productinfo) {
				$idBySku = $productObj->getIdBySku($id);
				$product = Mage::getModel('catalog/product')->load($idBySku);
				$productinfo['qty'] = $productinfo['qty'] > 0? $productinfo['qty']: 1;
				$buyInfo = $productinfo;
				$item = $quote->addProduct($product, new Varien_Object($buyInfo));

				if ($productinfo['price']) {
					$item->setPrice($productinfo['price']);
					$item->setCustomPrice($productinfo['price']);
					$item->setOriginalCustomPrice($productinfo['price']);
				}
				
				$subTotal = $subTotal + ($item->getPrice() * $productinfo['qty']);
			}
			
			/* Set Billing Address */
			$address = Mage::getModel("sales/quote_address");
			$address->setData($orderData['billAddress']);
			$address->implodeStreetAddress();
			$address->setEmail($quote->getCustomer()->getEmail());
			$quote->setBillingAddress($address);
			
			/* Set Shipping Address */
			$address = Mage::getModel("sales/quote_address");
			$address->setData($orderData['shipAddress']);
			$address->implodeStreetAddress();
			$address->setCollectShippingRates(true)->setSameAsBilling(0);
			$quote->setShippingAddress($address);
			
			/* Collect totals */
			$quote->collectTotals()->save();
			
			try {
			
				$quote->getShippingAddress()->setSubtotal($subTotal)
					->setBaseSubtotal($subTotal)
					->setGrandTotal($subTotal)
					->setBaseGrandTotal($subTotal);
					
				$quote->save();
			} catch(Exception $e) {
				$this->_fault("data_invalid", $e->getMessage());
			}
			
			### Start :: Add Custom Shipping Price to the Order #######
			if ($orderData['overrideMagentoTotals']) {
				if (isset($orderData['shipTotal']) and $orderData['shipTotal'] >= 0) {
					Mage::getModel('core/session')->setCustomShippingPrice($orderData['shipTotal']);// Temp Session
					if (isset($orderData['shippingTitle']) and $orderData['shippingTitle'] != '') {
						Mage::getModel('core/session')->setCustomShippingTitle($orderData['shippingTitle']);// Temp Session
					}
				}
			}
			### End :: Add Custom Shipping Price to the Order #######
			
			### Start :: Add Custom Discount Price to the Order #######
			if ($orderData['overrideMagentoTotals']) {
				if (isset($orderData['discountTotal']) and $orderData['discountTotal'] > 0) {
					$coupon = $this->createCouponCode($orderData['discountTotal']);
					$couponCode = $coupon->getCouponCode();
					//check Coupon Code Validity
					try {
						$quote->getShippingAddress()->setCollectShippingRates(true);
						$quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
							//->collectTotals()
							->save();
					} catch (Exception $e) {
						$this->_fault("data_invalid", $e->getMessage());
					}
					
				}
			}
			### End :: Add Custom Discount Price to the Order #######
			
			# $quote->getPayment()->importData(array('method' => $orderData['paymentMethod']));
			/* Check if the Shipping method exists */
			$rate = $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates()->getShippingRateByCode($orderData['shippingMethod']);
			if (!$rate) {
				return "Shipping Method: (". $orderData['shippingMethod'] .") not available";
			}
			
			/* Set Shipping method  */
			$quote->getShippingAddress()->setShippingMethod($orderData['shippingMethod']);
            $quote->collectTotals()->save();
			
			/* Set Payment method and import payment data  */
			$quote->getShippingAddress()->setPaymentMethod($orderData['paymentMethod']);
			$payment = $quote->getPayment();
			$paymentData = array('method' => $orderData['paymentMethod']);
            $payment->importData($paymentData);
            $quote->setTotalsCollectedFlag(false)
                    ->collectTotals()
                    ->save();
			
			
			### Start :: Add Custom Tax Rate to the Order #######
			if ($orderData['overrideMagentoTotals']) {
				try {
					$items = $quote->getAllItems();
					$extraAmount = 0;
					foreach($items as $item) { 
						$taxRate = ''; 
						if (isset($orderData['products'][$item->getSku()]['taxRate']))
							$taxRate = $orderData['products'][$item->getSku()]['taxRate'];
						if ($taxRate != '') {
							$curtax = $item->getTaxAmount(); //echo "<br/>"; 
							$item->setTaxPercent($taxRate); 
							$taxAmount = $taxRate * $item->getPrice() * $item->getQty() / 100; // Tax before Discount
							//$taxAmount = $taxRate * (($item->getPrice() * $item->getQty()) - $item->getDiscountAmount())  / 100; //// Tax After Discount
							$item->setTaxAmount($taxAmount); 
							$item->setBaseTaxAmount($taxAmount); 
							$extraAmount = $extraAmount + ($taxAmount - $curtax); 
						}
					}
					
					if (isset($orderData['shippingTaxRate']) and $orderData['shippingTaxRate'] >= 0) {
						$currentShippingTaxAmount = $quote->getShippingAddress()->getShippingTaxAmount();
						
						$shippingAmount = $quote->getShippingAddress()->getShippingAmount();
						$shippingTaxAmount = ($shippingAmount * $orderData['shippingTaxRate'] ) / 100;
						$quote->getShippingAddress()->setShippingTaxAmount($shippingTaxAmount);
						$quote->getShippingAddress()->setBaseShippingTaxAmount($shippingTaxAmount);
						$extraAmount = $extraAmount + $shippingTaxAmount - $currentShippingTaxAmount;
						
					}
					
					if ($extraAmount != 0) {
						$quote->getShippingAddress()->setTaxAmount($quote->getShippingAddress()->getTaxAmount() + $extraAmount);
						$quote->getShippingAddress()->setBaseTaxAmount($quote->getShippingAddress()->getBaseTaxAmount() + $extraAmount);
			
						$quote->getShippingAddress()->setGrandTotal($quote->getShippingAddress()->getGrandTotal() + $extraAmount);
						$quote->getShippingAddress()->setBaseGrandTotal($quote->getShippingAddress()->getBaseGrandTotal() + $extraAmount);
						$quote->collectTotals()->save();
					}
				} catch (Exception $e) {
					$this->_fault('data_invalid', $e->getMessage());
				}
			}
			### End :: Add Custom Tax Rate to the Order #######
			
			$service = Mage::getModel('sales/service_quote', $quote);
			$service->submitAll();
			$order = $service->getOrder();
			
			foreach ($quote->getAllItems() as $item) {
				$item->isDeleted(true);
				if ($item->getHasChildren()) {
					foreach ($item->getChildren() as $child) {
						$child->isDeleted(true);
					}
				}
			}
			
			$quote->collectTotals()->save();

			/* Clear temporary sessions */
			Mage::getModel('core/session')->setCustomShippingPrice('');
			Mage::getModel('core/session')->setCustomShippingTitle('');
			
			/* Set push to NetSuite flag */
			$order->setPushedToNs(self::PUSHED_TO_NETSUITE_FLAG)->save();
			
			/* Inactivate the applied coupon code */
			$couponCode = $order->getCouponCode();
			if(isset($couponCode) && trim($couponCode) != "") {
				if(isset($coupon) && $coupon->getId()) { 
					$coupon->setIsActive(0)->save();
				}
			}
			
			return $order->getIncrementId();

		} catch (Mage_Core_Exception $e) {
			$this->_fault('data_invalid', $e->getMessage());
		} catch (Exception $e) {
			$this->_fault('data_invalid', $e->getMessage());
		}
		return self::ORDER_NOT_CREATED;
	}

	public function update($orderId, $data)
	{
		if ($orderId != "creditMemo") {
			return self::INVALID_REQUEST;
		}

		try {
			if (!isset($data['creditmemo']['do_offline'])) {
				$data['creditmemo']['do_offline'] = true;
			}

			if (!isset($data['order_id']) && isset($data['invoice_id'])) {

				$_invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($data['invoice_id']);
				if (!$_invoice->getId()) {
					return "Invoice Number: ".$data['invoice_id']." Does not Exists";
				}
				$_order = Mage::getModel('sales/order')->load($_invoice->getOrderId());
				if (!$_order->getId()) {
					return "Order does not exists for Invoice Id: ".$data['invoice_id'];
				}
				$data['order_id'] = $_order->getIncrementId();
			}

			$orderId = '';
			$order = Mage::getModel('sales/order');
			$order->loadByIncrementId($data['order_id']);
			if (!$order->getId()) {
				return "Order Number: ".$data['order_id']." Does not Exists";
			}
			$orderId = $order->getId();
			$invoiceId = ''; $invoiceGrandTotal = 0;
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
				return "Invoice Number ".$data['invoice_id']." for Order Number: ".$data['order_id']." Does not Exists";
			}

			if ($orderId != '' && $invoiceId != '') {
				$data['order_id'] = $orderId;
				$data['invoice_id'] = $invoiceId;
			} else {
				return "Invoice Number ".$data['invoice_id']." for Order Number: ".$data['order_id']." Does not Exists";
			}
			$invoiceGrandTotal = $invoiceGrandTotal + $data['creditmemo']['shipping_amount'] + $data['creditmemo']['adjustment_positive'];
			if ($data['creditmemo']['adjustment_negative'] > $invoiceGrandTotal) {
				return "The Invoice amount is less than the refund amount. Please try again.";
			}
			if ($data['creditmemo']['refund_customerbalance_return_enable']) {
				$data['creditmemo']['refund_customerbalance_return'] = $invoiceGrandTotal - $data['creditmemo']['adjustment_negative'];
			}

			return $this->createCreditMemo($data);
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
    }

	public function delete($orderId)
	{
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
		if (!$orderId = $order->getId()) {
			return $this->_fault('not_exists');
		}

		try {
			$resource = Mage::getSingleton('core/resource');
			$write = $resource->getConnection('core_write');

			$table_sales_flat_order = $resource->getTableName('sales/order');
			$query1 = "DELETE FROM {$table_sales_flat_order} WHERE entity_id = ".$orderId;
			$write->query($query1);


			$table_sales_order_tax = $resource->getTableName('sales/order_tax');
			$query2 = "DELETE FROM {$table_sales_order_tax} WHERE order_id = ".$orderId;
			$write->query($query2);
			
			$table_downloadable_link_purchased = $resource->getTableName('downloadable/link_purchased');
			$query3 = "DELETE FROM {$table_downloadable_link_purchased} WHERE order_id = ".$orderId;
			$write->query($query3);
			return true;
			
		} catch (Mage_Core_Exception $e) {
			$this->_fault('not_deleted', $e->getMessage());
		}
		return false;
	}

    /**
     * Create creditmemo
     * We can save only new creditmemo. Existing creditmemos are not editable
     */

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

    private function createCreditMemo($data = array())
    {
		$this->tempData = $data;

        try {
			$creditmemo = $this->_initCreditmemo();
			if ($creditmemo){
				if (($creditmemo->getGrandTotal() <=0) && (!$creditmemo->getAllowZeroGrandTotal())) {
					$this->_fault('credit_total', self::INCORRECT_DATA_CANNOT_CREATE);
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

                $creditmemo->register();
                if (!empty($data['creditmemo']['send_email'])) {
                    $creditmemo->setEmailSent(true);
                }

                $creditmemo->getOrder()->setCustomerNoteNotify(!empty($data['creditmemo']['send_email']));
                $creditmemo->setIsImported(self::IS_IMPORTED_CREDITMEMO_FLAG);
                $this->_saveCreditmemo($creditmemo);
                $creditmemo->sendEmail(!empty($data['creditmemo']['send_email']), $comment);
				$this->tempData['message'][] = $creditmemo->getIncrementId();				
            }
        } catch (Mage_Core_Exception $e) {
            Mage::throwException($e->getMessage());
        }
		return $this->tempData['message'][0];
    }

    private function _saveCreditmemo($creditmemo)
    {
		try {
			$transactionSave = Mage::getModel('core/resource_transaction')
				->addObject($creditmemo)
				->addObject($creditmemo->getOrder());
			if ($creditmemo->getInvoice()) {
				$transactionSave->addObject($creditmemo->getInvoice());
			}
			$transactionSave->save();

			return;
        } catch (Mage_Core_Exception $e) {
            Mage::throwException($e->getMessage());
        }
    }

    /**
     * Initialize creditmemo model instance
     *
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    private function _initCreditmemo($update = false)
    {
        try {
			$creditmemo = false;
			$creditmemoId = $this->tempData['creditmemo_id'];
			$orderId = $this->tempData['order_id'];
			$invoiceId = $this->tempData['invoice_id'];
			
			try {
				$order  = Mage::getModel('sales/order')->load($orderId);
				if (!$order->canCreditmemo()) {
					if ($invoiceId) {
						$invoice = Mage::getModel('sales/order_invoice')
							->load($invoiceId)
							->setOrder($order);

						$capture_case = 'offline';
						$invoice->setRequestedCaptureCase($capture_case)->setCanVoidFlag(false)->pay()/*->save()*/;
		
						$transactionSave = Mage::getModel('core/resource_transaction')
							->addObject($invoice)
							->addObject($invoice->getOrder());
						$transactionSave->save();
					}
					$this->tempData['message'][] = self::CONNOT_CREATE_CREDITMEMO;
					return false;
				}
			} catch (Exception $e) {
				Mage::throwException($e->getMessage());
			}

			if ($creditmemoId) {
				$creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId);
			} elseif ($orderId) {
				$data   = $this->tempData['creditmemo'];
				$order  = Mage::getModel('sales/order')->load($orderId);
				if ($invoiceId) {
					$invoice = Mage::getModel('sales/order_invoice')
						->load($invoiceId)
						->setOrder($order);
				}
				if (!$order->canCreditmemo()) {
					$this->tempData['message'][] = self::CONNOT_CREATE_CREDITMEMO;
					Mage::throwException(self::CONNOT_CREATE_CREDITMEMO);
					return false;
				}

				if(isset($data['items'])) {$savedData = $data['items'];} else { $savedData = array();}

				$qtys = array();
				$backToStock = array();
				foreach ($savedData as $orderItemId =>$itemData) {
					if (isset($itemData['qty'])) {
						$qtys[$orderItemId] = $itemData['qty'];
					}
					if (isset($itemData['back_to_stock']) && $itemData['back_to_stock'] == true) {
						$backToStock[$orderItemId] = true;
					}
				}
				$data['qtys'] = $qtys;

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
					if (isset($backToStock[$orderItem->getId()])) {
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
			foreach($this->tempData as $index => $value) {
				Mage::app()->getRequest()->setParam($index, $value);
			}
			$args = array('creditmemo' => $creditmemo, 'request' => Mage::app()->getRequest());
			Mage::dispatchEvent('adminhtml_sales_order_creditmemo_register_before', $args);
			$this->creditmemoDataImport($creditmemo,$this->tempData);
			return $creditmemo;
        } catch (Mage_Core_Exception $e) {
            Mage::throwException($e->getMessage());
        }
    }

    private function creditmemoDataImport($creditmemo,$request)
    {
		try {
			$input = $request['creditmemo'];

			if (isset($input['refund_customerbalance_return']) && isset($input['refund_customerbalance_return_enable'])) {
				$enable = $input['refund_customerbalance_return_enable'];
				$amount = $input['refund_customerbalance_return'];
				if ($enable && is_numeric($amount)) {
					$amount = max(0, min($creditmemo->getBaseCustomerBalanceReturnMax(), $amount));
					if ($amount) {
						$amount = $creditmemo->getStore()->roundPrice($amount);
						$creditmemo->setBaseCustomerBalanceTotalRefunded($amount);

						$amount = $creditmemo->getStore()->roundPrice(
							$amount*$creditmemo->getOrder()->getStoreToOrderRate()
						);
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
        } catch (Mage_Core_Exception $e) {
            Mage::throwException($e->getMessage());
        }
	}
	
	### Start :: Following functions to create a coupon code on the fly and returns the code #####
	private function createCouponCode($discountAmount)
	{
		$couponCode = $this->generateCouponCode(16);
		$data = array(
			'product_ids' => null,
			'name' => "Discount",
			'description' => null,
			'is_active' => 1,
			'website_ids' => $this->getAllWebSiteIdsArray(),
			'customer_group_ids' => $this->getAllCustomerGroupIdsArray(),
			'coupon_type' => 2,
			'coupon_code' => $couponCode,
			'uses_per_coupon' => 1,
			'uses_per_customer' => 1,
			'from_date' => null,
			'to_date' => null,
			'sort_order' => null,
			'is_rss' => 1,
			'rule' => array(
				'conditions' => array(
					array(
						'type' => 'salesrule/rule_condition_combine',
						'aggregator' => 'all',
						'value' => 1,
						'new_child' => null
					)
				)
			),
			'simple_action' => 'cart_fixed',
			'discount_amount' => $discountAmount,
			'discount_qty' => 0,
			'discount_step' => null,
			'apply_to_shipping' => 0,
			'simple_free_shipping' => 0,
			'stop_rules_processing' => 0,
			'rule' => array(
				'actions' => array(
					array(
						'type' => 'salesrule/rule_condition_product_combine',
						'aggregator' => 'all',
						'value' => 1,
						'new_child' => null
					)
				)
			),
			'store_labels' => array($couponCode)
		);
		 
		$model = Mage::getModel('salesrule/rule');
		//$data = $this->_filterDates($data, array('from_date', 'to_date'));
		 
		$validateResult = $model->validateData(new Varien_Object($data));
		 
		if ($validateResult == true) {
		 
			if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent'
					&& isset($data['discount_amount'])) {
				$data['discount_amount'] = min(100, $data['discount_amount']);
			}
		 
			if (isset($data['rule']['conditions'])) {
				$data['conditions'] = $data['rule']['conditions'];
			}
		 
			if (isset($data['rule']['actions'])) {
				$data['actions'] = $data['rule']['actions'];
			}
		 
			unset($data['rule']);
		 
			$model->loadPost($data);
		 
			$model->save();
			return $model; //->getCouponCode();
		}
	}
	
	private function getAllWebSiteIdsArray()
	{
		$website_ids = array();
		$webSites = Mage::app()->getWebsites();
		if(is_array($webSites) && count($webSites) > 0) {
			foreach ($webSites as $website) {
				$website_ids[] = $website->getWebsiteId();
			}
		}
		return $website_ids;
	}
	
	private function getAllCustomerGroupIdsArray()
	{
		$customerGroups = array();
		$customer_group = new Mage_Customer_Model_Group();
		$allGroups  = $customer_group->getCollection()->toOptionHash();
		foreach ($allGroups as $key=>$allGroup){
			  $customerGroups[$key] = $key; //array('value'=>$allGroup,'label'=>$allGroup);
		}
		return $customerGroups;
	}	

	private function generateCouponCode($couponlength = 16, $prefix = 'Celigo_')
	{
		$coupon_code = Mage::helper('core')->getRandomString($couponlength);
		
		if($prefix != '') {
			$coupon_code = $prefix . $coupon_code;
		}

		$collection = Mage::getModel('salesrule/rule')->getCollection()->addFieldToFilter('code', $coupon_code);
		if ($collection->count() == 0) { 
			return $coupon_code;
		} else {
			return $this->generateCouponCode($couponlength, $couponprefix);
		}
	}	
	### End :: Following functions to create a coupon code on the fly and returns the code #####
}
?>