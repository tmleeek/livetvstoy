<?php

class Kwanso_Apiaddtocart_Model_Observer
{
    public function cartAddOptionsToApi($observer)
    {
    	try {
    		
    		if($observer['quote']) {
    			$orderPersonalizations = 
    				json_encode(
							array(
				        		'quote_id' => $observer['quote'],
				        		'product_id' => $observer['product'],
				        		'personalization' => $observer['options'],
				        		'key' => 'Zoh553-54i?JVo3-v3N2u0{579$39D54%4j6F7v2II730N5[3QI',
				        		'action' => 'addtocart'
				        	)
    				);

	    		$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://cps-crawler-production.herokuapp.com/order_personalizations");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $orderPersonalizations);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				$server_output = curl_exec ($ch);
				curl_close ($ch);

				if ($server_output == "OK") { 
				
				} else { 
					Mage::log("Add to cart", Zend_Log::DEBUG, 'missingDebug.log' );
					Mage::log($observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
		    		Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
		    		Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
				}	
    		} else {
    			Mage::log("Add to cart quote & product missed ?", Zend_Log::DEBUG, 'missingDebug.log' );
				Mage::log("Options : ".$observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Add to cart End", Zend_Log::DEBUG, 'missingDebug.log' );
    		}

    	} catch (Mage_Core_Exception $e) {

    		Mage::log("Add to cart", Zend_Log::DEBUG, 'missingDebug.log' );
			Mage::log("Options : ".$observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Add to cart End", Zend_Log::DEBUG, 'missingDebug.log' );
    	}
    }

    public function checkoutCartUpdateItemComplete($observer)
    {
    	try {
    		
    		if($observer['quote']) {
    			$orderPersonalizations = 
    				json_encode(
							array(
				        		'quote_id' => $observer['quote'],
				        		'product_id' => $observer['product'],
				        		'personalization' => $observer['options'],
				        		'key' => 'Zoh553-54i?JVo3-v3N2u0{579$39D54%4j6F7v2II730N5[3QI',
				        		'action' => 'update'
				        	)
    				);

	    		$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://cps-crawler-production.herokuapp.com/order_personalizations");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $orderPersonalizations);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				$server_output = curl_exec ($ch);
				curl_close ($ch);

				if ($server_output == "OK") { 
				
				} else { 
					Mage::log("Add to cart", Zend_Log::DEBUG, 'missingDebug.log' );
					Mage::log($observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
		    		Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
		    		Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
				}	
    		} else {
    			Mage::log("Add to cart quote & product missed ?", Zend_Log::DEBUG, 'missingDebug.log' );
				Mage::log("Options : ".$observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
		    	Mage::log("Add to cart End", Zend_Log::DEBUG, 'missingDebug.log' );
    		}

    	} catch (Mage_Core_Exception $e) {

    		Mage::log("Add to cart", Zend_Log::DEBUG, 'missingDebug.log' );
			Mage::log("Options : ".$observer['options'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Quote Id : ".$observer['quote'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Product Id : ".$observer['product'], Zend_Log::DEBUG, 'missingDebug.log' );
	    	Mage::log("Add to cart End", Zend_Log::DEBUG, 'missingDebug.log' );
    	}
    }


  	public function missingOrdersCron($observer)
    {
    	try {

    		$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');

			$salesFlatOrderItem = $resource->getTableName('sales_flat_order_item');
			$salesFlatOrder = $resource->getTableName('sales_flat_order');
			$salesFlatQuote = $resource->getTableName('sales_flat_quote');
			$catalogProductOption = $resource->getTableName('catalog_product_option');
			$catalogProductOptionTitle = $resource->getTableName('catalog_product_option_title');

			$condition = '%"options";a:0:{}%';

			$query = "SELECT SQL_NO_CACHE DISTINCT o.increment_id AS OrderID, q.entity_id AS quote_id ,
						oi.product_id , oi.product_type AS ProductType, o.status ,
						oi.sku AS SKU, cpo.is_require, oi.created_at, oi.updated_at
						FROM       {$salesFlatOrderItem} oi 
						INNER JOIN {$salesFlatOrder} o ON o.entity_id = oi.order_id
						INNER JOIN {$salesFlatQuote} q ON q.entity_id = o.quote_id
						LEFT JOIN  {$catalogProductOption} cpo ON cpo.product_id = oi.product_id
						WHERE oi.product_options LIKE '{$condition}' 
						AND oi.parent_item_id IS NULL 
						AND (cpo.is_require = 1 OR cpo.is_require IS NULL)
						AND o.status = 'pending_fulfillment'
						AND (oi.created_at BETWEEN CURDATE() -2 AND CURDATE()+1) 
						ORDER BY o.created_at DESC LIMIT 1000;";

			$results = $readConnection->fetchAll($query);
			/*echo "here<pre>";
			print_r($results);
			exit;*/

			foreach ($results as $key => $value) {
				$curl = curl_init();

				curl_setopt_array($curl, array(
				 	CURLOPT_URL => "https://cps-crawler-production.herokuapp.com/order_personalizations/get_personalization?quote_id=".$value['quote_id']."&product_id=".$value['product_id'],
				  	CURLOPT_RETURNTRANSFER => true,
				  	CURLOPT_ENCODING => "",
				  	CURLOPT_MAXREDIRS => 10,
				  	CURLOPT_TIMEOUT => 30,
				  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  	CURLOPT_CUSTOMREQUEST => "GET"
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				  //exit;
				} else {

				  	$response = json_decode($response);

				  	if($response->status == "ok" && $response->code == "200" && $response->personalization->quote_id != null) {

						$order = Mage::getModel('sales/order')->getCollection();
						$order->addFieldToFilter('quote_id',$response->personalization->quote_id);
						$orderIncrementId = $order->getFirstItem()->getIncrementId();

						$personalization = unserialize($response->personalization->personalization);
						//$personalization = unserialize($personalization);
						$quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($response->personalization->quote_id);

						$html = "<h1><b>Order Id : </b>".$orderIncrementId."</h1>";

						if($response->personalization->action == "update") {
							$allItems = $quote->getAllItems();
							foreach ($allItems as $quoteItem) {

								Mage::log("Quote ".$response->personalization->quote_id." Found", null,"missingOrdersEmails.log");
								Mage::log("Searching for Product ".$response->personalization->product_id, null,"missingOrdersEmails.log");

								if($quoteItem->getId() == $response->personalization->product_id) { // In case of update we get Quote Item Id
									$product = $quoteItem->getProduct();

									Mage::log("Product ".$response->personalization->product_id." Found", null,"missingOrdersEmails.log");

						        	if($product->hasOptions) {
						        		
						        		Mage::log("Product ".$response->personalization->product_id." has Options", null,"missingOrdersEmails.log");
						                //foreach ($product->getOptions() as $_option) {
									        foreach ($personalization as $key => $value) {
									        	$queryTitle = "SELECT title FROM {$catalogProductOptionTitle} WHERE option_id = '{$key}'";
									        	$optionResult = $readConnection->fetchOne($queryTitle);
									        	Mage::log($optionResult, null,"missingOrdersEmails.log");
									        	$html .= "<div><b>".$optionResult."</b> : ".$value."</div>";
									        	/*if($_option->getId() == $key) {
									        		$html .= "<div><b>".$_option->getTitle()."</b> : ".$value."</div>";
									        	}*/
									        }
									    //}
						            }
								}
					        }
						} else {
							$allItems = $quote->getAllItems();
							foreach ($allItems as $quoteItem) {
								Mage::log("Quote ".$response->personalization->quote_id." Found", null,"missingOrdersEmails.log");
								Mage::log("Searching for Product ".$response->personalization->product_id, null,"missingOrdersEmails.log");

					        	$product = $quoteItem->getProduct();

					        	if($product->getId() == $response->personalization->product_id) { // In case of addtocart we get Product Id

					        		Mage::log("Product ".$response->personalization->product_id." Found", null,"missingOrdersEmails.log");

						        	if($product->hasOptions)  {

						        		Mage::log("Product ".$response->personalization->product_id." has Options", null,"missingOrdersEmails.log");
						                //foreach ($product->getOptions() as $_option) {
									        foreach ($personalization as $key => $value) {
									        	$queryTitle = "SELECT title FROM {$catalogProductOptionTitle} WHERE option_id = '{$key}'";
									        	$optionResult = $readConnection->fetchOne($queryTitle);
									        	Mage::log($optionResult, null,"missingOrdersEmails.log");
									        	$html .= "<div><b>".$optionResult."</b> : ".$value."</div>";
									        	/*if($_option->getId() == $key) {
									        		$html .= "<div><b>".$_option->getTitle()."</b> : ".$value."</div>";
									        	}*/
									        }
									    //}
						            }
					        	}

					        }
						}

						$this->sendEmail($html,$orderIncrementId);

					} else {
						echo "Empty Data";						
						//exit;
					}
				}
			}
    		
    	} catch (Mage_Core_Exception $e) {

    		Mage::log("Add to cart", Zend_Log::DEBUG, 'missingDebug.log' );
    	}

    }

    public function sendEmail($body,$orderId)
    {
    	$from_email     = Mage::getStoreConfig('trans_email/ident_general/email'); //fetch sender email Admin
        $from_name      = Mage::getStoreConfig('trans_email/ident_general/name'); //fetch sender name Admin
        $emails 		= array(
				        		'Ahmed' => 'ahmed.javed@kwanso.com', 
				        		'Abdul Basit' => 'abdul.basit@kwanso.com', 
			        		);

        foreach ($emails as $name => $email) {
        	$mail = Mage::getModel('core/email');
			$mail->setToName($name);
			$mail->setToEmail($email);
			$mail->setBody($body);
			$mail->setSubject('Missing Personalization Email for Order # '.$orderId);
			$mail->setFromEmail($from_email);
			$mail->setFromName($from_name);
			$mail->setType('html'); // You can use 'html' or 'text'

			try {
			    $mail->send();
			    Mage::log("Email Sent for Order # ".$orderId, null,"missingOrdersEmails.log");
			}
			catch (Exception $e) {
			    Mage::log($e->getMessage(), null,"missingOrdersEmails.log");
			}
        }
    }


}