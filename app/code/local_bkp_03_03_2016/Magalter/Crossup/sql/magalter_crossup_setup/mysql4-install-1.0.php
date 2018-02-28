<?php

$this->startSetup();

try {
    $this->run("
 CREATE TABLE IF NOT EXISTS {$this->getTable('magalter_crossup/crossup')} (
  `upsell_id` bigint unsigned NOT NULL AUTO_INCREMENT, 
  `status` tinyint(4) unsigned NOT NULL,
  `once` tinyint(4) unsigned default 0,
  `priority` int(10) NOT NULL,
  `groups` varchar(255) default NULL,  
  `stores` varchar(255) default NULL, 
  `discount` decimal(12,4) NOT NULL,
  `discount_type` tinyint(1) NOT NULL,
  `min_qty` int(10) unsigned DEFAULT 1,
  `available_from` datetime DEFAULT NULL,
  `available_to` datetime DEFAULT NULL,  
  `name` varchar(255) DEFAULT NULL, 
  `label` varchar(255) DEFAULT NULL,
  `additional_settings` mediumtext DEFAULT NULL,
  `template` mediumtext default NULL, 
  `styles` mediumtext default NULL,
  `conditions_serialized` text NOT NULL,
  `actions_serialized` text NOT NULL,
  `media` varchar(255) default NULL,
  `description` text default NULL,
  PRIMARY KEY (`upsell_id`), 
  KEY `KEY_MAGALTER_CROSSUP_PRIORITY` (`priority`),
  KEY `KEY_MAGALTER_CROSSUP_ONCE` (`once`),
  KEY `KEY_MAGALTER_CROSSUP_DISCOUNT_TYPE` (`discount_type`),
  KEY `KEY_MAGALTER_CROSSUP_MIN_QTY` (`min_qty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 


CREATE TABLE IF NOT EXISTS {$this->getTable('magalter_crossup/rule_actions')} (
  `action_id` int(10) unsigned NOT NULL auto_increment,
  `rule_id` bigint unsigned NOT NULL, 
  `store_id` int(10) unsigned NOT NULL,
  `product_ids` text DEFAULT NULL,
  PRIMARY KEY  (`action_id`),
  KEY `FK_MAGALTER_CROSSUP_ACTION_RULE` (`rule_id`), 
  KEY `KEY_MAGALTER_CROSSUP_ACTION_STORE` (`store_id`),
  CONSTRAINT `FK_MAGALTER_CROSSUP_ACTION_RULE` FOREIGN KEY (`rule_id`) REFERENCES {$this->getTable('magalter_crossup/crossup')} (`upsell_id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  

 
 CREATE TABLE IF NOT EXISTS {$this->getTable('magalter_crossup/upsell_item')} (
  `item_id` int(10) unsigned NOT NULL auto_increment,
  `upsell_id` bigint unsigned NOT NULL, 
  `anchor_product_id` int(10) unsigned NOT NULL,
  `related_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`item_id`),
  KEY `FK_MAGALTER_CROSSUP_PRODUCT` (`upsell_id`),
  KEY `KEY_MAGALTER_CROSSUP_ANCHOR_PRODUCT_ID` (`anchor_product_id`),
  KEY `KEY_MAGALTER_CROSSUP_RELATED_ID` (`related_id`),
  CONSTRAINT `FK_MAGALTER_CROSSUP_PRODUCT` FOREIGN KEY (`upsell_id`) REFERENCES {$this->getTable('magalter_crossup/crossup')} (`upsell_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_MAGALTER_CROSSUP_ANCHOR_PRODUCT_ID` FOREIGN KEY (`anchor_product_id`) REFERENCES {$this->getTable('catalog/product')} (`entity_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_MAGALTER_CROSSUP_RELATED_ID` FOREIGN KEY (`related_id`) REFERENCES {$this->getTable('catalog/product')} (`entity_id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
  
");
 
  
} catch (Exception $e) {   
    Mage::logException($e);
}

$this->endSetup();



