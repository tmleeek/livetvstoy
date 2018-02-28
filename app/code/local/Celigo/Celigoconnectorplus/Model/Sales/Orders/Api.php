<?php

class Celigo_Celigoconnectorplus_Model_Sales_Orders_Api extends Mage_Api_Model_Resource_Abstract {
    const PUSHED_TO_NETSUITE_FLAG = 1;
    const ORDER_NOT_CREATED = "Unable to create Order. Please verify the data and try again.";
    const UNABLE_TO_CREATE_CREDITMEMO = 'Unable to create Credit Memo.';
    const INCORRECT_DATA_CANNOT_CREATE = "Unable to create Credit Memo. Invalid data.";
    const PAYMENT_METHOD_MISSING = "Invalid API Call. Payment Method is missing.";
    const SHIPPING_METHOD_MISSING = "Invalid API Call. Shipping Method is missing.";
    const CUSTOMER_ID_EMAIL_MISSING = "Invalid API Call. Customer Id or Email is missing.";
    const CUSTOMER_NOT_EXISTS = "Invalid API Call. Customer does not exist";
    const IS_IMPORTED_CREDITMEMO_FLAG = 1;
    const CREDITMEMO_INVALID_FIRST_PARAM = 'Invalid API Call. First Argument to this API must be the String "creditMemo".';
    const BUNDLE_PRODUCTS_NOT_SUPPORTED = 'Bundle product not supported.';
    protected $tempData;
    private function getDefaultStoreID() {
        try {
            
            return $iDefaultStoreId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        }
        catch(Exception $e) {
            
            return 0;
        }
    }
    public function create($orderData) {
        $storeId = 0;
        if (isset($orderData['store']) and is_numeric($orderData['store'])) {
            $store = Mage::getModel('core/store')->load($orderData['store']);
            if ($store->getId()) {
                $storeId = $store->getId();
            } else {
                $this->_fault('data_invalid', "Store Id " . $orderData['store'] . " does not exist");
            }
        }
        if ($storeId == 0 || $storeId == "") {
            $store = Mage::getModel('core/store')->load($this->getDefaultStoreID());
        }
        $quote = Mage::getModel('sales/quote')->setStoreId($store->getId())->setIsActive(false)->setIsMultiShipping(false)->load(null);
        if ($orderData['customer_id']) {
            $customer = Mage::getModel('customer/customer')->setWebsiteId($store->getWebsiteId())->load($orderData['customer_id']);
            $quote->assignCustomer($customer);
            $quote->setCustomer($customer)->setCheckoutMethod('customer');
        } elseif ($orderData['customerEmail']) {
            $customer = Mage::getModel('customer/customer')->setWebsiteId($store->getWebsiteId())->loadByEmail($orderData['customerEmail']);
            $quote->assignCustomer($customer);
            $quote->setCustomer($customer)->setCheckoutMethod('customer');
        } elseif ($orderData['guestCustomerEmail']) {
            $quote->setCustomerEmail($orderData['guestCustomerEmail']);
        } else {
            $this->_fault('data_invalid', self::CUSTOMER_ID_EMAIL_MISSING);
        }
        if (!$customer->getId()) {
            $this->_fault('data_invalid', self::CUSTOMER_NOT_EXISTS);
        }
        if (!$orderData['shippingMethod']) {
            $this->_fault('data_invalid', self::SHIPPING_METHOD_MISSING);
        }
        if (!$orderData['paymentMethod']) {
            $this->_fault('data_invalid', self::PAYMENT_METHOD_MISSING);
        }
        /* Add products to quote */
        $subTotal = 0;
        $productObj = Mage::getModel('catalog/product');
        
        foreach ($orderData['products'] as $id => $productinfo) {
            $idBySku = $productObj->getIdBySku($id);
            if (!$idBySku) {
                $this->_fault('data_invalid', "Product with SKU '" . $id . "' does not exist");
            }
            $product = Mage::helper('catalog/product')->getProduct($idBySku, $quote->getStoreId(), 'id');
            $productinfo['qty'] = $productinfo['qty'] > 0 ? $productinfo['qty'] : 1;
            $buyInfo = $productinfo;
            try {
                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                    Mage::throwException(self::BUNDLE_PRODUCTS_NOT_SUPPORTED);
                }
                $item = $quote->addProduct($product, new Varien_Object($buyInfo));
                if (is_string($item)) {
                    Mage::throwException($item);
                }
            } catch (Mage_Core_Exception $e) {
                 $this->_fault('data_invalid', $e->getMessage() . " (SKU = $id)");
            }
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
        //$address->implodeStreetAddress();
        $address->setEmail($quote->getCustomer()->getEmail());
        $quote->setBillingAddress($address);
        /* Set Shipping Address */
        $address = Mage::getModel("sales/quote_address");
        $address->setData($orderData['shipAddress']);
        //$address->implodeStreetAddress();
        $address->setCollectShippingRates(true)->setSameAsBilling(0);
        $quote->setShippingAddress($address);
        $quote->collectTotals();
        $quote->getShippingAddress()->setSubtotal($subTotal)->setBaseSubtotal($subTotal)->setGrandTotal($subTotal)->setBaseGrandTotal($subTotal);
        //## Start :: Add Custom Shipping Price to the Order #######
        if ($orderData['overrideMagentoTotals']) {
            if (isset($orderData['shipTotal']) and $orderData['shipTotal'] >= 0) {
                Mage::getModel('core/session')->setCustomShippingPrice($orderData['shipTotal']); // Temp Session
                if (isset($orderData['shippingTitle']) and $orderData['shippingTitle'] != '') {
                    Mage::getModel('core/session')->setCustomShippingTitle($orderData['shippingTitle']); // Temp Session
                    
                }
            }
        }
        //## End :: Add Custom Shipping Price to the Order #######
        //## Start :: Add Custom Discount Price to the Order #######
        if ($orderData['overrideMagentoTotals']) {
            if (isset($orderData['discountTotal']) and $orderData['discountTotal'] > 0) {
                $coupon = $this->createCouponCode($orderData['discountTotal']);
                $couponCode = $coupon->getCouponCode();
                //check Coupon Code Validity
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote->setCouponCode(strlen($couponCode) ? $couponCode : '');
            }
        }
        //## End :: Add Custom Discount Price to the Order #######
        /* Check if the Shipping method exists */
        $rate = $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates()->getShippingRateByCode($orderData['shippingMethod']);
        if (!$rate) {
            $this->_fault("data_invalid", "Shipping Method: (" . $orderData['shippingMethod'] . ") not available");
        }
        /* Set Shipping method  */
        $quote->getShippingAddress()->setShippingMethod($orderData['shippingMethod']);
        /* Set Payment method and import payment data  */
        $quote->getShippingAddress()->setPaymentMethod($orderData['paymentMethod']);
        $payment = $quote->getPayment();
        $paymentData = array(
            'method' => $orderData['paymentMethod']
        );
        $payment->importData($paymentData);
        //## Start :: Add Custom Tax Rate to the Order #######
        if ($orderData['overrideMagentoTotals']) {
            $items = $quote->getAllItems();
            $extraAmount = 0;
            
            foreach ($items as $item) {
                $taxRate = '';
                if (isset($orderData['products'][$item->getSku() ]['taxRate'])) $taxRate = $orderData['products'][$item->getSku() ]['taxRate'];
                if ($taxRate != '') {
                    $curtax = $item->getTaxAmount();
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
                $shippingTaxAmount = ($shippingAmount * $orderData['shippingTaxRate']) / 100;
                $quote->getShippingAddress()->setShippingTaxAmount($shippingTaxAmount);
                $quote->getShippingAddress()->setBaseShippingTaxAmount($shippingTaxAmount);
                $extraAmount = $extraAmount + $shippingTaxAmount - $currentShippingTaxAmount;
            }
            if ($extraAmount != 0) {
                $quote->getShippingAddress()->setTaxAmount($quote->getShippingAddress()->getTaxAmount() + $extraAmount);
                $quote->getShippingAddress()->setBaseTaxAmount($quote->getShippingAddress()->getBaseTaxAmount() + $extraAmount);
                $quote->getShippingAddress()->setGrandTotal($quote->getShippingAddress()->getGrandTotal() + $extraAmount);
                $quote->getShippingAddress()->setBaseGrandTotal($quote->getShippingAddress()->getBaseGrandTotal() + $extraAmount);
            }
        }
        //## End :: Add Custom Tax Rate to the Order #######
        $quote->setTotalsCollectedFlag(false)->collectTotals()->save();
        try {
            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();
            $order = $service->getOrder();
            
            /* Clear temporary sessions */
            Mage::getModel('core/session')->setCustomShippingPrice('');
            Mage::getModel('core/session')->setCustomShippingTitle('');
            /* Set push to NetSuite flag */
            $order->setPushedToNs(self::PUSHED_TO_NETSUITE_FLAG)->save();
            /* Inactivate the applied coupon code */
            $couponCode = $order->getCouponCode();
            if (isset($couponCode) && trim($couponCode) != "") {
                if (isset($coupon) && $coupon->getId()) {
                    $coupon->setIsActive(0)->save();
                }
            }
            
            return $order->getIncrementId();
        }
        catch(Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        catch(Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        $this->_fault('not_created', self::ORDER_NOT_CREATED);
    }
    public function update($flag, $data) {
        if ($flag != "creditMemo") {
            $this->_fault("data_invalid", self::CREDITMEMO_INVALID_FIRST_PARAM);
        }
        $creditMemoApiModel = Mage::getModel('celigoconnectorplus/sales_order_creditmemo_api');
        
        return $creditMemoApiModel->create($data);
    }
    public function delete($orderId) {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        if (!$orderId = $order->getId()) {
            $this->_fault('not_exists');
        }
        try {
            $resource = Mage::getSingleton('core/resource');
            $write = $resource->getConnection('core_write');
            $table_sales_flat_order = $resource->getTableName('sales/order');
            $query1 = "DELETE FROM {$table_sales_flat_order} WHERE entity_id = " . $orderId;
            $write->query($query1);
            $table_sales_order_tax = $resource->getTableName('sales/order_tax');
            $query2 = "DELETE FROM {$table_sales_order_tax} WHERE order_id = " . $orderId;
            $write->query($query2);
            $table_downloadable_link_purchased = $resource->getTableName('downloadable/link_purchased');
            $query3 = "DELETE FROM {$table_downloadable_link_purchased} WHERE order_id = " . $orderId;
            $write->query($query3);
            
            return true;
        }
        catch(Mage_Core_Exception $e) {
            $this->_fault('not_deleted', $e->getMessage());
        }
        
        return false;
    }
    private function createCouponCode($discountAmount) {
        $couponCode = $this->generateCouponCode(16);
        $data = array(
            'product_ids' => null,
            'name' => "Discount",
            'description' => null,
            'is_active' => 1,
            'website_ids' => $this->getAllWebSiteIdsArray() ,
            'customer_group_ids' => $this->getAllCustomerGroupIdsArray() ,
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
            ) ,
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
            ) ,
            'store_labels' => array(
                $couponCode
            )
        );
        $model = Mage::getModel('salesrule/rule');
        //$data = $this->_filterDates($data, array('from_date', 'to_date'));
        $validateResult = $model->validateData(new Varien_Object($data));
        if ($validateResult == true) {
            if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent' && isset($data['discount_amount'])) {
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
    private function getAllWebSiteIdsArray() {
        $website_ids = array();
        $webSites = Mage::app()->getWebsites();
        if (is_array($webSites) && count($webSites) > 0) {
            
            foreach ($webSites as $website) {
                $website_ids[] = $website->getWebsiteId();
            }
        }
        
        return $website_ids;
    }
    private function getAllCustomerGroupIdsArray() {
        $customerGroups = array();
        $customer_group = new Mage_Customer_Model_Group();
        $allGroups = $customer_group->getCollection()->toOptionHash();
        
        foreach ($allGroups as $key => $allGroup) {
            $customerGroups[$key] = $key; //array('value'=>$allGroup,'label'=>$allGroup);
            
        }
        
        return $customerGroups;
    }
    private function generateCouponCode($couponlength = 16, $prefix = 'Celigo_') {
        $coupon_code = Mage::helper('core')->getRandomString($couponlength);
        if ($prefix != '') {
            $coupon_code = $prefix . $coupon_code;
        }
        $collection = Mage::getModel('salesrule/rule')->getCollection()->addFieldToFilter('code', $coupon_code);
        if ($collection->count() == 0) {
            
            return $coupon_code;
        } else {
            
            return $this->generateCouponCode($couponlength, $prefix);
        }
    }
}
?>
