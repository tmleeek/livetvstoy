<?php

$installer = $this;

$installer->startSetup();

if (Mage::helper('crmcore')->isColumnExisted('admin_user', 'crm4e_startup_page_usedefault')) {
    $installer->run("ALTER TABLE {$this->getTable('admin_user')}
            DROP COLUMN `crm4e_startup_page_usedefault`;");
}

if (Mage::helper('crmcore')->isColumnExisted('admin_user', 'crm4e_startup_page')) {
    $installer->run("ALTER TABLE {$this->getTable('admin_user')}
            DROP COLUMN `crm4e_startup_page`;");
}

$installer->run("

ALTER TABLE {$this->getTable('admin_user')}
	    	ADD `crm4e_startup_page_usedefault` smallint(6) NOT NULL default '1',
                ADD `crm4e_startup_page` text NOT NULL default '';

    ");

$installer->endSetup();
