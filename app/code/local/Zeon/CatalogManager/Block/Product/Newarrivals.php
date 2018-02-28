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
class Zeon_CatalogManager_Block_Product_Newarrivals
    extends Mage_Catalog_Block_Product_List
{
    /**
     * Class variables.
     */
    protected $_productCollection;

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
     * Load best seller products collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $todayStartDate = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

            $todayEndDate = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

            $order = $this->getRequest()->getParam('order', 'news_from_date');
            $dir   = $this->getRequest()->getParam('dir', 'desc');

            // @var $collection Mage_Catalog_Model_Resource_Product_Collection
            $collection = Mage::getResourceModel('catalog/product_collection');
            $collection->setVisibility(
                Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds()
            );

            //Exclude products of particular category
            $configSettings = Mage::helper('zeon_catalogmanager')->getConfigDetails('new_arrival');
            if (isset($configSettings['excluded_category']) && trim($configSettings['excluded_category'])) {
                $categoryProductTable   =  Mage::getSingleton('core/resource')
                    ->getTableName('catalog/category_product');
                $collection->getSelect()->Where(
                    'e.entity_id NOT IN (SELECT product_id FROM '.$categoryProductTable.'
                    WHERE category_id ='.trim($configSettings['excluded_category']).')'
                );
            }

            $collection = $this->_addProductAttributesAndPrices($collection)
                ->addStoreFilter()
                ->addAttributeToFilter(
                    'news_from_date',
                    array(
                        'or' => array(
                                0 => array(
                                    'date' => true, 'to' => $todayEndDate
                                    ),
                                1 => array('is' => new Zend_Db_Expr('null'))
                        )
                    ),
                    'left'
                )
                ->addAttributeToFilter(
                    'news_to_date',
                    array(
                        'or' => array(
                                0 => array(
                                    'date' => true, 'from' => $todayStartDate
                                ),
                                1 => array('is' => new Zend_Db_Expr('null')))
                    ),
                    'left'
                )
                ->addAttributeToFilter(
                    array(
                        array(
                            'attribute' => 'news_from_date',
                            'is'        => new Zend_Db_Expr('not null')
                        ),
                        array(
                            'attribute' => 'news_to_date',
                            'is'        => new Zend_Db_Expr('not null')
                        )
                    )
                )
                ->addAttributeToSort($order, $dir)
                ->setPageSize($this->getProductsCount())
                ->setCurPage(1);
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    /**
     * Retrieve loaded best seller products collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getNewArrivalProductCollection()
    {
        return $this->_getProductCollection();
    }
}