<?php
require_once('app/Mage.php'); //Path to Magento


umask(0);

Mage::app();

//$fromDate = "2015-07-28 00:00:00";
//$toDate = "2015-08-04 11:40:00";

$to_date = date('Y-m-d h:i:s A', strtotime("now") - 60 * 60 * 5); 
$from_date = date('Y-m-d h:i:s A', strtotime("now") - 60 * 60 * 6);

$fromDate = $from_date; 
$toDate = $to_date;


//$fromDate = "2015-08-03 00:00:00";
//$toDate = "2015-08-07 12:00:00";


$orders = Mage::getModel('sales/order')->getCollection()
//            ->addAttributeToFilter('status', array('eq' => 'processing'));
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate));
foreach ($orders as $order) {

$payment = $order->getPayment();

//Get Payment Info
$payment_method = $payment->getMethodInstance()->getTitle();
	
        //echo $order->getId()."->".$order->getIncrementId();  echo "\n";
        //echo "------------------\n";
        foreach($order->getAllItems() as $item) {

                /*$product = Mage::getModel('catalog/product')->load($item->getProductId());
                    $options = $product->getProductOptionsCollection();
                    foreach($options as $option) {
                        //echo $option->getSku();
                        //echo $option->getTitle();
                        if($option->getValue() != '')
                        echo $option->getTitle()."->".$option->getValue()."\n";
                    }*/



                //$opts = $item->getProductOptions();

                $opts=$item->getData('product_options');
                $opts=unserialize($opts);
                //var_dump($opts);
                //print_r($opts);
                if (count($opts['options']) > 0 && $item->getProductOptionsJson() == ''){
                //echo $opts['info_buyRequest']['product']."=>".count($opts['options']); echo "\n\n";
                //echo $order->getId()."->".$order->getIncrementId();  echo "\n";
		//echo $item->getSku(); echo $item->getProductOptionsJson();echo "\n";
		$options  = $opts['options'];    //print_r($options);   
		$json_str = "";     
                $json_str = "{";
		foreach($options as $option){
		    $json_str .= '"'.$option['label'].'":';
		    $json_str .= '"'.$option['value'].'"'; 
		    $json_str .=',';	
                	//echo $item->getProductOptionsJson();  
		}
		$json_str = rtrim($json_str, ","); 
		$json_str .= "}";

		//$order_mail .= $order->getIncrementId().','.$item->getSku().','.$payment_method.','.$json_str."\r\n";
		$order_mail .= $order->getIncrementId().'|'.$order->getCreatedAt().'|'.$item->getSku().'|'.$payment_method.'|'.$json_str."\r\n";
                } 

        }
}
if ($order_mail != '') {
	$message = "These are the orders which are missing custom options from $from_date to $to_date CST";
	//$message = "These are the orders which are missing custom options for Last four days";

        $Name = "Admin"; //senders name
        $email = "admin@tystoybox.com"; //senders e-mail adress

        $recipient = "Magento_order_alerts@cpscompany.com";
        //$recipient = "afsar@vtrio.com"; //recipient
        $mail_body = $message. "\n\n". $order_mail; //"The text for the mail..."; //mail body
        $subject = "Missing custom option Orders"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" ; //optional headerfields

        mail($recipient, $subject, $mail_body, $header); //mail command :) 
}
?>

