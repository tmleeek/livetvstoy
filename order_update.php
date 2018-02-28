<?php
// initialize Magento environment
include_once "app/Mage.php";
Mage::app('admin')->setCurrentStore(0);
Mage::app('default');

$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');


/*$query_sel = "SELECT increment_id, DATE(sales_flat_order.created_at) FROM sales_flat_order LEFT JOIN sales_flat_order_status_history ON sales_flat_order.entity_id = sales_flat_order_status_history.parent_id WHERE sales_flat_order.status = 'processing' AND sales_flat_order_status_history.status = 'pending_fulfillment' ORDER BY sales_flat_order.created_at DESC";*/
$query_sel = "SELECT increment_id, DATE(sales_flat_order.created_at) FROM sales_flat_order LEFT JOIN sales_flat_order_status_history ON sales_flat_order.entity_id = sales_flat_order_status_history.parent_id WHERE (sales_flat_order.status = 'processing' OR sales_flat_order.status = 'pending') AND sales_flat_order_status_history.status = 'pending_fulfillment' ORDER BY sales_flat_order.created_at DESC;";

$results = $readConnection->fetchAll($query_sel);
echo "Total orders = ". count($results)."<br>";
foreach($results as $ro) {

    echo $incrementId = $ro['increment_id'];  print "\n";

    $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
    $order->setState('processing', true);
    $order->setStatus('pending_fulfillment', true); //update status to pending_fulfillment
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
