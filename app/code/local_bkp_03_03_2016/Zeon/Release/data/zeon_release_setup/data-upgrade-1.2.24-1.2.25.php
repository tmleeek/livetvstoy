<?php
$storeId = 3;
$package = 'enterprise/personalizedplanet';
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 2));
foreach ($stores as $value) {
    if ($value->getCode() == 'pp_storeview') {
        $storeId = $value->getStoreId();
    }
}

$blocks = array(
    array(
        'title' => 'Homepage - Best Seller Products - PP',
        'identifier' => 'homepage_best_products',
        'content' => '
    {{block type="zeon_catalogmanager/bestproducts"
    name="bestproducts" as="bestproducts"
    template="zeon/catalogmanager/block_bestproducts.phtml"}}
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 1,
        'widget_title' => 'Homepage - Best Seller Products - PP'
    ),
    array(
        'title' => 'Homepage - Gifts for him - PP',
        'identifier' => 'homepage_gifts_for_him',
        'content' => '
        Your contents goes here
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 2,
        'widget_title' => 'Homepage - Gifts for him - PP'
    ),
    array(
        'title' => 'Homepage - Gifts for her - PP',
        'identifier' => 'homepage_gifts_for_her',
        'content' => '
        Your contents goes here
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 3,
        'widget_title' => 'Homepage - Gifts for her - PP'
    ),
    array(
        'title' => 'Homepage - Gifts for baby - PP',
        'identifier' => 'homepage_gifts_for_baby',
        'content' => '
        Your contents goes here
    ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 4,
        'widget_title' => 'Homepage - Gifts for baby - PP'
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
