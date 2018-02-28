<?php
require 'app/Mage.php';
Mage::app();



$sku = 64152;
 $product_id = Mage::getModel('catalog/product')->getIdBySku($sku);
        

$product = Mage::getModel('catalog/product')->load($product_id);

//$stockItem = $product->getStockItem();
$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
$stockItem->setData('manage_stock', 1);
$stockItem->setData('is_in_stock', 1);
$stockItem->setData('use_config_notify_stock_qty', 0);
$stockItem->setData('qty', 1);

$stockItem->save();
$product->save();

?>