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
 * Do not edit or add to this file if you wish to upgrade this extension to
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Fqs
 * @package     Fqs_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc.
 * All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_Attributemapping_Block_Adminhtml_Switcher
    extends Mage_Adminhtml_Block_Template
{


    /**
     * @var bool
     */
    protected $_hasDefaultOption = true;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('zeon/attributemapping/switcher.phtml');
        $this->setUseConfirm(true);
        //$this->setUseAjax(true);
    }

    /**
     * Deprecated
     */
    public function getFilterableAttributes()
    {
        /** @var $collection Mage_Catalog_Model_Resource_
         * Product_Attribute_Collection
         **/
        $collection = Mage::getResourceModel(
            'catalog/product_attribute_collection'
        );
        $collection
            ->setItemObjectClass('catalog/resource_eav_attribute')
            ->addStoreLabel(Mage::app()->getStore()->getId())
            ->setOrder('position', 'ASC');
        $collection = $this->_prepareAttributeCollection($collection);
        $collection->load();

        return $collection;
    }

    /**
     * get store data
     */
    protected function _getStoreId()
    {
        $defaultStore = $storeId = Mage::helper('zeon_attributemapping')
            ->getTopStore();
        $storeId = (int) $this->getRequest()->getParam('store', $defaultStore);
        return $storeId;
    }

    /**
     * Add filters to attribute collection
     *
     * @param   Mage_Catalog_Model_Resource_Eav_Mysql4
     * _Attribute_Collection $collection
     * @return  Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection
     */
    protected function _prepareAttributeCollection($collection)
    {
        $collection->addIsFilterableFilter();
        return $collection;
    }


    public function getAttributeSwitchUrl($attributeId)
    {
        $attributeSwitchUrl = Mage::helper("adminhtml")
            ->getUrl(
                '/index/list/',
                array('id'=>$attributeId, 'store'=>$this->_getStoreId())
            );
        return $attributeSwitchUrl;
    }
}
