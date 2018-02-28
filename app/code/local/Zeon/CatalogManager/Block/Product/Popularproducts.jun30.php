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
 * newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_CatalogManager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_CatalogManager_Block_Product_Popularproducts
    extends Mage_Catalog_Block_Product_List
{
    /**
     * Class variables.
     */
    protected $_productCollection;
    protected $_sortBy;

    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $helper = Mage::helper('zeon_catalogmanager');
            $breadcrumbsBlock->addCrumb(
                'home', array(
                'label' => $helper->__('Home'),
                'title' => $helper->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
                )
            );
        }

        parent::_prepareLayout();
    }

    /**
     * Remove "Position" option from Sort By dropdown
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $toolbar = $this->getToolbarBlock();
        $toolbar->removeOrderFromAvailableOrders('position');
        return $this;
    }

    /**
     * Load most popular products collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $order = $this->getRequest()->getParam('order', 'most_popular');
            $dir   = $this->getRequest()->getParam('dir', 'desc');

            $collection = Mage::getModel('catalog/product')->getCollection();

            $attributes = Mage::getSingleton('catalog/config')
                ->getProductAttributes();

            $collection->addAttributeToSelect($attributes)
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToFilter('most_popular', 1, 'left')
                ->addStoreFilter();

            Mage::getSingleton('catalog/product_status')
                ->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')
                ->addVisibleInCatalogFilterToCollection($collection);

            $collection->addAttributeToSort($order, $dir);
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    /**
     * Retrieve loaded most popular products collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getMostPopularProductCollection()
    {
        return $this->_getProductCollection();
    }
}