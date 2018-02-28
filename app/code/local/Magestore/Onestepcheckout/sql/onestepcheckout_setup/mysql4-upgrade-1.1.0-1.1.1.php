<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
    $this->getTable('sales_flat_order'), 'onestepcheckout_order_comment', "text NULL");

$installer->endSetup(); 