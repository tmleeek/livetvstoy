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

$block = array(
    'title' => 'Navigation menu dynamic block - PP',
    'identifier' => 'nav-menu-dynamic-block-pp',
    'content' => '
<span>
    <img src="{{media url=images/default-block-image.jpg}}"
    alt="Default Category" />
</span>
<a class="level-top" title="Back to school">
<span>BACK-TO-SCHOOL</span>
</a>
',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);
//creating block
$blockId = Mage::getModel('cms/block')
    ->setTitle($block['title'])
    ->setIdentifier($block['identifier'])
    ->setStores(array($storeId))
    ->setContent($block['content'])
    ->setIsActive($block['is_active'])
    ->save();