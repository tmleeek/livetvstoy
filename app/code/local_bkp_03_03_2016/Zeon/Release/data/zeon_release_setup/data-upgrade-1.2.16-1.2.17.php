<?php
// Get all the stores.
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 0))
    ->getAllIds();
$packages = array(
    0 => 'enterprise/tystoybox',
    1 => 'enterprise/pbskids',
);
$storeName = array(
    0 => 'TYS',
    1 => 'PBS',
);
// initializing static blocks
$block = array(
    'title' => 'Checkout Custom Printed Items Message',
    'identifier' => 'checkout_custom_printed_item_message',
    'content' => '
        <p class="custom-printed-msg ">
        Custom printed items (with or without name)
        ship 2-4 business days after order.
        </p>
        ',
    'is_active' => 1,
    'stores' => array(0)
);
//saving block
// Loop on all the stores to create the block for each store.
foreach ($stores as $storeOrder => $store) {
    Mage::getModel('cms/block')
        ->setTitle($block['title'].' - '.$storeName[$storeOrder])
        ->setIdentifier($block['identifier'].'_'.$storeName[$storeOrder])
        ->setStores(array($store))
        ->setContent($block['content'])
        ->setIsActive($block['is_active'])
        ->save();
}