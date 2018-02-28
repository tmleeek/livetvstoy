<?php

$installer = $this;

$installer->startSetup();

$installer->run(
    "-- DROP TABLE IF EXISTS {$this->getTable('contactus')};
    CREATE TABLE {$this->getTable('contactus')} (
      `contactus_id` int(11) unsigned NOT NULL auto_increment,
      `name` varchar(255) NOT NULL default '',
      `email` varchar(255) NOT NULL default '',
      `phone` varchar(255) NOT NULL default '',
      `message` text NOT NULL default '',
      `contacted_on` datetime NULL,
      PRIMARY KEY (`contactus_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    "
);

$installer->endSetup();