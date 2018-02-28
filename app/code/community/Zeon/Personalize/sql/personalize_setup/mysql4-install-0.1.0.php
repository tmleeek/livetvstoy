<?php
$installer = $this;
$installer->startSetup();
$installer->run(
    "CREATE TABLE IF NOT EXISTS `{$installer->getTable('personalize')}` (
      `personalize_id` int(11) NOT NULL auto_increment,
      `design_id` int(11) NOT NULL,
      `design_params` text,
      `product_id` int(11) NOT NULL,
      `customer_id` varchar(255) NOT NULL,
	  `session_id` varchar(255) NOT NULL,
      `is_guest` varchar(255) NOT NULL,
      `order_id` int(11) NOT NULL,
      `quote_id` int(11) NOT NULL,
	  `sku` varchar(255),
      `productcode` varchar(255),
      `update_date` datetime default NULL,
      `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
      PRIMARY KEY  (`personalize_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->addAttributeGroup('catalog_product', 'Default', 'General', 1000);
$installer->addAttribute('catalog_product', 'personalize', array(
    'group'             => 'General',
    'label'                => 'Personalized Product',
    'type'                => 'int',
    'input'                => 'boolean',
    'source'               => 'eav/entity_attribute_source_boolean',
    'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'              => 1,
    'required'             => 1,
    'user_defined'         => 1,
    'searchable'           => 1,
    'filterable'           => 1,
    'comparable'           => 0,
    'visible_on_front'     => 0,
    'visible_in_advanced_search'    => 0,
    'unique'            => 0,
    'default'            => 0
));

$setup->updateAttribute('catalog_product', 'personalize', 'is_used_for_promo_rules',1);
$setup->updateAttribute('catalog_product', 'personalize', 'is_used_for_price_rules',1);

$installer->endSetup();
