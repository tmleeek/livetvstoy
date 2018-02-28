<?php
	$installer = $this;
	$installer->startSetup();

	$fieldsQuote = 'SHOW COLUMNS FROM sales_flat_quote_item' ;
	$fieldsOrder = 'SHOW COLUMNS FROM sales_flat_order_item' ;

	$cols_quote = $this->getConnection()->fetchCol($fieldsQuote);
	$cols_order = $this->getConnection()->fetchCol($fieldsOrder);

	if (!in_array('background_cropped_url', $cols_quote)){
		$installer->addAttribute("quote_item", "background_cropped_url", array("type"=>"varchar"));
	}

	if (!in_array('background_cropped_url', $cols_order)){
		$installer->addAttribute("order_item", "background_cropped_url", array("type"=>"varchar"));
	}

	$installer->endSetup();