<?php
/**
 * Created by PhpStorm.
 * User: aniket.nimje
 * Date: 8/18/14
 * Time: 12:12 PM
 */
$installer = $this;
$installer->startSetup();
$installer->addAttribute('catalog_category', 'perfect_gift_category',  array(
    'group'        => 'General Information',
    'type'         => 'int',
    'label'        => 'Perfect Gift Category',
    'input'        => 'select',
    'global'       => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'      => true,
    'required'     => false,
    'user_defined' => false,
    'default'      => 0,
    'source'       => 'eav/entity_attribute_source_boolean'
));
$installer->addAttribute('catalog_category', 'best_seller_category',  array(
    'group'        => 'General Information',
    'type'         => 'int',
    'label'        => 'Best Seller Category',
    'input'        => 'select',
    'global'       => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'      => true,
    'required'     => false,
    'user_defined' => false,
    'default'      => 0,
    'source'       => 'eav/entity_attribute_source_boolean'
));
$this->endSetup();