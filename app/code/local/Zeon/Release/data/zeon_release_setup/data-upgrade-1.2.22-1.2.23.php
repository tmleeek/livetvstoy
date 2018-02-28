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
//array for cms pages
$cmsPages = array(
    array(
        'title' => 'Home Page - PP',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'home',
        'content_heading' => 'Customer Service Ordering',
        'content' => '
            Your Home page contents goes here
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 1
    )
);

/**
 * Insert default and system pages
 */
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}