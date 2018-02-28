<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */


$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// List of 
$attributes = array(
    'featured_product_position',
    'best_seller_position'
);

foreach ($attributes as $attribute) {
    $installer->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $attribute,
        array('backend_type' => 'int')
    );
}

// End of installer script.
$installer->endSetup();