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

//delete attributes
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'gender');
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'size_apparel');
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'size_decor_misc');
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'size_shoes');

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

// create age attribute
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'gender',
    array(
        'input'         => 'multiselect',
        'type'          => 'varchar',
        'attribute_set' => 'Default',
        'label'         => 'Gender',
        'group'         => 'CPS Attributes',
        'backend'       => 'eav/entity_attribute_backend_array',
        'source'        => null,
        'entity_model'  => 'catalog/product',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'default'       => '',
        'filterable'    => true,
        'is_filterable' => '1',
        'apply_to'      => $applyTo,
    )
);

// End of installer script.
$installer->endSetup();