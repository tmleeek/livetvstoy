<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("catalog_category", "category_banner_url",  array(
    "type"     => "varchar",
    "backend"  => "",
    "frontend" => "",
    "label"    => "Banner Url",
    "input"    => "text",
    "class"    => "",
    "source"   => "",
    "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    "visible"  => true,
    "required" => false,
    "user_defined"  => false,
    "default" => "",
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,

    "visible_on_front"  => false,
    "unique"     => false,
    "note"       => "add an optional URL field to make category banner image clickable",
    "group" => "General Information",
    "sort_order" => 6

    ));
$installer->endSetup();
