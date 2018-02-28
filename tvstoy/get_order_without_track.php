<?php
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');



$send_email = false;
$message = "";
$emailmessage = "";

$query_sel = "SELECT sales_flat_order.entity_id, sales_flat_order.created_at, sales_flat_shipment_track.order_id, sales_flat_shipment_track.carrier_code, sales_flat_order.increment_id 
        FROM `sales_flat_order` 
        LEFT JOIN sales_flat_shipment_track ON sales_flat_order.entity_id=sales_flat_shipment_track.order_id 
        WHERE sales_flat_shipment_track.order_id IS NULL AND sales_flat_order.status='complete' AND DATE(sales_flat_order.created_at) > DATE_SUB(NOW(), INTERVAL 30 DAY)";

$results = $readConnection->fetchAll($query_sel);
foreach($results as $ro) {
    
    $orders .= $ro['created_at']." -> ".$ro['increment_id']."\n";                                                                                                                                                        
}                                                                                                                                                                                               
                                                                                                                                                                                                
if (empty($orders))                                                                                                                                                                             
        $message = "";
else{
        $message = "Orders with no Tracking number for the last Thirty days :" ."\n".$orders;
        $send_email = true;
}





$query_sele = "SELECT increment_id, created_at FROM `sales_flat_order` WHERE customer_email IS NULL AND DATE(created_at) > DATE_SUB(NOW(), INTERVAL 30 DAY)";
$resultset = $readConnection->fetchAll($query_sele);
foreach($resultset as $row) {
    $ordersemail .= $row['created_at']." -> ".$row['increment_id']."\n";
}

if (empty($ordersemail))
        $emailmessage = "";
else{
        $emailmessage = "Orders with no Email address for the last Thirty days:" ."\n".$ordersemail;
        $send_email = true;
}


if ($send_email){

        $Name = "Admin"; //senders name
        $email = "admin@tystoybox.com"; //senders e-mail adress
        $recipient = "Magento_order_alerts@cpscompany.com"; //recipient
        //$recipient = "afsar@vtrio.com"; //recipient
        $mail_body = $message."\n".$emailmessage; //"The text for the mail..."; //mail body
        $subject = "Urgent: Error Processing Magento Order"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" ; //optional headerfields

        mail($recipient, $subject, $mail_body, $header); //mail command :) 
}

?>
