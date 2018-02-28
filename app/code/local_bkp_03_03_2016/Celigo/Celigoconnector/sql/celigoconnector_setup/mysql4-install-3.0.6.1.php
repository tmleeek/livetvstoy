<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Celigo
 * @package     Celigo_Celigoconnector
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
Mage::log('Celigoconnector installer - START', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
$errMessages = array();
/*
 * Below script to update the old configurations (port/connector) to new configurations to avoid entering the details again
*/
try {
    $oldpaths = array('port', 'connector');
	foreach($oldpaths as $path) {
		$collection = Mage::getModel('core/config_data')->getCollection()->addFieldToFilter('path', array(
			'like' => $path . '/%'
		));
		if ($collection->count() > 0) {
			
			foreach ($collection as $coreConfig) {
				$oldPath = $coreConfig->getPath();
				$newPath = str_replace($path, "celigoconnector", $oldPath);
				$coreConfig->setPath($newPath);
				$coreConfig->save();
			}
		}
	}
	$coreConfig = Mage::getModel('core/config');
	$currentDate = Mage::getModel('core/date')->date('m/d/Y');
	$coreConfig->saveConfig('celigoconnector/cronsettings/startdate', $currentDate, 'default', 0);
	$coreConfig->saveConfig('crontab/jobs/push_orders_to_ns/schedule/cron_expr', '0,30 * * * *', 'default', 0);
	
    $oldLogName = Mage::getModel('core/config_data')->load('celigoconnector/logsettings/filename', 'path')->getValue();
    if (trim(strtolower($oldLogName)) == 'celigoconnector-error.log') {
		$coreConfig = Mage::getModel('core/config');
		$coreConfig->saveConfig('celigoconnector/logsettings/filename', 'celigo-magento-celigoconnector.log', 'default', 0);
    }
	
}
catch(Exception $e) {
    Mage::log($e->getMessage() , Zend_Log::ERR, 'celigoconnector-install.log', true);
	$errMessages[] = $e->getMessage();
}
$installer = $this;
$installer->startSetup();
/* Add the field to order table if not exists */
try {
    $installer->getConnection()->addColumn($installer->getTable('sales/order') , 'pushed_to_ns', array(
        'TYPE' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'LENGTH' => 2,
        'NULLABLE' => false,
        'DEFAULT' => 0,
        'COMMENT' => 'Pushed To NetSuite'
    ));
    Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
}
catch(Exception $e) {
    try {
        $installer->getConnection()->addColumn($installer->getTable('sales/order') , 'pushed_to_ns', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
        Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
    }
    catch(Exception $e) {
        Mage::log($e, Zend_Log::ERR, 'celigoconnector-install.log', true);
        Mage::log($e->getMessage() , Zend_Log::ERR, 'celigoconnector-install.log', true);
		$errMessages[] = $e->getMessage();
    }
}
/* Remove the field from grid tables if exists */
try {
	$dbname = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
	$remove_old_columns = array(
		'sales/order_grid' => array(
			"columns" => array(
				'pushed_to_ns',
				'cancelled_in_netsuite'
			),
			"isEnterpriseField" => false
		),
		'sales/creditmemo_grid' => array(
			"columns" => array(
				'is_imported'
			),
			"isEnterpriseField" => false
		),
		'enterprise_salesarchive/creditmemo_grid' => array(
			"columns" => array(
				"is_imported"
			),
			"isEnterpriseField" => true
		)
	);
    foreach ($remove_old_columns as $old_table => $old_columns) {
        if (is_array($old_columns)
				&& isset($old_columns["columns"])
				&& is_array($old_columns["columns"])
				&& isset($old_columns["isEnterpriseField"])
        ) {
            $isEnterpriseField = $old_columns["isEnterpriseField"];
            foreach ($old_columns["columns"] as $column_name) {
                $execute = false;
                if (!$isEnterpriseField) {
                    $execute = true;
                } else {
                    $mageObj = new Mage();
                    if (method_exists($mageObj, 'getEdition')) {
                        $current_edition = Mage::getEdition();
                        if ($isEnterpriseField && $current_edition == Mage::EDITION_ENTERPRISE) {
                            $execute = true;
                        }
                    }
                }
                if ($execute) {
                    $column_check_query = "SELECT ORDINAL_POSITION FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $dbname . "' AND TABLE_NAME = '" . $this->getTable($old_table) . "' AND COLUMN_NAME = '" . $column_name . "'";
                    if ($installer->getConnection()->fetchOne($column_check_query)) {
                        $dropQuery = "ALTER TABLE " . $this->getTable($old_table) . " DROP " . $column_name;
                        $installer->run($dropQuery);
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnector-install.log', true);
	$errMessages[] = $e->getMessage();
}
$installer->endSetup();
Mage::log('Celigoconnector installer - END', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
$emailSenderObj = Mage::helper('celigoconnector/installeremail');
if(count($errMessages) > 0) {
	$emailSenderObj->sendInstallationEmail(false, false, $errMessages);
} else {
	$emailSenderObj->sendInstallationEmail();
}