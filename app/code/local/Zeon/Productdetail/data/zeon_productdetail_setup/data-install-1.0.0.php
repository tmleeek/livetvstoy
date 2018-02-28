<?php
/**
 * @category    Zeon
 * @package     Catalog Manager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Get store ids
$stores = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

//database read adapter
$read = Mage::getSingleton("core/resource")->getConnection("core_read");
$select = $read->select()->from(
    array("a" => "eav_attribute"),
    array("attribute_id", "attribute_code", "frontend_label")
)->join(
    array("ao" => "eav_attribute_option"),
    "a.attribute_id = ao.attribute_id",
    array()
)->join(
    array("cea" => "catalog_eav_attribute"),
    "a.attribute_id = cea.attribute_id",
    array("is_configurable", "is_global")
)->where(
    "is_configurable = '1' AND is_global = '1' "
    . " AND a.frontend_input = 'select'"
)
->group("a.attribute_id")
->order("a.attribute_id");

$data = $read->fetchAll($select);
$data[] = array(
    'attribute_code' => 'ship_details',
    'frontend_label' => 'Ship Details'
);

// get settings
$details =  Mage::helper('zeon_productdetail')
    ->getConfigDetails('productdetails');
$identifier = $details['popup_identifier'];

// add popup static blocks
foreach ($stores as $store) {
    foreach ($data as $attribute) {
        $name = str_replace(' ', '-', strtolower($attribute['frontend_label']));
        $title = 'Product Detail Page '.$attribute['frontend_label'];
        $block = Mage::getModel('cms/block');
        $block->setTitle($title);
        $block->setIdentifier($identifier.$name);
        $block->setStores(array($store));
        $block->setIsActive(1);
        $block->setContent(
            $attribute['frontend_label']. ' Details <br>'
            . 'Block: '.'Product Detail Page '.$attribute['frontend_label']
        );
        $block->save();
    }
}

// add location column in review table
$installer->run(
    "ALTER TABLE review_detail ADD COLUMN location VARCHAR(255) NULL"
);

// End of installer script.
$installer->endSetup();