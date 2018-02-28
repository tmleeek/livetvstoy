<?php
/**
 * This installer script is used to create the product attributes "legacy_product_id".
 * This attribute will be used to map the old product (from existing site) with the new one.
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
    'legacy_product_id',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'varchar',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Legacy Product ID',
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