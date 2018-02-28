<?php

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$setup->updateAttribute('catalog_product', 'gift_for_him_position', 'used_in_product_listing',1);
$setup->updateAttribute('catalog_product', 'gift_for_him', 'used_in_product_listing',1);

$setup->updateAttribute('catalog_product', 'gift_for_her_position', 'used_in_product_listing',1);
$setup->updateAttribute('catalog_product', 'gift_for_her', 'used_in_product_listing',1);

$setup->updateAttribute('catalog_product', 'gift_for_her_position', 'used_in_product_listing',1);
$setup->updateAttribute('catalog_product', 'gift_for_her', 'used_in_product_listing',1);

$installer->endSetup();