<?php

require 'app/Mage.php';
Mage::app();

//$order = Mage::getModel('sales/order')->loadByIncrementId('300036168');
$storeId = 5;
$statuses = 'pending_fulfillment';
$helper=Mage::helper('celigoconnector');
$orderArr = array();
 $orderCollection = Mage::getModel("sales/order")->getCollection()->addFieldToFilter('pushed_to_ns', 0)->addFieldToFilter('store_id', $storeId)->addFieldToFilter('status', array(
                    'in' => $statuses
                ));
                $startdate = '2018-01-01 15:50:59';
                if (trim($startdate) != '') {
                    $startdate = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($startdate));
                    $orderCollection->addFieldToFilter('created_at', array(
                        'from' => $startdate
                    ));
                }
//echo $orderCollection->printLogQuery(true); 
                $orderCollection->setOrder('created_at', 'desc');
//print_r($orderCollection->count());
                if ($orderCollection->count() > 0) {

                    foreach ($orderCollection as $order) {
//		echo $order->getId().'Order Id';
                      array_push($orderArr, $order->getId());
                    }
                }

print_r($orderArr);
//print_r($order->getAllVisibleItems());
/*$orderItems = $order->getItemsCollection();


    $skuQtyArray = array();
    foreach ($orderItems as $item)
    {   
	print_r($item);
	}
*/
?>
