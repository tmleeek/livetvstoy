<?php
//upgrade script for creating static pages for limoges store


$staticBlock = array(
    'title' => 'Product Detail Page Ship Details',
    'identifier' => 'detail_popup_ship-details',
    'content' => '<p>Ship Details Details <br />Block: Product Detail Page Ship Details</p>',
    'is_active' => 1,
    'stores' => array(5)
);

Mage::getModel('cms/block')->setData($staticBlock)->save();