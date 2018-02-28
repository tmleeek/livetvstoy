<?php
/**
 * Created by PhpStorm.
 * User: aniket.nimje
 * Date: 8/13/14
 * Time: 7:43 PM
 */
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$setup->updateAttribute('catalog_product', 'content_top_right_position', 'used_in_product_listing',1);
$setup->updateAttribute('catalog_product', 'content_top_right', 'used_in_product_listing',1);

$installer->endSetup();