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
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
	Mage::log('Connector installer - START', Zend_Log::DEBUG, 'connector-install.log', true);
	
	/*
	 * Below script to update the old configurations (port) to new configurations to avoid entering the details again
	 */
	try {
		$path = 'port';
		$collection = Mage::getModel('core/config_data')->getCollection()
						->addFieldToFilter('path', array('like' => $path . '/%'));
		if ($collection->count() > 0) {
			foreach ($collection as $coreConfig) {
				$oldPath = $coreConfig->getPath();
				$newPath = str_replace("port", "connector", $oldPath);
				$coreConfig->setPath($newPath);
				
				$oldValue = $coreConfig->getValue();
				$pos = strpos($newPath, "password");
				if ($pos !== false && trim($oldValue) != '') {
					$coreConfig->setValue(Mage::helper('core')->encrypt($oldValue));
				}
				
				$coreConfig->save();
			}		
		}
	} catch (Exception $e) {
		Mage::log($e->getMessage(), Zend_Log::ERR, 'connector-install.log', true);
	}
		
	$coreConfig = Mage::getModel('core/config');
	$currentDate = Mage::getModel('core/date')->date('m/d/Y');
	$coreConfig->saveConfig('connector/cronsettings/startdate', $currentDate, 'default', 0);
	$coreConfig->saveConfig('crontab/jobs/push_orders_to_ns/schedule/cron_expr', '0,30 * * * *', 'default', 0);

	$installer = $this;	
	$installer->startSetup();
		
	try {
	
		$installer->getConnection()
			->addColumn($installer->getTable('sales/order'), 'pushed_to_ns', array(
				'TYPE'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
				'LENGTH'    => 2,
				'NULLABLE'  => false,
				'DEFAULT'  => 0,
				'COMMENT'   => 'Pushed To NetSuite'
			));
			
		Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'connector-install.log', true);
		
		$installer->getConnection()
			->addColumn($installer->getTable('sales/order_grid'), 'pushed_to_ns', array(
				'TYPE'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
				'LENGTH'    => 2,
				'NULLABLE'  => false,
				'DEFAULT'  => 0,
				'COMMENT'   => 'Pushed To NetSuite 0 for not pushed and 1 for pushed. This field is for M Connector Purpose'
			));
			
		Mage::log('pushed_to_ns field added to sales_order_grid table', Zend_Log::DEBUG, 'connector-install.log', true);
		
	} catch(Exception $e) {
	
		try {
		
			$installer->getConnection()
				->addColumn($installer->getTable('sales/order'), 'pushed_to_ns', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
			Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'connector-install.log', true);
			
			$installer->getConnection()
				->addColumn($installer->getTable('sales/order_grid'), 'pushed_to_ns', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
			Mage::log('pushed_to_ns field added to sales_order_grid table', Zend_Log::DEBUG, 'connector-install.log', true);
			
		} catch(Exception $e) {	
			Mage::log($e->getMessage(), Zend_Log::ERR, 'connector-install.log', true);
		}
	}
	
	$installer->endSetup();
	Mage::log('Connector installer - END', Zend_Log::DEBUG, 'connector-install.log', true);	