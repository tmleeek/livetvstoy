<?php
/**
 * This installer script is used to create the various product attributes.
 *
 * @category    Zeon
 * @package     Release
 * @author      Nilesh Tighare <nilesh.tighare@zeonsolutions.com>
 */
$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

$attributes = array(
    'metal'  => array(
        'group' => $attributeGroupName,
        'label' => 'Metal',
        'type' => 'varchar',
        'input' => 'text',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '0'
    ),
    'ring_size'  => array(
        'group' => $attributeGroupName,
        'label' => 'Ring Size',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
    ),
    'birthstones'  => array(
        'group' => $attributeGroupName,
        'label' => 'Birthstones',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
    ),
    'birthstone_month'  => array(
        'group' => $attributeGroupName,
        'label' => 'Birthstone Month',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
    ),
    'alpha'  => array(
        'group' => $attributeGroupName,
        'label' => 'Alpha',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
    ),
    'zodiac'  => array(
        'group' => $attributeGroupName,
        'label' => 'Zodiac',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
    ),
    'year'  => array(
        'group' => $attributeGroupName,
        'label' => 'Year',
        'type' => 'int',
        'input' => 'select',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'is_configurable'         => true,
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
    'is_visible_on_front'        => true,
    'unique'                  => false,
    'apply_to'                => $applyTo,
    'is_configurable'         => false,
    'used_in_product_listing' => '1',
);

foreach ($attributes as $attribute => $attributeDetails) {
    //check if attribute present
    $attId = Mage::getModel('catalog/resource_eav_attribute')
        ->loadByCode('catalog_product', $attribute)->getId();
    if(!$attId) {
        $resultData = array_merge($attributeData, $attributeDetails);
        // Add the Atrribute.
        $installer->addAttribute(
            Mage_Catalog_Model_Product::ENTITY,
            $attribute,
            $resultData
        );
    } else {
        $installer->updateAttribute(
            Mage_Catalog_Model_Product::ENTITY,
            $attribute,
            $attributeDetails
        );
    }
}

// End of installer script.
$installer->endSetup();