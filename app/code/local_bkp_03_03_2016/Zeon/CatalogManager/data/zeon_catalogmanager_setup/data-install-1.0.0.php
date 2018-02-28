<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

$applyTo = 'simple,configurable,virtual,bundle,downloadable';

// Add the Feature Product Atrribute.
$installer->addAttribute(
    'catalog_product',
    'featured_product',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Featured Product',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => '1'
    )
);

// Add the Best Seller Atrribute.
$installer->addAttribute(
    'catalog_product',
    'best_seller',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Best Seller Product',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => '1'
    )
);

// Add the Most Popular Product Atrribute.
$installer->addAttribute(
    'catalog_product',
    'most_popular',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Most Popular Product',
        'input'                   => 'boolean',
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
        'used_in_product_listing' => '1'
    )
);

$blocks = array (
    array(
        'title'      => 'Homepage - Best Seller Products',
        'identifier' => 'homepage_best_products',
        'sort'       => 0,
        'content'    => '{{block
            type="zeon_catalogmanager/bestproducts"
            name="bestproducts"
            as="bestproducts"
            template="zeon/catalogmanager/block_bestproducts.phtml"}}'
    ),
    array(
        'title'      => 'Homepage - Featured Products',
        'identifier' => 'homepage_featured_products',
        'sort'       => 1,
        'content'    => '{{block
            type="zeon_catalogmanager/featuredproducts"
            name="featuredproducts"
            as="featuredproducts"
            template="zeon/catalogmanager/block_featuredproducts.phtml"}}'
    ),
);

// Get all the stores.
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 0))
    ->getAllIds();

// Var to store the package/theme names.
$packageName = array(
    0 => 'enterprise/tystoybox',
    1 => 'enterprise/pbskids',
);

// Loop on all the stores to create the block for each store.
foreach ($stores as $sortOrder => $store) {
    foreach ($blocks as $block) {
        $blockModel = Mage::getModel('cms/block');
        $blockModel->setTitle($block['title']);
        $blockModel->setIdentifier($block['identifier']);
        $blockModel->setStores(array($store));
        $blockModel->setIsActive(1);
        $blockModel->setContent($block['content']);
        $blockModel->save();

        $pageGroupArray = array(
            'page_id'       => 0,
            'for'           => 'all',
            'layout_handle' => 'cms_index_index',
            'block'         => 'content',
            'template'      => 'cms/widget/static_block/default.phtml'
        );

        // Creating widgets for respective blocks
        Mage::getModel('widget/widget_instance')
            ->setData(
                'page_groups',
                array(
                    array(
                        'page_group' => 'pages',
                        'pages'      => $pageGroupArray
                    )
                )
            )
            ->setData('store_ids', array($store))
            ->setData(
                'widget_parameters',
                array('block_id' => $blockModel->getId())
            )
            ->addData(
                array(
                    'instance_type' => 'cms/widget_block',
                    'package_theme' => $packageName[$sortOrder],
                    'title'         => $block['title'],
                    'sort_order'    => $block['sort']
                )
            )
            ->save();
    }
}

// End of installer script.
$installer->endSetup();