<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create bannerslider table
 */
$installer->run(
    "
    CREATE TABLE {$this->getTable('bannerslider_banner_stores')} (
        `id` int(11) unsigned NOT NULL auto_increment,
        `banner_id` smallint(6) NOT NULL,
        `store_id` smallint(6) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    "
);

$installer->endSetup();