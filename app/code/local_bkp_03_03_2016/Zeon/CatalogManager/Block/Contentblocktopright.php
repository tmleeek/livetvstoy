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
class Zeon_CatalogManager_Block_Contentblocktopright
    extends Mage_Core_Block_Template
{
    /**
     * Class variables.
     */
    protected $_productCollection;

    /**
     * Method used to get featured product collection.
     *
     * @return Object
     */
    public function getContentBlockTopRightCollection()
    {
        // Check, whether the feature is enable or disabled.
        $isActive = Mage::getStoreConfig(
            'zeon_catalogmanager/content_block_top_right/active'
        );

        // If the feature is disabled then return blank.
        if ($isActive) {
            return $this->_getProductCollection();
        }
    }

    /**
     * Method to load the product collection.
     * @return type
     */
    protected function _getProductCollection()
    {
        // Check, if the collection var is null then query to database.
        if (is_null($this->_productCollection)) {

            //get inactive categories
            $categories = Mage::helper('zeon_catalogmanager')->getInActiveCategories();

            // Get the product collection.
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->addAttributeToSelect(array('name', 'content_top_right_position', 'small_image', 'product_flags'))
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToFilter('content_top_right', 1, 'left')
                ->addStoreFilter();

            $collection->joinField(
                'category_id',
                'catalog/category_product',
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter('category_id', array('nin' => $categories));
            $collection->getSelect()->group('e.entity_id');

            // Filter the collection by Visibility and Status.
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            // Set the order-by clause.
            $collection->addAttributeToSort('gift_for_baby_position', 'asc');


            // Set the page limit.
            $configSettings = Mage::helper('zeon_catalogmanager')->getConfigDetails('best_products');
            $collection->setPageSize($configSettings['number_of_items']);
            // Set the collection to a var.
            $this->_productCollection = $collection;
        }
        // Return the best seller product collection.
        return $this->_productCollection;
    }

}