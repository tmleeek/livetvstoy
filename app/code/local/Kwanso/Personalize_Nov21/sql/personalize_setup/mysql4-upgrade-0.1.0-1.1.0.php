<?php

$this->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('smashingmagazinereport_branddirectory/brand');
$cols = $this->getConnection()->fetchCol($fieldsSql);

if (!in_array('options_json', $cols)){
    $this->run("ALTER TABLE `{$this->getTable('smashingmagazinereport_branddirectory/brand')}` ADD `options_json` TEXT");
}
 
$this->endSetup();