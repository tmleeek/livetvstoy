<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */
$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Allow best_seller attribute for sorting purpose.
Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'best_seller')
            ->setData('used_for_sort_by', 1)
            ->save();

// End the installer.
$installer->endSetup();