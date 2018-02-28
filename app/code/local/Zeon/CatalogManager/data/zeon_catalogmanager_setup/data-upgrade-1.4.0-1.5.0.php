<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Aniket Nimje <aniket.nimje@zeonsolutions.com>
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
    'content_top_right',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Content top right product',
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
// Add the Feature Product Position Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'content_top_right_position',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Content top right product',
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
// End of installer script.
$installer->endSetup();
