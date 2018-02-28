<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create bannerslider table
 */
$installer->run(
    "DROP TABLE IF EXISTS {$this->getTable('bannerslider_banner')};
    CREATE TABLE {$this->getTable('bannerslider_banner')} (
        `banner_id` int(11) unsigned NOT NULL auto_increment,
        `name` varchar(255) NOT NULL default '',
        `sort_order` int(11) NOT NULL,
        `status` smallint(6) NOT NULL default '0',
        `click_url` varchar(255) NULL default '',
        `image` varchar(255) NULL,
        `image_alt` varchar(255) NULL,
        `start_time` datetime NULL,
        `end_time` datetime NULL,
        PRIMARY KEY (`banner_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    "
);

$installer->endSetup();