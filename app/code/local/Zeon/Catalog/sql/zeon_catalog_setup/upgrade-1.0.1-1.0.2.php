<?php
/**
 * Installer script for adding video_url product attribute
 */
$installer = $this;
$installer->startSetup();
$installer->addAttribute('catalog_product', 'video_url', array(
    'group'             => 'General',
    'label'             => 'Video Url',
    'type'              => 'varchar',
    'input'             => 'text',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => 1,
    'required'          => 0,
    'user_defined'      => 1,
    'searchable'        => 0,
    'filterable'        => 0,
    'comparable'        => 0,
    'visible_on_front'  => 0,
    'visible_in_advanced_search'=> 0,
    'unique'            => 0,
    'default'           => ''
));
$installer->endSetup();