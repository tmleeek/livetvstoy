<?php
$installer = $this;
$installer->startSetup();

// Get store ids
$storeId = 5;//for lemoges store

//create store tables
$tableName = $installer->getTable(
    array('zeon_attributemapping/attributemapping', $storeId)
);
$tableCreate = "DROP TABLE IF EXISTS {$tableName};
    CREATE TABLE `{$tableName}` ( "
    . "`mapping_id` int(10) unsigned NOT NULL AUTO_INCREMENT "
    . "COMMENT 'Attribute Mapping Id',"
    . "`attribute_id` smallint(5) unsigned NOT NULL "
    . "COMMENT 'Attribute Id',"
    . "`option_id` smallint(5) unsigned NOT NULL "
    . "COMMENT 'Attribute Option Id',"
    . "`option_status` smallint(5) NOT NULL default '2' "
    . "COMMENT 'status of attribute option',"
    . "`url_key` varchar(255) DEFAULT NULL COMMENT 'Url key',"
    . "`display_in_slider` boolean DEFAULT FALSE,"
    . "`sort_order` int(11) DEFAULT NULL "
    . "COMMENT 'sort order on slider',"
    . "`slider_image` varchar(255) DEFAULT NULL,"
    . "`logo_image` varchar(255) DEFAULT NULL,"
    . "`page_background_image` varchar(255) NULL,"
    . "`description` text DEFAULT NULL COMMENT 'Description',"
    . "`meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Keywords',"
    . "`meta_keywords` text COMMENT 'Meta Keywords',"
    . "`meta_description` text COMMENT 'Meta Description',"
    . "PRIMARY KEY (`mapping_id`),"
    . "UNIQUE KEY `mapping_index` (`attribute_id`,`option_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Mapping table'";
$installer->run($tableCreate);

$installer->endSetup();