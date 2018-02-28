<?php
/**
 * This installer script is used to create the product attributes for "Expedited Shipping".
 * These attributes will be displayed on shopping cart pages.
 *
 * @category    Zeon
 * @package     Release
 * @author      Aniket Nimje <aniket.nimje@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

$attributes = array(
    'expedited_shipping'  => array(
        'group' => $attributeGroupName,
        'label' => 'Expedited Shipping',
        'type' => 'int',
        'input' => 'boolean',
        'default'  => '0',
        'required' => true,
        'source' => 'eav/entity_attribute_source_boolean',
        'visible_on_front' => true,
        'used_in_product_listing' => '0'
    )
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