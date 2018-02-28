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

// Attribute Group Name.
$attributeGroupName = 'Netsuite Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

$attributes = array(
    'cps_invenotry_item_size_list'  => array(
        'group' => $attributeGroupName,
        'label' => 'CPS Invenotry Item Size List',
        'type' => 'int',
        'input' => 'select',
        'visible_on_front' =>false,
        'used_in_product_listing' => '0'
    ),
    'subitem_of'  => array(
        'group' => $attributeGroupName,
        'label' => 'Subitem of',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' =>false,
        'used_in_product_listing' => '0'
    ),
    'unit_of_measurement'  => array(
        'group' => $attributeGroupName,
        'label' => 'Unit Size',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' =>true,
        'used_in_product_listing' => '0'
    ),
    'lead_time'  => array(
        'group' => $attributeGroupName,
        'label' => 'Lead Time',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' =>true,
        'used_in_product_listing' => '0'
    ),
    'supplier'  => array(
        'group' => $attributeGroupName,
        'label' => 'Vendor Name',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' =>false,
        'used_in_product_listing' => '0'
    ),
    'supplier_sku'  => array(
        'group' => $attributeGroupName,
        'label' => 'Vendor Code',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' =>false,
        'used_in_product_listing' => '0'
    ),
    'purchase_price'  => array(
        'group' => $attributeGroupName,
        'label' => 'Purchase Price',
        'type' => 'decimal',
        'input' => 'price',
        'visible_on_front' => false,
        'used_in_product_listing' => '0'
    ),
    'expected_backorder_shipdate'  => array(
        'group' => $attributeGroupName,
        'label' => 'PO Expected Ship on Back Order',
        'type' => 'datetime',
        'input' => 'date',
        'visible_on_front' => true,
        'used_in_product_listing' => '0'
    ),
    'map_pricing'  => array(
        'group' => $attributeGroupName,
        'label' => 'Map Pricing',
        'type' => 'int',
        'input' => 'boolean',
        'default'  => '0',
        'required' => true,
        'source' => 'eav/entity_attribute_source_boolean',
        'visible_on_front' =>true,
        'used_in_product_listing' => '1'
    ),
    'drop_ship_item'  => array(
        'group' => $attributeGroupName,
        'label' => 'Drop Ship Item',
        'type' => 'int',
        'input' => 'boolean',
        'default'  => '0',
        'required' => true,
        'source' => 'eav/entity_attribute_source_boolean',
        'visible_on_front' =>true,
        'used_in_product_listing' => '1'
    ),
    'retail_rate'  => array(
        'group' => 'Prices',
        'label' => 'Royalty % (Retail Rate)',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => false,
        'used_in_product_listing' => '0'
    ),
    'drop_ship_fee_per_item'  => array(
        'group' => 'Prices',
        'label' => 'Drop Ship Fee (per Item)',
        'type' => 'decimal',
        'input' => 'price',
        'visible_on_front' => false,
        'used_in_product_listing' => '0'
    ),
    'drop_ship_fee_per_order'  => array(
        'group' => 'Prices',
        'label' => 'Drop Ship Fee (per Sales Order)',
        'type' => 'decimal',
        'input' => 'price',
        'visible_on_front' => false,
        'used_in_product_listing' => '0'
    ),
    'drop_ship_personalization_fee'  => array(
        'group' => 'Prices',
        'label' => 'Drop Ship Personalization Fee',
        'type' => 'decimal',
        'input' => 'price',
        'visible_on_front' => false,
        'used_in_product_listing' => '0'
    ),

    'bullet_point1'  => array(
        'group' => 'General',
        'label' => 'Bullet Point 1',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => true,
        'used_in_product_listing' => '0',
        'sort_order'    => '4',
    ),
    'bullet_point2'  => array(
        'group' => 'General',
        'label' => 'Bullet Point 2',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => true,
        'used_in_product_listing' => '0',
        'sort_order'    => '4',
    ),
    'bullet_point3'  => array(
        'group' => 'General',
        'label' => 'Bullet Point 3',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => true,
        'used_in_product_listing' => '0',
        'sort_order'    => '4',
    ),
    'bullet_point4'  => array(
        'group' => 'General',
        'label' => 'Bullet Point 4',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => true,
        'used_in_product_listing' => '0',
        'sort_order'    => '4',
    ),
    'bullet_point5'  => array(
        'group' => 'General',
        'label' => 'Bullet Point 5',
        'type' => 'varchar',
        'input' => 'text',
        'visible_on_front' => true,
        'used_in_product_listing' => '0',
        'sort_order'    => '4',
    ),
);

$attributeData = array(
            'group'                   => '',
            'type'                    => '',
            'backend'                 => '',
            'frontend'                => '',
            'label'                   => '',
            'input'                   => '',
            'class'                   => '',
            'source'                  => '',
            'is_global'               => 1,
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => true,
            'default'                 => '',
            'searchable'              => true,
            'filterable'              => true,
            'is_filterable'           => true,
            'comparable'              => false,
            'visible_on_front'        => true,
            'unique'                  => false,
            'apply_to'                => $applyTo,
            'is_configurable'         => false,
            'used_in_product_listing' => '1',
        );

foreach ($attributes as $attribute => $attributeDetails) {
    $resultData = array_merge($attributeData, $attributeDetails);
   // Add the Atrribute.
    $installer->addAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $attribute,
        $resultData
    );
}

// End of installer script.
$installer->endSetup();