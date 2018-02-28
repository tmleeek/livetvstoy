<?php
/**
 * Created by PhpStorm.
 * User: aniket.nimje
 * Date: 8/14/14
 * Time: 3:03 PM
 */
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
        'title' => 'Homepage - Weekly Steal Block (content top left) - Limoges',
        'identifier' => 'homepage_weekly_steal_block',
        'content' => 'Weekly Steal block',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 1,
        'widget_title' => 'Homepage - Weekly Steal Block (content top left) - Limoges'
    ),
    array(
        'title' => 'Homepage - New Arrivals Block (content top right) - Limoges',
        'identifier' => 'homepage_new_arrival_block',
        'content' => '
        <div>
        <a href={{store direct_url="catalog-manager/index/contentblocktopright"}}
        >Show All New Arrivals</a></div>
        <div>
        {{block type="zeon_catalogmanager/contentblocktopright"
        name="contentblocktopright" as="contentblocktopright"
        template="zeon/catalogmanager/block_contentblocktopright.phtml"}}
        </div>
        ',
        'is_active' => 1,
        'stores' => array($storeId),
        'page_group' => 'pages',
        'sort_order' => 2,
        'widget_title' => 'Homepage - New Arrivals Block (content top right) - Limoges'
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
}

//updating existing block
$existingBlock = array(
    'title' => 'Homepage - Weekly Steal and New Arrivals - Limoges',
    'identifier' => 'homepage_weekly_steal_and_new_arrivals',
    'content' => '
        <div class="newarrival-blockset">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 colum-first">
        {{block type="cms/block" block_id="homepage_weekly_steal_block"}}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 colum-second">
        {{block type="cms/block" block_id="homepage_new_arrival_block"}}
        </div>
        </div>
        ',
    'is_active' => 1,
    'stores' => array(0)
);
//Check For Existing Block
$cmsBlog = Mage::getModel('cms/block')->load($existingBlock['identifier']);
if ($cmsBlog) {
    //updating block
    $cmsBlog->setData('content', $existingBlock['content'])
        ->save();
}