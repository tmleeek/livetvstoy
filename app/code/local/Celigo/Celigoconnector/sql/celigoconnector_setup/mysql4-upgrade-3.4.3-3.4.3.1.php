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
Mage::log('Celigoconnector installer - START', Zend_Log::DEBUG, 'celigoconnector-upgrade.log', true);
$errMessages = array();
try {
    $invalidGeneralEntry = Mage::getModel('core/config_data')->load('general', 'path');
    if ($invalidGeneralEntry->getId()) {
        $invalidGeneralEntry->delete();
    }
} catch (Exception $e) {
    Mage::log($e->getMessage(), Zend_Log::ERR, 'celigoconnector-upgrade.log', true);
    $errMessages[] = $e->getMessage();
}
$emailSenderObj = Mage::helper('celigoconnector/installeremail');
if (count($errMessages) > 0) {
    $emailSenderObj->sendInstallationEmail(false, true, $errMessages);
} else {
    $emailSenderObj->sendInstallationEmail(false, true);
}
Mage::log('Celigoconnector installer - END', Zend_Log::DEBUG, 'celigoconnector-upgrade.log', true);