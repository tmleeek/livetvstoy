<?php
/**
 * This installer script is used to create the product attributes for "Ships From" and "Ships To".
 * These attributes will be displayed on the prdocut details and on shopping cart pages.
 * (Usually ships within <ships_from> to <ships_to> Business Days)
 *
 * @category    Zeon
 * @package     Release
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

// Add the Ships From Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ships_from',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Ships From (n) Business Days',
        'input'                   => 'text',
        'class'                   => '',
        'source'                  => '',
        'is_global'               => 0,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => '',
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => true,
        'unique'                  => false,
        'apply_to'                => $applyTo,
        'is_configurable'         => false,
        'used_in_product_listing' => '0',
    )
);

// Add the Ships To Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ships_to',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Ships To (n) Business Days',
        'input'                   => 'text',
        'class'                   => '',
        'source'                  => '',
        'is_global'               => 0,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => '',
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => true,
        'unique'                  => false,
        'apply_to'                => $applyTo,
        'is_configurable'         => false,
        'used_in_product_listing' => '0',
    )
);

// End of installer script.
$installer->endSetup();