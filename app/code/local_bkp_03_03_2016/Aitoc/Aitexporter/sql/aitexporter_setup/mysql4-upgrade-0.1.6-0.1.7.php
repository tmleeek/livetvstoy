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

$installer->run('

ALTER TABLE `'.$this->getTable('aitexporter_export').'` ADD COLUMN `store_id` SMALLINT UNSIGNED NOT NULL DEFAULT "0";

ALTER TABLE `'.$this->getTable('aitexporter_export').'` ADD CONSTRAINT `aitexporter_export2store` FOREIGN KEY `aitexporter_export2store` (`store_id`)
    REFERENCES `'.$this->getTable('core_store').'` (`store_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

');

$installer->endSetup();