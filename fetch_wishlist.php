<?php
include_once "app/Mage.php";
Mage::app('admin')->setCurrentStore(0);
Mage::app('default');


$customer_id = 473828;
//$customer_id = 253968;

$customer = Mage::getModel('customer/customer')
    ->load($customer_id);
$wishList = Mage::getSingleton('wishlist/wishlist')->loadByCustomer($customer);
//var_dump($wishList);
$wishListItemCollection = $wishList->getItemCollection();

if (count($wishListItemCollection)) {
    $arrProductIds = array();

    foreach ($wishListItemCollection as $item) {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $item->getProduct();
        //$arrProductIds[] = $product->getId();
	$arrProductIds[] = $product->getSku();

    }
}
print_r($arrProductIds);
echo implode("\n",$arrProductIds);
?>
