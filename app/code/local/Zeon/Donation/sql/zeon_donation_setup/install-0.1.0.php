<?php
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');;
$installer->startSetup();
Mage::app()->setUpdateMode(false);
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
$product = Mage::getModel('catalog/product');
// Build the product
$product->setSku('donate');
$product->setAttributeSetId(4);
$product->setTypeId('simple');
$product->setName('Donation');
$product->setWebsiteIDs(array(2));
$product->setDescription('For Donation Of $1');
$product->setShortDescription('For Donation Of $1');
$product->setPrice(1); # Set some price
//Default Magento attribute
$product->setWeight(0.0000);
$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG);
$product->setStatus(1);
$product->setTaxClassId(0); # My default tax class
$product->setStockData(
    array(
        'manage_stock'=>0,
        'use_config_manage_stock' =>0,
        'min_sale_qty'=>1,
        'max_sale_qty'=>1
    )
);
$product->setCreatedAt(strtotime('now'));
try {
    $product->setInitialSetupFlag(true)->save();
}
catch (Exception $ex) {
    Mage::throwException($ex);
}