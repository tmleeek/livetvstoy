<?php
$installer = $this;

$installer->startSetup();

// Get store ids
$stores = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

$content = '<img src="{{skin url=images/free-shiping-desktop.jpg}}"
    alt="Character Offer" width="95%" /> <br> Lorem ipsum dolor sit amet,
    consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
    ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
    pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa
    qui officia deserunt mollit anim id est laborum.';

$charBlock = Mage::helper('zeon_attributemapping')
    ->getConfigData('front_scroller/character_block');

foreach ($stores as $store) {
    $block = Mage::getModel('cms/block');
        $block->setTitle('Character List Page Top Block');
        $block->setIdentifier($charBlock);
        $block->setStores(array($store));
        $block->setIsActive(1);
        $block->setContent($content);
        $block->save();
}

Mage::getModel('zeon_attributemapping/Urlcron')->setAttributeUrls();
$installer->endSetup();
