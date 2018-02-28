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
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

$attributes = array(
    'gender'                  => array('label' => 'Gender'),
    'quantity_party_supplies' => array('label' => 'Quantity (Party Supplies)'),
    'size_apparel'            => array('label' => 'Apparel Size'),
    'size_decor_misc'         => array('label' => 'Décor & Misc Size'),
    'size_shoes'              => array('label' => 'Shoes Size'),
);

foreach ($attributes as $attribute => $attributeDetails) {
    // Add the Atrribute.
    $installer->addAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $attribute,
        array(
            'group'                   => $attributeGroupName,
            'type'                    => 'int',
            'backend'                 => '',
            'frontend'                => '',
            'label'                   => $attributeDetails['label'],
            'input'                   => 'select',
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
            'is_configurable'         => true,
            'used_in_product_listing' => '1',
        )
    );
}

// Allow shop_by_age attribute for sorting purpose.
Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'shop_by_age')
            ->setData(array('is_configurable' => 1, 'user_defined' => 1))
            ->save();

// End of installer script.
$installer->endSetup();