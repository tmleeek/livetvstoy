<?php
//upgrade script for creating static pages for limoges store

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

$data= array(
        'title' => 'Sizing Information',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/personalized',
        'content_heading' => 'Sizing Information',
        'stores' => array($storeId),
        'content' => 'Coming Soon',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 27
    );
Mage::getModel('cms/page')->setData($data)->save();
