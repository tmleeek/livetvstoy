<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

// Add the Feature Product Position Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'featured_product_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Featured Product Position',
        'input'                   => 'text',
        'class'                   => '',
        'source'                  => '',
        'is_global'               => 0,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => '0',
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => false,
        'unique'                  => false,
        'apply_to'                => $applyTo,
        'is_configurable'         => false,
        'used_in_product_listing' => '1',
    )
);

// Add the Best Seller Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'best_seller_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Best Seller Product Position',
        'input'                   => 'text',
        'class'                   => '',
        'source'                  => '',
        'is_global'               => 0,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => '0',
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => false,
        'unique'                  => false,
        'apply_to'                => $applyTo,
        'is_configurable'         => false,
        'used_in_product_listing' => '1',
    )
);

// End of installer script.
$installer->endSetup();