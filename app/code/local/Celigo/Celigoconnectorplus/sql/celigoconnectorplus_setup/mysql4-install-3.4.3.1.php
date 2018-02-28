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
 * @package     Celigo_Celigoconnectorplus
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$errMessages = array();
Mage::log('Celigoconnector Plus installer - START', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
$installer = $this;
$installer->startSetup();
try {
    $collection = Mage::getModel('core/config_data')->getCollection()->addFieldToFilter('path', array(
        'like' => 'carriers/customshipping/model'
            ));
    if ($collection->count() > 0) {
        foreach ($collection as $coreConfig) {
            $oldValue = $coreConfig->getValue();
            if (trim(strtolower($oldValue)) != 'celigoconnectorplus/carrier_shippingmethod') {
                $coreConfig->setValue('celigoconnectorplus/carrier_shippingmethod')->save();
            }
        }
    }

    $cronExpression = Mage::getModel('core/config_data')->load('crontab/jobs/push_cancelled_orders_to_ns/schedule/cron_expr', 'path');
    if (!$cronExpression->getId()) {
        $coreConfig = Mage::getModel('core/config')->saveConfig('crontab/jobs/push_cancelled_orders_to_ns/schedule/cron_expr', '0,30 * * * *', 'default', 0);
    }

    $installer->getConnection()->addColumn($installer->getTable('sales/order'), 'cancelled_in_netsuite', array(
        'TYPE' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'LENGTH' => 2,
        'NULLABLE' => false,
        'DEFAULT' => 0,
        'COMMENT' => '0 for not Canceled In NetSuite and 1 for Canceled In NetSuite'
    ));
    Mage::log('cancelled_in_netsuite field added to sales_order table', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
    $installer->getConnection()->addColumn($installer->getTable('sales/creditmemo'), 'is_imported', array(
        'TYPE' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'LENGTH' => 2,
        'NULLABLE' => false,
        'DEFAULT' => 0,
        'COMMENT' => '0 for not imported and 1 for imported to NetSuite.'
    ));
    Mage::log('is_imported field added to sales_creditmemo table', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
} catch (Exception $e) {
    try {
        $installer->getConnection()->addColumn($installer->getTable('sales/order'), 'cancelled_in_netsuite', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
        Mage::log('cancelled_in_netsuite field added to sales_order table', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
        $installer->getConnection()->addColumn($installer->getTable('sales/creditmemo'), 'is_imported', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
        Mage::log('is_imported field added to sales_creditmemo table', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
    } catch (Exception $e) {
        Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnectorplus-install.log', true);
        $errMessages[] = $e->getMessage();
    }
}
$installer->endSetup();
Mage::log('Celigoconnector Plus installer - END', Zend_Log::DEBUG, 'celigoconnectorplus-install.log', true);
$emailSenderObj = Mage::helper('celigoconnector/installeremail');
if (count($errMessages) > 0) {
    $emailSenderObj->sendInstallationEmail(true, false, $errMessages);
} else {
    $emailSenderObj->sendInstallationEmail(true);
}