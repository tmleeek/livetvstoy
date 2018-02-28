<?php
require_once('/var/www/CPS/public_html/app/Mage.php'); 
Mage::app('default');

echo '<pre>';
$product = Mage::getModel('catalog/product')->load('3104');
$cats = $product->getCategoryIds();

//foreach ($cats as $category_id) {
   $_cat = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load('3104');
   print_r($_cat->getName());
//}


?>
