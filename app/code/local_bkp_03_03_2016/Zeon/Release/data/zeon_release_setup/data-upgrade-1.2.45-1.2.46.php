<?php
/**
 * This installer script is used to create the various product attributes.
 *
 * @category    Zeon
 * @package     Release
 * @author      Sandhya Sharma <sandhya.sharma@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';
$applyTo = 'simple,configurable,virtual,bundle,downloadable';

$attributes = array(
    'is_shared_custom_option'  => array(
        'group' => $attributeGroupName,
        'label' => 'Is Shared Custom Option',
        'type' => 'int',
        'attribute_set' => 'Default',
        'input' => 'boolean',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'required'                => false,
    ),
    'shared_custom_option_ids'  => array(
        'group' => $attributeGroupName,
        'label' => 'Shared Custom Option Ids',
        'type' => 'varchar',
        'attribute_set' => 'Default',
        'input' => 'text',
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'required'                => false,
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
    'required'                => true,
    'user_defined'            => true,
    'default'                 => '',
    'searchable'              => true,
    'filterable'              => true,
    'is_filterable'           => true,
    'comparable'              => false,
    'is_visible_on_front'     => true,
    'unique'                  => false,
    'apply_to'                => $applyTo,
    'is_configurable'         => false,
    'used_in_product_listing' => '1',
);

foreach ($attributes as $attribute => $attributeDetails) {
    //check if attribute present
    $attId = Mage::getModel('catalog/resource_eav_attribute')
        ->loadByCode('catalog_product', $attribute)->getId();
    if (!$attId) {
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