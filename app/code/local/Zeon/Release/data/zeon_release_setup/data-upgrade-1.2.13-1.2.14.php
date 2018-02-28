<?php
/**
 * This installer script is used to create the various product attributes.
 *
 * @category    Zeon
 * @package     Release
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Add the column to store the sub-title in `catalog_product_bundle_option` table.
$installer
    ->getConnection()
    ->addColumn($installer->getTable('catalog_product_bundle_option'), 'subtitle', 'varchar (255) NULL');

// Add the column to store the is-size-attribute in `catalog_product_bundle_selection` table.
/*$installer
    ->getConnection()
    ->addColumn($installer->getTable('catalog_product_bundle_selection'), 'is_size_attribute', 'varchar (255) NULL');*/

// End of installer script.
$installer->endSetup();