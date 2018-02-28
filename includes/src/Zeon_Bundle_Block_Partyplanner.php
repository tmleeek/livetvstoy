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
 * @package     Zeon_Bundle
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_Bundle_Block_Partyplanner extends Mage_Core_Block_Template
{
    /**
     * Class variables.
     */
    protected $_productCollection;

    /**
     * Method used to get party planner product collection.
     *
     * @return Object
     */
    public function getPartyPlannerProductCollection()
    {
        //
        return $this->_getProductCollection();
    }

    /**
     * Method to load the product collection.
     * @return type
     */
    protected function _getProductCollection()
    {
        // Check, if the collection var is null then query to database.
        if (is_null($this->_productCollection)) {
            // Get the store-id.
            $storeId = Mage::app()->getStore()->getId();

            // Get the product collection.
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->addAttributeToSelect(array('name', 'small_image'))
                ->addAttributeToFilter('is_party_planner', 1, 'left')
                ->setStoreId($storeId)
                ->addStoreFilter($storeId);

            // Filter the collection by Visibility and Status.
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            // Set the page limit.
            $collection->setPageSize(18);

            // Set the collection to a var.
            $this->_productCollection = $collection;
        }

        // Return the party planner product collection.
        return $this->_productCollection;
    }

}