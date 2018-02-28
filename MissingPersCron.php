<?php 

require_once('app/Mage.php');
Mage::app();
Mage::app('admin')->setUseSessionInUrl(false);
error_reporting(E_ALL | E_STRICT);
if (file_exists(BP.DS.'maintenance.flag')) exit;
if (class_exists('Kwanso_Apiaddtocart_Model_Observer') === false) {
	echo "Class not found"; 
	exit;
}

try {
	@set_time_limit(0);
	
	$obj = new Kwanso_Apiaddtocart_Model_Observer();
	$obj->missingOrdersCron();

} catch (Exception $e) {
	Mage::logException($e);

}
