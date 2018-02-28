<?php
/**
 * This installer script is used to create the product attributes for "UPC".
 *
 * @category    Zeon
 * @package     Release
 * @author      Aniket Nimje <aniket.nimje@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

$code = 'upc';
$isExist = Mage::getModel('catalog/resource_eav_attribute')
    ->loadByCode('catalog_product',$code);

if (null===$isExist->getId()) {

    // Attribute Group Name.
    $attributeGroupName = 'General';

    $applyTo = 'simple,configurable,virtual,bundle,downloadable';

    // Add the Ships From Atrribute.
    $installer->addAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $code,
        array(
            'group'                   => $attributeGroupName,
            'type'                    => 'varchar',
            'backend'                 => '',
            'frontend'                => '',
            'label'                   => 'UPC',
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
}