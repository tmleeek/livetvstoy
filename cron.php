<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
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
 * @package     Mage
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

// Change current directory to the directory of current script
chdir(dirname(__FILE__));

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

Mage::app('admin')->setUseSessionInUrl(false);

umask(0);

// Detect Unix-like operating system including OS X, not confusing it with Windows
$isUnix = !preg_match('/(?<!dar)win/i', PHP_OS);

$disabledFuncs = explode(',', ini_get('disable_functions'));
$isShellDisabled = is_array($disabledFuncs) ? in_array('shell_exec', $disabledFuncs) : true;
$isShellDisabled = $isUnix ? $isShellDisabled : true;

// Parse command line arguments
$options = getopt('m::');
$cronMode = isset($options['m']) ? $options['m'] : '';

try {
    if (!$cronMode && !$isShellDisabled) {
        // Spawn parallel background processes for predefined cron modes
	$fileName = escapeshellarg(basename(__FILE__));
        $cronPath = escapeshellarg(dirname(__FILE__) . '/cron.sh');
        shell_exec(escapeshellcmd("/bin/sh $cronPath $fileName -mdefault 1 > /dev/null 2>&1 &"));
        shell_exec(escapeshellcmd("/bin/sh $cronPath $fileName -malways 1 > /dev/null 2>&1 &"));	
        return;
    }
    $config = Mage::getConfig()->init();
    $config->loadEventObservers('crontab');
    Mage::app()->addEventArea('crontab');
    if ($cronMode) {
        // Execute specified cron mode within the current process
        $allowedCronModes = array_keys((array)$config->getNode('crontab/events')->children());
        if (!in_array($cronMode, $allowedCronModes)) {
            Mage::throwException('Unrecognized cron mode was defined');
        }
        Mage::dispatchEvent($cronMode);
    } else {
        // Execute predefined cron modes consecutively within the current process
        Mage::dispatchEvent('always');
        Mage::dispatchEvent('default');
    }
} catch (Exception $e) {
    Mage::printException($e);
    exit(1);
}
