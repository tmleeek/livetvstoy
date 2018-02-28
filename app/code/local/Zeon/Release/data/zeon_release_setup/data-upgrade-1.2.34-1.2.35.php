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
//array for cms pages
$cmsPages = array(
    array(
        'title' => '404 Not Found - Limoges',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'no-route',
        'content_heading' => '',
        'stores' => array($storeId),
        'content' => '
<div class="container-404-page">
<div class="col-lg-3 col-md-2 col-sm-3 col-xs-4 image-404">
<img title="We can&rsquo;t find what you&rsquo;re looking for."
src="{{skin url=images/pbs_404_image.png}}" alt="Sorry Image" /></div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7">
<h2>oops !</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7">
<span>We can&rsquo;t find what you&rsquo;re looking for.</span>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
<p>If you typed the URL directly, please make sure the spelling is correct.
If you clicked on a link to get here, we may have moved the content.</p>
</div>
<div class="search-container col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
<div class="search-404 col-lg-7 col-md-7 col-sm-7 col-xs-12">
{{block type="core/template" name="error.search" as="errorSearch"
template="catalogsearch/errorform.mini.phtml"}}</div>
<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 return-link">
<a href="{{store url=""}}"><strong>Return to homepage<strong></strong>
</strong></a></div>
</div>
</div>
        ',
        'is_active'     => 1,
        'sort_order'    => 10
    )
);
/**
 * Insert default and system pages
 */
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}