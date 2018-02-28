<?php
// initializing static blocks
$blockData = array(
    'title' => 'Tystoybox category left',
    'identifier' => 'tystoybox_categories',
    'content' => '
        {{block type="catalog/category_list"
        name="tystoybox_category"
        as="tystoybox_category"
        template="catalog/category/tystoyboxcategory.phtml"}}
        ',
    'is_active' => 1,
    'stores' => array(0)
);
//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($blockData['title'])
    ->setIdentifier($blockData['identifier'])
    ->setStores($blockData['stores'])
    ->setContent($blockData['content'])
    ->setIsActive($blockData['is_active'])
    ->save();
