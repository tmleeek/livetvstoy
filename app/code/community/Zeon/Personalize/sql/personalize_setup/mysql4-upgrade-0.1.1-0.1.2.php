<?php
/**
 * Installer file used to create an another column in `personalize` table to save the `item_id` in table.
 */
$installer = $this;
$installer->startSetup();

$installer
    ->getConnection()
    ->addColumn($installer->getTable('personalize'), 'item_id', 'int(11)');

$installer->endSetup();
