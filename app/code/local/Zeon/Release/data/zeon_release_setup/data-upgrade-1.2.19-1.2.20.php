<?php
/**
 * This installer script is used to create the product attributes for "UPC".
 *
 * @category    Zeon
 * @package     Release
 * @author      Aniket Nimje <aniket.nimje@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

$code = 'bestselling_products';
$installer->updateAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    $code,
    array(
        'is_visible_on_front' => '1',
        'used_in_product_listing' => '1',
        'used_for_sort_by' => '1'
    )
);