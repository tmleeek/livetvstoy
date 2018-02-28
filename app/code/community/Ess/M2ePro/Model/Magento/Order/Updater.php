<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  2011-2015 ESS-UA [M2E Pro]
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_Magento_Order_Updater
{
    // M2ePro_TRANSLATIONS
    // Cancel is not allowed for Orders which were already Canceled.
    // Cancel is not allowed for Orders with Invoiced Items.
    // Cancel is not allowed for Orders which were put on Hold.
    // Cancel is not allowed for Orders which were Completed or Closed.

    //########################################

    /** @var $magentoOrder Mage_Sales_Model_Order */
    private $magentoOrder = NULL;

    private $needSave = false;

    //########################################

    /**
     * Set magento order for updating
     *
     * @param Mage_Sales_Model_Order $order
     * @return Ess_M2ePro_Model_Magento_Order_Updater
     */
    public function setMagentoOrder(Mage_Sales_Model_Order $order)
    {
        $this->magentoOrder = $order;

        return $this;
    }

    //########################################

    private function getMagentoCustomer()
    {
        if ($this->magentoOrder->getCustomerIsGuest()) {
            return null;
        }

        $customer = $this->magentoOrder->getCustomer();
        if ($customer instanceof Varien_Object && $customer->getId()) {
            return $customer;
        }

        $customer = Mage::getModel('customer/customer')->load($this->magentoOrder->getCustomerId());
        if ($customer->getId()) {
            $this->magentoOrder->setCustomer($customer);
        }

        return $customer->getId() ? $customer : null;
    }

    //########################################

    /**
     * Update shipping address
     *
     * @param array $addressInfo
     */
    public function updateShippingAddress(array $addressInfo)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        $shippingAddress = $this->magentoOrder->getShippingAddress();
        if ($shippingAddress instanceof Mage_Sales_Model_Order_Address) {
            $shippingAddress->addData($addressInfo);
            $shippingAddress->implodeStreetAddress()->save();
        } else {
            /** @var $shippingAddress Mage_Sales_Model_Order_Address */
            $shippingAddress = Mage::getModel('sales/order_address');
            $shippingAddress->setCustomerId($this->magentoOrder->getCustomerId());
            $shippingAddress->addData($addressInfo);
            $shippingAddress->implodeStreetAddress();

            // we need to set shipping address to order before address save to init parent_id field
            $this->magentoOrder->setShippingAddress($shippingAddress);
            $shippingAddress->save();
        }

        // we need to save order to update data in table sales_flat_order_grid
        // setData method will force magento model to save entity
        $this->magentoOrder->setForceUpdateGridRecords(false);
        $this->needSave = true;
    }

    public function updateShippingDescription($shippingDescription)
    {
        $this->magentoOrder->setData('shipping_description', $shippingDescription);
        $this->needSave = true;
    }

    //########################################

    /**
     * Update customer email
     *
     * @param $email
     * @return null
     */
    public function updateCustomerEmail($email)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return;
        }

        if ($this->magentoOrder->getCustomerEmail() != $email) {
            $this->magentoOrder->setCustomerEmail($email);
            $this->needSave = true;
        }

        if (!$this->magentoOrder->getCustomerIsGuest()) {
            $customer = $this->getMagentoCustomer();

            if (is_null($customer)) {
                return;
            }

            if (strpos($customer->getEmail(), Ess_M2ePro_Model_Magento_Customer::FAKE_EMAIL_POSTFIX) === false) {
                return;
            }

            $customer->setEmail($email)->save();
        }
    }

    /**
     * Update customer address
     *
     * @param array $customerAddress
     */
    public function updateCustomerAddress(array $customerAddress)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        if ($this->magentoOrder->getCustomerIsGuest()) {
            return;
        }

        $customer = $this->getMagentoCustomer();

        if (is_null($customer)) {
            return;
        }

        /** @var $customerAddress Mage_Customer_Model_Address */
        $customerAddress = Mage::getModel('customer/address')
            ->setData($customerAddress)
            ->setCustomerId($customer->getId())
            ->setIsDefaultBilling(false)
            ->setIsDefaultShipping(false);
        $customerAddress->implodeStreetAddress();
        $customerAddress->save();
    }

    //########################################

    /**
     * Update payment data (payment method, transactions, etc)
     *
     * @param array $newPaymentData
     */
    public function updatePaymentData(array $newPaymentData)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        $payment = $this->magentoOrder->getPayment();

        if ($payment instanceof Mage_Sales_Model_Order_Payment) {
            $payment->setAdditionalData(serialize($newPaymentData))->save();
        }
    }

    //########################################

    /**
     * Add notes
     *
     * @param mixed $comments
     * @return null
     */
    public function updateComments($comments)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        if (empty($comments)) {
            return;
        }

        !is_array($comments) && $comments = array($comments);

        $header = '<br/><b><u>' . Mage::helper('M2ePro')->__('M2E Pro Notes') . ':</u></b><br/><br/>';
        $comments = implode('<br/><br/>', $comments);

        $this->magentoOrder->addStatusHistoryComment($header . $comments);
        $this->needSave = true;
    }

    //########################################

    /**
     * Update status
     *
     * @param $status
     * @return null
     */
    public function updateStatus($status)
    {
        if ($this->magentoOrder->isCanceled()) {
            return;
        }

        if ($status == '') {
            return;
        }

        if ($this->magentoOrder->getState() == Mage_Sales_Model_Order::STATE_COMPLETE
            || $this->magentoOrder->getState() == Mage_Sales_Model_Order::STATE_CLOSED
        ) {
            $this->magentoOrder->setStatus($status);
        } else {
            $this->magentoOrder->setState(Mage_Sales_Model_Order::STATE_PROCESSING, $status);
        }

        $this->needSave = true;
    }

    //########################################

    public function cancel()
    {
        $this->magentoOrder->setActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_CANCEL, true);
        $this->magentoOrder->setActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_UNHOLD, true);

        if ($this->magentoOrder->isCanceled()) {
            //throw new Ess_M2ePro_Model_Exception('Cancel is not allowed for Orders which were already Canceled.');
            return;
        }

        if ($this->magentoOrder->canUnhold()) {
            throw new Ess_M2ePro_Model_Exception('Cancel is not allowed for Orders which were put on Hold.');
        }

        if ($this->magentoOrder->getState() === Mage_Sales_Model_Order::STATE_COMPLETE ||
            $this->magentoOrder->getState() === Mage_Sales_Model_Order::STATE_CLOSED) {
            throw new Ess_M2ePro_Model_Exception('Cancel is not allowed for Orders which were Completed or Closed.');
        }

        $allInvoiced = true;
        foreach ($this->magentoOrder->getAllItems() as $item) {
            if ($item->getQtyToInvoice()) {
                $allInvoiced = false;
                break;
            }
        }
        if ($allInvoiced) {
            throw new Ess_M2ePro_Model_Exception('Cancel is not allowed for Orders with Invoiced Items.');
        }

        $this->magentoOrder->cancel()->save();
    }

    //########################################

    /**
     * Save magento order only once and only if it's needed
     */
    public function finishUpdate()
    {
        if ($this->needSave) {
            $this->magentoOrder->save();
        }
    }
    //########################################
    public function amazon_custom($magento_order_id)
    {
           Mage::log('new function amazon_custom'.$magento_order_id, null, 'amazon_custom.log');
            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            
            /* Fetch amazon ordr id   */
            //$magento_order_id = 559891;
            $query = 'SELECT mo.magento_order_id,mao.amazon_order_id,mo.create_date FROM `m2epro_order` AS mo, m2epro_amazon_order AS mao WHERE mo.id=mao.order_id AND magento_order_id='.$magento_order_id;
            $results = $writeConnection->fetchAll($query);
            $amazon_order_id = $results[0]['amazon_order_id'];
            
            define ("AWS_ACCESS_KEY_ID", "AKIAJ3HVYDOS3J3NSLUA");
            define ("MERCHANT_ID", "A6N9USJK1HBM9");
            define ("MARKETPLACE_ID", "ATVPDKIKX0DER");
            define ("AWS_SECRET_ACCESS_KEY","f3Y86sS5HL6EwQ3qfBi8BGtuU4XHcw6nHxsVAC/5");
            $param = array();
            $param['AWSAccessKeyId']   = AWS_ACCESS_KEY_ID; 
            $param['Action']           = 'ListOrderItems'; 
            $param['SellerId']         = MERCHANT_ID; 
            $param['SignatureMethod']  = 'HmacSHA256'; 
            $param['SignatureVersion'] = '2'; 
            $param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
            $param['Version']          = '2013-09-01'; 
            $param['MarketplaceId']    = MARKETPLACE_ID; 
            $param['AmazonOrderId']    = $amazon_order_id;
            //$param['AmazonOrderId']    = '112-7819723-7137051';
            $url = array();
            foreach ($param as $key => $val) {
            
                $key = str_replace("%7E", "~", rawurlencode($key));
                $val = str_replace("%7E", "~", rawurlencode($val));
            
                $url[] = "{$key}={$val}";
            }
            sort($url);
            $arr   = implode('&', $url);
            $sign  = 'GET' . "\n";
            $sign .= 'mws.amazonservices.com' . "\n";
            $sign .= '/Orders/2013-09-01' . "\n";
            $sign .= $arr;
            $signature = hash_hmac("sha256", $sign, AWS_SECRET_ACCESS_KEY, true);
            $signature = urlencode(base64_encode($signature));
            $link  = "https://mws.amazonservices.com/Orders/2013-09-01?";
            $link .= $arr . "&Signature=" . $signature;
                   
             $ch = curl_init();
                    $timeout = 5;
                    curl_setopt($ch, CURLOPT_URL, $link);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $data = curl_exec($ch);
                    curl_close($ch);
                   $xml = simplexml_load_string($data);
            //print_r($data);exit;
             Mage::log('Magento Order details:'.$data, null, 'amazon_custom.log');
            foreach($xml->ListOrderItemsResult->OrderItems->OrderItem as $item)
            {
            
            
                        $xmlarray = array(); // this will hold the flattened data 
                     $this->XMLToArrayFlat($item, $xmlarray, '', true);
                     
                     $url= $xmlarray['/OrderItem/BuyerCustomizedInfo[1]/CustomizedURL[1]'];
            
                     if(empty($url))
                     { 
                            Mage::log('Magento Order item without personalization:'.$magento_order_id, null, 'amazon_custom.log');
                            continue;
            
                     }
                     Mage::log('Personazlization item:'.$magento_order_id, null, 'amazon_custom.log');
                    $sku = $xmlarray['/OrderItem/SellerSKU[1]'];
                    $filepath = "/var/www/CPS/public_html/amazon/amazon.zip";
                     $ch = curl_init($url);
                          curl_setopt($ch, CURLOPT_HEADER, 1);
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                          $raw_file_data = curl_exec($ch);
                     
                          if(curl_errno($ch)){
                             echo 'error:' . curl_error($ch);
                          }
                          curl_close($ch);
                     
                         file_put_contents($filepath, $raw_file_data);
                     
                     Mage::log('Put zip to folder:'.$magento_order_id, null, 'amazon_custom.log');
                     system('rm -rvf /var/www/CPS/public_html/amazon/azfile/*');
                     system('unzip /var/www/CPS/public_html/amazon/amazon.zip -d /var/www/CPS/public_html/amazon/azfile/');
                     $files = scandir('/var/www/CPS/public_html/amazon/azfile', SCANDIR_SORT_DESCENDING);
                     $newest_file = $files[0];
                     system('chmod 777 /var/www/CPS/public_html/amazon/azfile/'.$newest_file);
                     $str = file_get_contents('/var/www/CPS/public_html/amazon/azfile/'.$newest_file);
                                        
                     Mage::log('UNzip:'.$magento_order_id, null, 'amazon_custom.log');
                     $json = json_decode($str, true); 
                     $name = $json['customizationInfo']['aspects'][0]['text']['value'];
                     //system('rm /var/www/CPS/public_html/amazon/azfile/'.$newest_file);
                     system('rm /var/www/CPS/public_html/amazon/amazon.zip');
            
            
                     $serialize_array = Array(
                         "info_buyRequest" => Array
                             (
                                 "form_key" => $this->generateRandomString(),
                                 "product" => 41209,
                                 "child_product" => 41209,
                                 "related_product" => '',
                                 "options" => Array
                                     (
                                         "25501" => $name
                                     ),
                     
                                 "qty" => 1,
                                 "required_check" => on,
                                 "id" => 41209,
                                 "wishlist_id" => 0,
                             ),
                     
                         "options" => Array
                             (
                                 "0" => Array
                                     (
                                         "label" => Name,
                                         "value" => $name,
                                         "print_value" => $name,
                                         "option_id" => 25501,
                                         "option_type" => field,
                                         "option_value" => $name,
                                         "custom_view" => '',
                                     )
                     
                             ),
                     
                         "giftcard_lifetime" => ' ',
                         "giftcard_is_redeemable" => 0,
                         "giftcard_email_template" => '',
                         "giftcard_type" => '');
                     
                     $product_options = serialize($serialize_array);
                     Mage::log('Magento Order item with personalization:'.$magento_order_id.'     '.$product_options, null, 'amazon_custom.log');
                     //print_r($serialize_array);i
                    if($sku){
                     $query_order = 'SELECT item_id,product_id FROM sales_flat_order_item WHERE order_id='.$magento_order_id.' AND sku='.$sku;
                     $results_or= $writeConnection->fetchAll($query_order);
                            $product_id = $results_or[0]['product_id'];
                            $itemId = $results_or[0]['item_id'];
                            Mage::log('Magento Order item with personalization:'.$query_order.'     '.$itemId, null, 'amazon_custom.log');
                     }
                     $orderModel = Mage::getModel('sales/order')->load($magento_order_id);
                     //$orderItem = $orderModel->getAllItems();
                     $orderItem = $orderModel->getItemById($itemId);
                     Mage::log('Magento Order item:'.$magento_order_id.'     '.$orderItem, null, 'amazon_custom.log');
                    $orderItem->setProductOptions($serialize_array)->save();
                     try
                     {
                        $query_update = "UPDATE sales_flat_order_item SET product_options='".$product_options."' WHERE order_id=".$magento_order_id." AND sku=".$sku;
                    $update = $writeConnection->query($query_update);
                    Mage::log('Magento Order item with success:'.$magento_order_id.'     '.$query_update, null, 'amazon_custom.log');
                    Zend_Debug::dump($update);
                     }
                     catch(Exception $e)
                     {
                        Mage::log('Magento Order error:'.$magento_order_id.'     '.$e, null, 'amazon_custom.log');
                     }
                    
            }
        }
        function XMLToArrayFlat($xml, &$return, $path='', $root=false)
        {
        
            $children = array();
            if ($xml instanceof SimpleXMLElement) {
                $children = $xml->children();
                if ($root){ // we're at root 
                    $path .= '/'.$xml->getName();
                }
            }
            if ( count($children) == 0 ){
                $return[$path] = (string)$xml;
                return;
            }
        
        
            $seen=array();
            foreach ($children as $child => $value) {
                $childname = ($child instanceof SimpleXMLElement)?$child->getName():$child;
                if ( !isset($seen[$childname])){
                    $seen[$childname]=0;
                }
                $seen[$childname]++;
              $this->XMLToArrayFlat($value, $return, $path.'/'.$child.'['.$seen[$childname].']');
         
            }
        }

        public function generateRandomString($length = 12) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

    //########################################
}