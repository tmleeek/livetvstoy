<?php
$installer = $this;
$installer->startSetup();

$installer
    ->getConnection()
    ->addColumn($installer->getTable('personalize'), 'thumbnail_paths', 'text NULL');

$installer->endSetup();
