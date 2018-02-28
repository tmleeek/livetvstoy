<?php

$this->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('newsletter/subscriber');
$cols = $this->getConnection()->fetchCol($fieldsSql);

if (!in_array('created_at', $cols)){
    $this->run("ALTER TABLE `{$this->getTable('newsletter/subscriber')}` ADD `created_at` DATE");
}
 
$this->endSetup();