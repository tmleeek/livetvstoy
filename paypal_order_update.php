<?php
// initialize Magento environment
include_once "app/Mage.php";
Mage::app('admin')->setCurrentStore(0);
Mage::app('default');

$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');


$query_sel = "SELECT sales_flat_order.entity_id, increment_id, created_at FROM `sales_flat_order` LEFT JOIN sales_flat_order_payment ON sales_flat_order.entity_id = sales_flat_order_payment.parent_id WHERE `sales_flat_order`.`status` = 'pending_fulfillment' AND DATE(created_at) > '2015-01-01' AND sales_flat_order_payment.method = 'paypal_express' ORDER BY sales_flat_order.entity_id ASC";

$results = $readConnection->fetchAll($query_sel);
foreach($results as $ro) {

    echo $incrementId = $ro['increment_id'];  print "\n";

    $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);


    $order->setStatus('processing', true);
    $order->save();

}



/*
Mage_Sales_Model_Order::STATE_NEW
Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
Mage_Sales_Model_Order::STATE_PROCESSING
Mage_Sales_Model_Order::STATE_COMPLETE
Mage_Sales_Model_Order::STATE_CLOSED
Mage_Sales_Model_Order::STATE_CANCELED
Mage_Sales_Model_Order::STATE_HOLDED
*/
?>
