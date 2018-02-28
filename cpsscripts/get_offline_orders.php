<?php
/*
*This file is to Generate sales order report during 12:00 to 12:00 CST. 
*It pulls orders which are in pending_fulfillment status and payment method is Money order/Checkout
*The output file is pushed to a remote ftp location
*/
	require_once(dirname(__FILE__) . "/../app/Mage.php");
	//$app = Mage::app('');
	Mage::app('admin')->setCurrentStore(0);
	Mage::app('default');
	chdir("/var/www/CPS/public_html/cpsscripts/");
	$resource = Mage::getSingleton('core/resource');

	$readConnection = $resource->getConnection('core_read');


		
	//$date_to = date("Y-m-d", strtotime("+1 day"));
	$date_to = date("Y-m-d");

	$from_time = date("Y-m-d", strtotime("-1 day"))." 05:00:01"; // UTC 05:00 is 12:00 CST
	$to_time = $date_to." 05:00:00";// UTC 05:00 is 12:00 CST. This needs to be changed when Day light savings changes

	$order_table = 'sales_flat_order';
	$on_condition = "main_table.parent_id = $order_table.entity_id AND $order_table.status='pending_fulfillment' AND $order_table.created_at BETWEEN '".$from_time."' AND '".$to_time."'";

	$orderCollection =  Mage::getModel('sales/order_payment')->getCollection()->addFieldToFilter('method',"checkmo");    // for e.g checkmo

	$orderCollection ->getSelect()->join($order_table,$on_condition);

	$csv_file_name = "OffLineOrder_Report".time().".csv";
	$file = fopen("/var/www/CPS/public_html/cpsscripts/spool/".$csv_file_name, "w");


	fputcsv($file, array("Order #", "Name", "Email", "Billing Address", "Billing State", "Billing City", "Billing Zip", "Shipping Name", "Shipping Address", "Shipping State", "Shipping City", "Shipping Zipcode", "Shipping Telephone", "Walmart PO#", "SKU", "Quantity", "Client SKU", "Size", "Custom options"));

	$row_count = 0;

	foreach($orderCollection as $order):
 		//print "\nORDER # ".$order->getIncrementId(); echo ":";

		$order_id = $order->getId();
		$order_det = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());

		//$history = $order_det->getStatusHistoryCollection()->getFirstItem();
		$walmart_po = null;
		$history_collection = $order_det->getStatusHistoryCollection();
		foreach($history_collection as $history){
			$comment = $history->getComment();
			if($comment) {
				$walmart_po = $comment;	
			}
		}

		$billing_address = $order_det->getBillingAddress()->getData();

		$billing_name = $billing_address['firstname']." ".$billing_address['lastname']; 
		$billing_email = $billing_address['email'];
		$billing_street = $billing_address['street'];
		$billing_region = $billing_address['region'];
		$billing_city = $billing_address['city'];
		$billing_postcode = $billing_address['postcode'];
		

		$shipping_address = $order_det->getShippingAddress()->getData();

		$shipping_name = $shipping_address['firstname']." ".$shipping_address['lastname']; 
		$shipping_email = $shipping_address['email'];
		$shipping_street = $shipping_address['street'];
		$shipping_region = $shipping_address['region'];
		$shipping_city = $shipping_address['city'];
		$shipping_postcode = $shipping_address['postcode'];
		$shipping_telephone = $shipping_address['telephone'];
		
		$sku_array[] = array();

		$sku_counter = array();
		foreach ($order_det->getAllItems() as $item) {
			if (!isset($sku_counter[$item->getSku()])) {
				$sku_counter[$item->getSku()] = 0;
	        }
		    $sku_counter[$item->getSku()] += (float) $item->getQtyOrdered();

			$item_data = $item->getData();
			
			$prod_data = unserialize($item_data['product_options']);

			$config_attrib_data = $prod_data['info_buyRequest']['super_attribute'];

			$configurable_attribute_info = "";
			if(is_array($config_attrib_data)) {
				$config_attrib_id = key($config_attrib_data);
				$config_attrib_val = $config_attrib_data[$config_attrib_id];

				$config_attribute_label = $readConnection->fetchOne("SELECT frontend_label FROM eav_attribute WHERE attribute_id = $config_attrib_id");

				$config_attribute_opt = $readConnection->fetchOne("SELECT value FROM eav_attribute_option_value WHERE option_id = $config_attrib_val");
			}	
			//$configurable_attribute_info = $config_attribute_label.":".$config_attribute_opt;
			$configurable_attribute_info = $config_attribute_opt;


			$opts = $item->getData('product_options');
	                //$opts=unserialize($opts);
	                //var_dump($opts);
	                //print_r($opts);
			$custom_options =  $item->getProductOptionsJson(); 

			$sku_item = $item->getSku(); 

			$quantity = $item->getQtyOrdered();

			$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_item);
			
			$client_sku = $product->getClientSku();

			$parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
    		if(isset($parentIds[0])){
        		$client_sku = Mage::getModel('catalog/product')->load($parentIds[0])->getClientSku();
        	}

			if (!in_array($order_id.'_'.$sku_item, $sku_array))
				fputcsv($file, array($order->getIncrementId(), $billing_name, $billing_email, $billing_street, $billing_region, $billing_city, $billing_postcode, $shipping_name, $shipping_street, $shipping_region, $shipping_city, $shipping_postcode, $shipping_telephone, $walmart_po, $sku_item, $quantity, $client_sku, $configurable_attribute_info, $custom_options));
		
			$sku_array[] = $order_id.'_'.$sku_item;
			$row_count++;
		}

		/*$product_quantity = "";
		$client_sku = "";
		foreach ($sku_counter as $sku_item => $quantity) {
			$product_quantity = $sku_item ." Quantity=>".$quantity." ->	";	

			$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_item);
			
			$client_sku = $product->getClientSku();



			fputcsv($file, array($order->getIncrementId(), $billing_name, $billing_email, $billing_street, $billing_region, $billing_city, $billing_postcode, $shipping_name, $shipping_email, $shipping_street, $shipping_region, $shipping_city, $shipping_postcode, $shipping_telephone, $sku_item, $quantity, $client_sku));

		}*/
		//echo $items= implode(":", array_keys($sku_counter));

		//fputcsv($file, array($order->getIncrementId(),$order_det->getBillingAddress()->getFirstname(),$order_det->getBillingAddress()->getEmail(),$product_quantity, $client_sku, $custom_options));
	endforeach;

	
	fclose($file);

	if ($row_count > 0) {
		$ftp_server = "50.200.52.67";
	    $ftp_user_name = "nsintegrator";
	    $ftp_user_pass = "CPSmbm1556";

	    $remote_dir = "/Walmart_Retail/";

	     // set up basic connection
	    $conn_id = ftp_connect($ftp_server);

	    // login with username and password
	    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            // turn passive mode on	
     	    ftp_pasv($conn_id, true);
		// upload file
	    if (ftp_put($conn_id, "/Walmart_Retail/$csv_file_name", "spool/".$csv_file_name, FTP_ASCII)) 
	    	print "Successfully uploaded $file_to_load to FTP server..\n";
	    else
	    	print "Warning : There is some issue with $csv_file_name !!!\n";

	    // close connection
    	ftp_close($conn_id);
	} else 
		   print "There is no records to process !!!\n";	


	?>
