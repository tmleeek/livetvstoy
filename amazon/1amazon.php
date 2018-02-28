<?php

require '/var/www/CPS/public_html/app/Mage.php';

Mage::app();

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');

/* Fetch amazon ordr id   */
$magento_order_id = 560358;
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
foreach($xml->ListOrderItemsResult->OrderItems->OrderItem as $item)
{


	    $xmlarray = array(); // this will hold the flattened data 
	 XMLToArrayFlat($item, $xmlarray, '', true);
	 
	 $url= $xmlarray['/OrderItem/BuyerCustomizedInfo[1]/CustomizedURL[1]'];

	 if(empty($url))
	 { 
		echo 'Url is empty';
		continue;

	 }
	 
	echo $sku = $xmlarray['/OrderItem/SellerSKU[1]'];exit;
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
	 
	 
	 
	 system('unzip /var/www/CPS/public_html/amazon/amazon.zip -d azfile/');
	 $files = scandir('/var/www/CPS/public_html/amazon/azfile', SCANDIR_SORT_DESCENDING);
	 $newest_file = $files[0];
	 system('chmod 777 /var/www/CPS/public_html/amazon/azfile/'.$newest_file);
	 $str = file_get_contents('/var/www/CPS/public_html/amazon/azfile/'.$newest_file);
	 
	 system('rm /var/www/CPS/public_html/amazon/azfile/'.$newest_file);
	 system('rm /var/www/CPS/public_html/amazon/amazon.zip');
	 
	 $json = json_decode($str, true); 
	 echo $name = $json['customizationInfo']['aspects'][0]['text']['value'];exit;
	 


	 $serialize_array = Array(
	     "info_buyRequest" => Array
		 (
		     "form_key" => generateRandomString(),
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
	 //print_r($serialize_array);i
try {
	if($sku){
	 $query_order = 'SELECT item_id,product_id FROM sales_flat_order_item WHERE order_id='.$magento_order_id.' AND sku='.$sku;
	 $results_or= $writeConnection->fetchAll($query_order);
	 	$product_id = $results_or[0]['product_id'];
		$itemId = $results_or[0]['item_id'];
	 }
	 $orderModel = Mage::getModel('sales/order')->load($magento_order_id);
	 //$orderItem = $orderModel->getAllItems();
	 $orderItem = $orderModel->getItemById($itemId);
	$orderItem->setProductOptions($serialize_array)->save();
}
 catch (Exception $e) {
	echo 'caught';
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
        XMLToArrayFlat($value, $return, $path.'/'.$child.'['.$seen[$childname].']');
    }
}

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
