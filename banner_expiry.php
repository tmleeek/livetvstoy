<?php
// initialize Magento environment
include_once "app/Mage.php";
Mage::app('admin')->setCurrentStore(0);
Mage::app('default');

$cache_run = false;

$now_timestamp = Mage::getModel('core/date')->timestamp(time()); 


$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');

$query_sel = "SELECT date_from, date_to FROM enterprise_banner WHERE is_enabled = 1";

$results = $readConnection->fetchAll($query_sel);


foreach($results as $ro) {

    $from_date = $ro['date_from']; 

    $from_timestamp = Mage::getModel('core/date')->timestamp($from_date);
    $from_timestamp = $from_timestamp + 21600;
    $from_date_str = date("YmdH", $from_timestamp);	


    $to_date = $ro['date_to'];	
	
    $to_timestamp = Mage::getModel('core/date')->timestamp($to_date);
    $to_timestamp = $to_timestamp + 21600;
    $to_date_str = date("YmdH", $to_timestamp);	    		


    $now_date_str = date("YmdH", $now_timestamp);
    //echo $now_date_stamp = Mage::getModel('core/date')->timestamp(time());
    if ($from_date_str == $now_date_str || $to_date_str == $now_date_str){
	$cache_run = true;
    }

}


//if ($cache_run) {
    shell_exec("/usr/bin/php /var/www/CPS/public_html/clearcache.php")or die("Unable to execute Cache Refresh !!!");

//}

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
