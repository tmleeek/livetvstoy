<?php
$installer = $this;
$installer->startSetup();


$fieldsQuote = 'SHOW COLUMNS FROM sales_flat_quote_item' ;
$fieldsOrder = 'SHOW COLUMNS FROM sales_flat_order_item' ;

$cols_quote = $this->getConnection()->fetchCol($fieldsQuote);
$cols_order = $this->getConnection()->fetchCol($fieldsOrder);

if (!in_array('product_image_url', $cols_quote)){
	$installer->addAttribute("quote_item", "product_image_url", array("type"=>"varchar"));
}
if (!in_array('product_submission_image_url', $cols_quote)){
$installer->addAttribute("quote_item", "product_submission_image_url", array("type"=>"varchar"));
}
if (!in_array('cropped_image_url', $cols_quote)){
$installer->addAttribute("quote_item", "cropped_image_url", array("type"=>"varchar"));
}

if (!in_array('product_image_url', $cols_order)){
	$installer->addAttribute("order_item", "product_image_url", array("type"=>"varchar"));
}
if (!in_array('product_submission_image_url', $cols_order)){
	$installer->addAttribute("order_item", "product_submission_image_url", array("type"=>"varchar"));
}
if (!in_array('cropped_image_url', $cols_order)){
	$installer->addAttribute("order_item", "cropped_image_url", array("type"=>"varchar"));
}

$installer->endSetup();
	 