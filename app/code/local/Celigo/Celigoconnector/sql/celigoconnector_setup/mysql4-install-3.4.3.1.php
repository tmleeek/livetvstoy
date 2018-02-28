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
    $oldpaths = array();
    $oldpaths['connector/magentoconnector/active'] = 'celigoconnector/magentoceligoconnector/active';
    $oldpaths['connector/nsdetails/nsemail'] = 'celigoconnector/nsdetails/nsemail';
    $oldpaths['connector/nsdetails/nspassword'] = 'celigoconnector/nsdetails/nspassword';
    $oldpaths['connector/nsdetails/nscpassword'] = 'celigoconnector/nsdetails/nscpassword';
    $oldpaths['connector/nsdetails/nsrole'] = 'celigoconnector/nsdetails/nsrole';
    $oldpaths['connector/nsdetails/nsaccountid'] = 'celigoconnector/nsdetails/nsaccountid';
    $oldpaths['connector/nsdetails/nsenvironment'] = 'celigoconnector/nsdetails/nsenvironment';
    $oldpaths['connector/othersettings/restleturl'] = 'celigoconnector/othersettings/restleturl';
    $oldpaths['connector/othersettings/customerflowid'] = 'celigoconnector/othersettings/customerflowid';
    $oldpaths['connector/othersettings/orderflowid'] = 'celigoconnector/othersettings/orderflowid';
    $oldpaths['connector/othersettings/batchorderflowid'] = 'celigoconnector/othersettings/batchorderflowid';
    $oldpaths['connector/othersettings/ordercancelflowid'] = 'celigoconnector/othersettings/ordercancelflowid';
    $oldpaths['connector/othersettings/batchordercancelflowid'] = 'celigoconnector/othersettings/batchordercancelflowid';
    $oldpaths['connector/othersettings/orderstatus'] = 'celigoconnector/othersettings/orderstatus';
    $oldpaths['connector/othersettings/allowedmethods'] = 'celigoconnector/othersettings/allowedmethods';
    $oldpaths['connector/othersettings/imported_order_status'] = 'celigoconnector/othersettings/imported_order_status';
    $oldpaths['connector/othersettings/technical_contact_email'] = 'celigoconnector/othersettings/technical_contact_email';
    $oldpaths['connector/othersettings/async_sleep_time'] = 'celigoconnector/othersettings/async_sleep_time';
    $oldpaths['connector/cronsettings/enabled'] = 'celigoconnector/cronsettings/enabled';
    $oldpaths['connector/cronsettings/startdate'] = 'celigoconnector/cronsettings/startdate';
    $oldpaths['connector/cronsettings/frequency'] = 'celigoconnector/cronsettings/frequency';
    $oldpaths['connector/logsettings/enabled'] = 'celigoconnector/logsettings/enabled';
    $oldpaths['connector/logsettings/filename'] = 'celigoconnector/logsettings/filename';
    $oldpaths['port/magentoport/active'] = 'celigoconnector/magentoceligoconnector/active';
    $oldpaths['port/nsdetails/nsemail'] = 'celigoconnector/nsdetails/nsemail';
    $oldpaths['port/nsdetails/nspassword'] = 'celigoconnector/nsdetails/nspassword';
    $oldpaths['port/nsdetails/nscpassword'] = 'celigoconnector/nsdetails/nscpassword';
    $oldpaths['port/nsdetails/nsrole'] = 'celigoconnector/nsdetails/nsrole';
    $oldpaths['port/nsdetails/nsaccountid'] = 'celigoconnector/nsdetails/nsaccountid';
    $oldpaths['port/nsdetails/nsenvironment'] = 'celigoconnector/nsdetails/nsenvironment';
    $oldpaths['port/othersettings/restleturl'] = 'celigoconnector/othersettings/restleturl';
    $oldpaths['port/othersettings/customerflowid'] = 'celigoconnector/othersettings/customerflowid';
    $oldpaths['port/othersettings/orderflowid'] = 'celigoconnector/othersettings/orderflowid';
    $oldpaths['port/othersettings/orderstatus'] = 'celigoconnector/othersettings/orderstatus';
    $oldpaths['port/cronsettings/enabled'] = 'celigoconnector/cronsettings/enabled';
    $oldpaths['port/cronsettings/startdate'] = 'celigoconnector/cronsettings/startdate';
    $oldpaths['port/cronsettings/frequency'] = 'celigoconnector/cronsettings/frequency';
    $oldpaths['port/logsettings/enabled'] = 'celigoconnector/logsettings/enabled';
    $oldpaths['port/logsettings/filename'] = 'celigoconnector/logsettings/filename';
    foreach ($oldpaths as $oldpath => $newpath) {
        $oldConfig = Mage::getModel('core/config_data')->load($oldpath, 'path');
        $newConfig = Mage::getModel('core/config_data')->load($newpath, 'path');
        if ($oldConfig->getId() && !$newConfig->getId()) {
            $oldConfig->setPath($newpath);
            try {
                $oldConfig->save();
            } catch (Exception $e) {
                Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnector-install.log', true);
            }
        }
    }

    $cronStartDate = Mage::getModel('core/config_data')->load('celigoconnector/cronsettings/startdate', 'path');
    if (!$cronStartDate->getId()) {
        $currentDate = Mage::getModel('core/date')->date('m/d/Y');
        Mage::getModel('core/config')->saveConfig('celigoconnector/cronsettings/startdate', $currentDate, 'default', 0);
    }

    $cronExpression = Mage::getModel('core/config_data')->load('celigoconnector/cronsettings/startdate', 'path');
    if (!$cronExpression->getId()) {
        Mage::getModel('core/config')->saveConfig('crontab/jobs/push_orders_to_ns/schedule/cron_expr', '0,30 * * * *', 'default', 0);
    }

    $oldLogName = Mage::getModel('core/config_data')->load('celigoconnector/logsettings/filename', 'path');
    if ($oldLogName->getId() && trim(strtolower($oldLogName->getValue())) == 'celigoconnector-error.log') {
        $oldLogName->setValue('celigo-magento-celigoconnector.log');
        $oldLogName->save();
    }
} catch (Exception $e) {
    Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnector-install.log', true);
    $errMessages[] = $e->getMessage();
}
$installer = $this;
$installer->startSetup();
/* Add the field to order table if not exists */
try {
    $installer->getConnection()->addColumn($installer->getTable('sales/order'), 'pushed_to_ns', array(
        'TYPE' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'LENGTH' => 2,
        'NULLABLE' => false,
        'DEFAULT' => 0,
        'COMMENT' => 'Pushed To NetSuite'
    ));
    Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
} catch (Exception $e) {
    try {
        $installer->getConnection()->addColumn($installer->getTable('sales/order'), 'pushed_to_ns', "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Pushed To NetSuite'");
        Mage::log('pushed_to_ns field added to sales_order table', Zend_Log::DEBUG, 'celigoconnector-install.log', true);
    } catch (Exception $e) {
        Mage::log($e, Zend_Log::ERR, 'celigoconnector-install.log', true);
        Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnector-install.log', true);
        $errMessages[] = $e->getMessage();
    }
}
/* Remove the field from grid tables if exists */
try {
    if ($installer->getConnection()->isTableExists($installer->getTable('sales/order_grid'))) {
        if ($installer->getConnection()->tableColumnExists($installer->getTable('sales/order_grid'), 'pushed_to_ns')) {
            $installer->getConnection()->dropColumn($installer->getTable('sales/order_grid'), 'pushed_to_ns');
        }
        if ($installer->getConnection()->tableColumnExists($installer->getTable('sales/order_grid'), 'cancelled_in_netsuite')) {
            $installer->getConnection()->dropColumn($installer->getTable('sales/order_grid'), 'cancelled_in_netsuite');
        }
    }
    if ($installer->getConnection()->isTableExists($installer->getTable('sales/creditmemo_grid'))) {
        if ($installer->getConnection()->tableColumnExists($installer->getTable('sales/creditmemo_grid'), 'is_imported')) {
            $installer->getConnection()->dropColumn($installer->getTable('sales/creditmemo_grid'), 'is_imported');
        }
    }

    // Check this in Community edition
    $mageObj = new Mage();
    if (method_exists($mageObj, 'getEdition')) {
        $current_edition = Mage::getEdition();
        if ($current_edition == Mage::EDITION_ENTERPRISE) {
            if ($installer->getConnection()->isTableExists($installer->getTable('enterprise_salesarchive/creditmemo_grid'))) {
                if ($installer->getConnection()->tableColumnExists($installer->getTable('enterprise_salesarchive/creditmemo_grid'), 'is_imported')) {
                    $installer->getConnection()->dropColumn($installer->getTable('enterprise_salesarchive/creditmemo_grid'), 'is_imported');
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
if (count($errMessages) > 0) {
    $emailSenderObj->sendInstallationEmail(false, false, $errMessages);
} else {
    $emailSenderObj->sendInstallationEmail();
}