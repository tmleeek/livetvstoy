<?php
$installer = $this;

$installer->startSetup();

// Get store ids
$stores = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

$attributeHelper = Mage::helper('zeon_attributemapping');
$attributsCodes = $attributeHelper->getConfigDetails('attribute_list');
$attributsCodes = @explode(',', $attributsCodes);
// get all attribute data
$entityTypeId = Mage::getModel('eav/entity_type')
    ->loadByCode('catalog_product')->getEntityTypeId();
$attributes = Mage::getModel('eav/entity_attribute')
    ->getCollection()
    ->addFieldToFilter('entity_type_id', $entityTypeId)
    ->addFieldToFilter('attribute_code', array('in' => $attributsCodes));
foreach ($attributes as $attributeData) {
    $attributeId = $attributeData->getId();
    $attributeDetails = Mage::getSingleton("eav/config")
        ->getAttribute("catalog_product", $attributeData->getAttributeCode());
    $options = $attributeDetails->getSource()->getAllOptions(false);
    $ins = NULL;
    foreach ($stores as $store) {
        foreach ($options as $option) {
            $binds = NULL;
            $urlKey = Mage::getsingleton(
                'zeon_attributemapping/attributemapping'
            )->formatUrlKey($option["label"]);
            //for selected store
            $binds[] = $attributeId;
            $binds[] = $option["value"];
            $binds[] = '1';
            $binds[] = $urlKey;
            $ins[] = "('".@implode('\',\'', $binds)."')";
        }
        Mage::getsingleton('zeon_attributemapping/attributemapping')
            ->UpdateDatatoTable($binds, $ins, $store, $installer);
    }
}

Mage::getModel('zeon_attributemapping/Urlcron')->setAttributeUrls();
Mage::getModel('zeon_attributemapping/Menucron')->setSiteTopMenu();

$installer->endSetup();
