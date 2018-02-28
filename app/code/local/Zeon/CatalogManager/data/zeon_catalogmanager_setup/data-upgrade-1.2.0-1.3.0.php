<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <sushil.zore@zeonsolutions.com>
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
    'gift_for_him_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Him Position',
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
        'used_in_product_listing' => true,
    )
);

// Add the Best Seller Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gift_for_him',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Him',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => true,
    )
);

// Add the Feature Product Position Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gift_for_her_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Her Position',
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
        'used_in_product_listing' => true,
    )
);

// Add the Best Seller Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gift_for_her',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Her',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => true,
    )
);


// Add the Feature Product Position Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gift_for_baby_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Baby Position',
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
        'used_in_product_listing' => true,
    )
);

// Add the Best Seller Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gift_for_baby',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Gifts For Baby',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => true,
    )
);
// End of installer script.
$installer->endSetup();
