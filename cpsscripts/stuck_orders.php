<?php
/*
*
* This script is intend to send an email alert for Processing/Pending Orders at magento over an hour
*
*/
require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);

Mage::app();




$orders = Mage::getModel('sales/order')->getCollection()
    ->addFieldToFilter('status', array('pending', 'processing'))
    ->addFieldToFilter('created_at', array('gt' =>  new Zend_Db_Expr("DATE_ADD('".now()."', INTERVAL -'24' HOUR)")));

        $order_records = "";
$order_cnt=0;
    foreach ($orders as $order) {
        $order_status = $order->getStatus(); 
        $order_number = $order->getIncrementId();
        $order_date = $order->getCreatedAt();

        $order_records .= "\n".$order_number." => ".$order_status." => ".$order_date;
        $order_cnt++;
    }


         $message = "The following orders are either processing or pending status for more than an hour. $order_records";

        if($order_cnt > 0){
                $Name = "Admin"; //senders name
                $email = "admin@tystoybox.com"; //senders e-mail adress
                //$recipient = "afsar@vtrio.com"; //recipient
		$recipient = "MTelci@cpscompany.com"; //recipient
                $mail_body = $message; //"The text for the mail..."; //mail body
                $subject = "Stuck Orders at Magento"; //subject
                $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
                  "CC: bpunati@cpscompany.com \r\n" .
		  "CC: AIsmail@cpscompany.com \r\n" ;

                mail($recipient, $subject, $mail_body, $header); //mail command :) 
        }

?>

