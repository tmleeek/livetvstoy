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

// List of
$attributes = array(
    'unit_of_measurement',
    'lead_time',
    'expected_backorder_shipdate',
    'map_pricing',
    'drop_ship_item',
    'bullet_point1',
    'bullet_point2',
    'bullet_point3',
    'bullet_point4',
    'bullet_point5'
);

foreach ($attributes as $attribute) {
    $installer->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $attribute,
        array(
            'is_visible_on_front' => '1',
            'used_in_product_listing' => '1'
        )
    );
}

// End of installer script.
$installer->endSetup();