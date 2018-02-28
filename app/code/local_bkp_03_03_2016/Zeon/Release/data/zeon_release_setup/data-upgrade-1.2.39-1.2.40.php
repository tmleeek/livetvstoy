<?php
/**
 * Created by PhpStorm.
 * User: aniket.nimje
 * Date: 9/3/14
 * Time: 2:39 PM
 */

// create product_flags attribute
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute(
    'catalog_product',
    'product_flags',
    array(
        'input'         => 'multiselect',
        'type'          => 'varchar',
        'attribute_set' => 'Default',
        'label'         => 'Product Flags',
        'group'         => 'CPS Attributes',
        'backend'       => 'eav/entity_attribute_backend_array',
        'source'        => null,
        'entity_model'  => 'catalog/product',
        'global'        => false,
        'visible'       => true,
        'required'      => false,
        'default'       => '',
        'filterable'    => true,
        'is_filterable'    => '1',
        'option'        => array (
            'value' => array(
                'hot-buy' => array('Hot Buy'),
                'top-pick' => array('Top Pick')
            )
        ),
    )
);

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer->startSetup();

// List of
$attributes = array(
    'product_flags'
);
foreach ($attributes as $attribute) {
    $installer->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        $attribute,
        array(
            'is_visible_on_front' => '1',
            'used_in_product_listing' => '1'
        )
    );
}
// End of installer script.
$installer->endSetup();