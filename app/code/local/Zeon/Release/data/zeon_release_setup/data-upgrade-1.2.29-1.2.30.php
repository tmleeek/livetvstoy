<?php
$storeId = 4;
$package = 'enterprise/limoges';
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 3));
foreach ($stores as $value) {
    if ($value->getCode() == 'limoges_store_view') {
        $storeId = $value->getStoreId();
    }
}

$blocks = array(
    array(
        'title' => 'Homepage - Weekly Steal and New Arrivals - Limoges',
        'identifier' => 'homepage_weekly_steal_and_new_arrivals',
        'content' => '
    Weekly Steal + New Arrivals block
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 10,
        'widget_title' => 'Homepage - Weekly Steal and New Arrivals - Limoges'
    ),
    array(
        'title' => 'Homepage - Best Seller Products - Limoges',
        'identifier' => 'homepage_best_products',
        'content' => '
    {{block type="zeon_catalogmanager/bestproducts"
    name="bestproducts" as="bestproducts"
    template="zeon/catalogmanager/block_bestproducts.phtml"}}
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 20,
        'widget_title' => 'Homepage - Best Seller Products - Limoges'
    ),
    array(
        'title' => 'Homepage - Perfect Gift - Limoges',
        'identifier' => 'homepage_perfect_gifts',
        'content' => '
    {{block type="zeon_catalogmanager/perfectgifts"
    name="perfectgifts" as="perfectgifts"
    template="zeon/catalogmanager/block_perfectgifts.phtml"}}
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 30,
        'widget_title' => 'Homepage - Perfect Gift - Limoges'
    ),
    array(
        'title' => 'Homepage - Birthstone of the month - Limoges',
        'identifier' => 'homepage_birthstone_of_month',
        'content' => '
    {{block type="zeon_catalogmanager/birthstone"
    name="birthstone" as="birthstone"
    template="zeon/catalogmanager/block_birthstone.phtml"}}
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 40,
        'widget_title' => 'Homepage - Birthstone of the month - Limoges'
    ),
);
$pageGroupArray = array(
    'pages' => array(
        'page_id'       => 0,
        'for'           => 'all',
        'layout_handle' => 'cms_index_index',
        'block'         => 'content',
        'template'      => 'cms/widget/static_block/default.phtml'
    )
);
//creating block
foreach ($blocks as $block) {
    $blockSave = Mage::getModel('cms/block')
        ->setTitle($block['title'])
        ->setIdentifier($block['identifier'])
        ->setStores(array($storeId))
        ->setContent($block['content'])
        ->setIsActive($block['is_active'])
        ->save();
    $blockId = $blockSave->getId();

    // creating widgets for respective blocks
    $widgetInstance = Mage::getModel('widget/widget_instance')
        ->setData(
            'page_groups',
            array(
                array(
                    'page_group'         => $block['page_group'],
                    $block['page_group'] => $pageGroupArray['pages']
                )
            )
        )
        ->setData('store_ids', array(0))
        ->setData(
            'widget_parameters',
            array(
                'block_id'   => $blockId
            )
        )
        ->addData(
            array(
                'instance_type' => 'cms/widget_block',
                'package_theme' => $package,
                'title'         => $block['widget_title'],
                'sort_order'    => $block['sort_order']
            )
        )
        ->save();
}
