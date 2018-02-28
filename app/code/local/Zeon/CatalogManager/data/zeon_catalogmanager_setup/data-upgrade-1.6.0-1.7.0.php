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

$codes = array(
    'gift_for_him_position' => 'homepage_option1_position',
    'gift_for_him'          => 'homepage_option1',
    'gift_for_her_position' => 'homepage_option2_position',
    'gift_for_her'          => 'homepage_option2',
    'gift_for_baby_position' => 'homepage_option3_position',
    'gift_for_baby'          => 'homepage_option3',
);

$attributeData = Mage::getModel('eav/entity_attribute')->getCollection()
    ->addFieldToFilter('attribute_code', array('in' => array_keys($codes)) );

    foreach ($attributeData as $attribute) {
        $attribute->setData('attribute_code', $codes[$attribute->getAttributeCode()]);
        $attribute->save();
    }

$staticBlocks = array(
    'homepage_gifts_for_him' => array('block' => 'homepageoption1', 'phtml' => 'block_homepage_option1'),
    'homepage_gifts_for_her' => array('block' => 'homepageoption2', 'phtml' => 'block_homepage_option2'),
    'homepage_gifts_for_baby' => array('block' => 'homepageoption3', 'phtml' => 'block_homepage_option3'),
);

$storeId = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('code', 'pp_storeview')
	->getFirstItem();

$blocks = Mage::getModel('cms/block')->getCollection()
    ->addFieldToFilter('identifier', array('in' => array_keys($staticBlocks)) );

    foreach ($blocks as $blockData) {
        $staticData = $staticBlocks[$blockData->getIdentifier()];
        $contents = '{{block type="zeon_catalogmanager/'.$staticData['block'].'" name="'.$staticData['block'].'"
            as="'.$staticData['block'].'" template="zeon/catalogmanager/'.$staticData['phtml'].'.phtml"}}';
        $blockData->setData('content', $contents);
        $blockData->setStores(array($storeId->getId()));
        $blockData->save();
    }


$installer->endSetup();