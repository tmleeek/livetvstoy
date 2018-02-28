<?php
/**
 * Orders Export and Import
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitexporter
 * @version      10.1.7
 * @license:     G0SwOduKhxI2ppsFsSxbJansCjYrTVcLKLwIiB2Xt7
 * @copyright:   Copyright (c) 2015 AITOC, Inc. (http://www.aitoc.com)
 */
$installer = $this;

$installer->startSetup();

$installer->run("
CREATE  TABLE IF NOT EXISTS `".$this->getTable('aitexporter_profile')."` (
  `profile_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `store_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0' ,
  `name` VARCHAR(64) NOT NULL,
  `config` text,
  `xsl` text,
  `date` datetime NOT NULL, 
  `flag_auto` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `crondate` datetime DEFAULT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE = InnoDB; 


");

if(!$this->getConnection()->tableColumnExists($this->getTable('aitexporter_export'), 'profile_id')) {
    $installer->run("
    ALTER TABLE `".$this->getTable('aitexporter_export')."` ADD COLUMN `profile_id` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT '0'; 
    ");
}
if(!$this->getConnection()->tableColumnExists($this->getTable('aitexporter_export_order'), 'profile_id')) {
    $installer->run("
    ALTER TABLE `".$this->getTable('aitexporter_export_order')."` ADD COLUMN `profile_id` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT '0'; 
    ALTER TABLE `".$this->getTable('aitexporter_export_order')."` ADD INDEX `profile_id` ( `profile_id` )
    ");
}

$installer->endSetup();