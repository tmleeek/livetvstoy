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

ALTER TABLE `'.$this->getTable('aitexporter_import').'` 
    ADD COLUMN `add_count` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    ADD COLUMN `update_count` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    ADD COLUMN `fail_count` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0;

');

$installer->endSetup();