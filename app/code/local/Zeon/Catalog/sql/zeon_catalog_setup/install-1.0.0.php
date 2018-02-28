<?php
// initializing static blocks
$blockData = array(
    'title' => 'Sub-Category List',
    'identifier' => 'sub_category_list',
    'content' => '
        {{block type="catalog/category_list"
        template="catalog/category/list.phtml"}}
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
