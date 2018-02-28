<?php
/**
 * Zeon Solutions, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zeon Solutions License
 * that is bundled with this package in the file LICENSE_ZE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zeonsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension
 * to newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.
 *    (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

class Zeon_Attributemapping_Model_Attributemapping
    extends Mage_Core_Model_Abstract
{
    const FILE_PATH = 'characters/';
    public function _construct()
    {
        parent::_construct();
        $this->_init('zeon_attributemapping/attributemapping');
    }

    /**
     *
     * Get attribute details
     * @param string $argAttribute
     * @param string $argValue
     * @param int $editOption
     */
    function attributeValueExists($argAttribute, $argValue, $editOption)
    {
        $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $argAttribute);

        $options   = $attribute->getSource()->getAllOptions();

        foreach ($options as $option) {
            foreach ($argValue as $key=>$value) {
                if (($option['label'] == trim($value))
                    && ($editOption != $option['value'])) {
                    return $option['value'];
                }
            }
        }
        return false;
    }

    /**
     *
     * save attribute option
     * @param string $optionLabel
     * @param int $attributeId
     */
    function setOptionasDefault($optionLabel,$attributeId)
    {
        $attribute = Mage::getModel('eav/entity_attribute')->load($attributeId);
        $productModel = Mage::getModel('catalog/product');
        $attr = $productModel->getResource()
            ->getAttribute($attribute->getAttributeCode());
        $attribute->setDefaultValue(
            $attr->getSource()->getOptionId($optionLabel)
        );
        $attribute->save();
        return;
    }

    /**
     *
     * Delete attribute option mapping data
     * @param int $attributeId
     * @param int $optionId
     */
    public function deleteAttrData($attributeId,$optionId)
    {
        if (empty($attributeId) || empty($optionId)) {
            return;
        }
        $allStores =  Mage::app()->getStores();
        foreach ($allStores as $store) {
            $this->getCollection()
                ->attributeDeleteData($store->getId(), $optionId);
        }
        return;
    }

    /**
     *
     * update table with attribute data
     * @param array $binds
     * @param array $dataIns
     * @param int $store
     * @param object $installer
     */
    public function UpdateDatatoTable($binds, $dataIns, $store, $installer)
    {
        try {
            $tableName = $installer->getTable(
                array('zeon_attributemapping/attributemapping', $store)
            );

            $sql = "INSERT INTO `" . $tableName . "` "
                . " (`attribute_id`,`option_id`,`option_status`,`url_key`) "
                . " VALUES "
                . implode(",", $dataIns)
                . " ON DUPLICATE KEY UPDATE `url_key` = VALUES(`url_key`)";
            $installer->run($sql);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     *
     * get formatted string for url
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $urlKey = preg_replace(
            '#[^0-9a-z]+#i', '-',
            Mage::helper('catalog/product_url')->format($str)
        );
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }

    /**
     * get scroller attribute data
     */
    public function getScrollerData()
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $code = Mage::helper('zeon_attributemapping')
            ->getConfigData('front_scroller/slider_attribute');
        $limit = Mage::helper('zeon_attributemapping')
            ->getConfigData('front_scroller/item_count');
        $attId = Mage::getModel('eav/entity_attribute')
            ->loadByCode('catalog_product', $code)->getId();
        $store = Mage::app()->getStore()->getId();

        $condition = 'AND att_table.display_in_slider = \'1\''
            . ' ORDER BY att_table.sort_order ASC LIMIT '.$limit;
        $query = $this->getCollection()
            ->getAttributeData($attId, $store, $condition);

        $readresult = $read->query($query);
        return $readresult;
    }

    /**
     * function to get active options
     * @param object $attribute
     */
    public function getActiveAttributes($attribute)
    {
        $optionsId = array();
        if (in_array($attribute->getFrontendInput(), array('select', 'multiselect'))) {
            $optionsId = $this->getCollection()
                ->addFieldToFilter('attribute_id', $attribute->getId())
                ->addFieldToFilter('option_status', '1')
                ->getColumnValues('option_id');
        }
        return $optionsId;
    }

}
