<?php
require_once('app/Mage.php'); //Path to Magento


umask(0);

Mage::app();

$fromDate = "2015-08-03 00:00:00";
$toDate = "2015-08-04 00:00:00";


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
                if (count($opts['options']) > 0 && $item->getProductOptionsJson() != '' && $payment_method=='PayPal Express Checkout'){
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

		echo $order->getIncrementId().','.$item->getSku().','.$payment_method.','.$json_str; echo "\n\n";
                } 

        }
}
?>

